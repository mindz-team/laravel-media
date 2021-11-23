<?php

namespace Mindz\LaravelMedia\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class MediaLibrary extends Model implements HasMedia
{
    use InteractsWithMedia;

    public $exists = true;

    protected $table = 'media';

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->forceFill(['id' => 1]);
    }
}
