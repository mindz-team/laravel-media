# Laravel Media

Package utilizes `spatie/laravel-medialibrary` package and allows to use media as properties and painlessly use them.

# Installation

You can install package via composer. Add repository to your composer.json

    "repositories": [
        {
            "type": "vcs",
            "url": "https://github.com/mindz-team/laravel-media"
        }
    ],

And run

    composer require mindz-team/laravel-media

Publish migrations

    php artisan vendor:publish --provider="Spatie\MediaLibrary\MediaLibraryServiceProvider" --tag="migrations"
    php artisan migrate

Publishing the config file is optional:

    php artisan vendor:publish --provider="Spatie\MediaLibrary\MediaLibraryServiceProvider" --tag="config"

Set default temporary upload model

```
    'temporary_upload_model' => \Mindz\LaravelMedia\Models\MediaLibrary::class
```

# Usage

Model must use trait `Shortcodes/Media/Mediable` and implement HasMedia interface

## Register collections

To register collections add method registerMediaCollections and add collections you need

```
public function registerMediaCollections()
{
    $this->addMediaCollection('avatar')->singleFile();
    $this->addMediaCollection('my-other-collection');
}
```

> You can define if collection is singular (add singleFile() method) or multiple (by default)

## Uploading media files

To be able to upload media easily using this package you must create controller and use `upload` method of `MediaFacade`
class link in example below.

```
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Mindz\LaravelMedia\Facades\MediaFacade as Media;

class MediaController
{
    public function upload(Request $request)
    {
        return Media::upload($request->file('example'));
    }
}

```

> By default response is `$media->toArray()` object.

There is an option to uploade file using `uploadContent` method which allows to use file in string representation

```
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Mindz\LaravelMedia\Facades\MediaFacade as Media;

class MediaController
{
    public function upload(Request $request)
    {
        return Media::uploadContent($request->file('example')->getContent(), $request->file('example')->getClientOriginalName());
    }
}

```

To use default package resource `Mindz\LaravelMedia\Resources\UploadResource` use `usingResource` method

```
return Media::usingResource()->upload($request->file('example'));
```

To use resource of your preference use `usingResource` method with your resource class as parameter

```
return Media::usingResource(\App\Resources\MyResource::class)->upload($request->file('example'));
```

## Direct assignment

To assign uploaded file to custom object directly use `usingObject`

```
$object = User::factory()->create;
return Media::usingObject($object)->upload($request->file('example'));
```

In addition, you can choose collection to which item should be assigned. You can do it with method `assCollection`

```
$object = User::factory()->create;
return Media::usingObject($object)->asCollection('avatar)->upload($request->file('example'));
```

## Models unassigned to any model

Free spatie library does not allows to upload media without any model. Therefore assets are binded to dummy model. This
assignment can be treated as package library of medias. 

Default model should be set in `config.media-library.php` file

```
'temporary_upload_model' => \Mindz\LaravelMedia\Models\MediaLibrary::class
```



## Assigning assets to models

To assign uploaded asset to model you have to simply update/create model with properties names registered
in `registerMediaCollections`

### Single file registered collection

```
Model::update(
    'avatar': 1
]);
```

### Multiple files registered collection

```
Model::update(
    'my-other-collection': [ 1,2,3,4 ]
]);
```

In `my-other-collection` property collection you need to provide array of media ids received while uploading media.

In this case images are automatically reordered by provided ids.

> REMEMBER! While updating model object collection not described as single all skipped media id in array will be deleted.

## Attaching multiple files to model without removing current

In case you need to add media file without removing all missing in array you can use `add` key in collection data
request

```
Model::update([
    'my-other-collection': [
        'add' => [1]
    ]
]);
```

## Deleting selected files from model

In case you need to remove selected media from object you can use `delete` key in collection data request

```
Model::update([
    'my-other-collection': [
        'delete' => [3, 4]
    ]
]);
```

## Additional features

More about library can be found at official [documentation page](https://github.com/spatie/laravel-medialibrary)

# Change log

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

# Security

If you discover any security related issues, please email r.szymanski@mindz.it instead of using the issue tracker.

# Credits

Author: Roman Szymański [r.szymanski@mindz.it](mailto:r.szymanski@mindz.it)

# License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
