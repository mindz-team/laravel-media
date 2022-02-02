<?php

namespace Mindz\LaravelMedia\Actions;

use Mindz\LaravelMedia\Models\MediaLibrary;
use Mindz\LaravelMedia\Resources\UploadResource;

class MediaActions
{
    private $resource;

    public function usingResource($resourceClass = null)
    {
        if (!$resourceClass) {
            $resourceClass = UploadResource::class;
        }

        $this->setResource($resourceClass);

        return $this;
    }

    private function setResource($resourceClass)
    {
        $this->resource = $resourceClass;
    }

    public function upload($file)
    {
        $mediaLibraryClass = config(config('media-library.temporary_upload_model'), MediaLibrary::class);
        $mediaLibrary = new $mediaLibraryClass();

        $media = $mediaLibrary->addMedia($file)->toMediaCollection();

        if ($resource = $this->getResource()) {
            return new $resource($media);
        }

        return $media->toArray();
    }

    private function getResource()
    {
        return $this->resource;
    }
}
