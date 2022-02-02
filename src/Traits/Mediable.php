<?php

namespace Mindz\LaravelMedia\Traits;

use Mindz\LaravelMedia\Observers\MediableObserver;
use Spatie\MediaLibrary\InteractsWithMedia;

trait Mediable
{
    use InteractsWithMedia;

    public $mediables = [];

    public static function bootMediable()
    {
        static::observe(MediableObserver::class);
    }

    public function initializeMediable()
    {
        if (!$this->mediaCollections) {
            $this->getRegisteredMediaCollections();
        }

        $collectionsName = array_map(function ($item) {
            return $item->name;
        }, $this->mediaCollections);

        $this->fillable(array_merge($this->fillable, $collectionsName));
    }
}
