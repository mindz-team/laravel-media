<?php

namespace Mindz\LaravelMedia\Observers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Mindz\LaravelMedia\Traits\HandleMediables;

class MediableObserver
{
    use HandleMediables;

    public function saving(Model $model)
    {
        $this->getMediablesFromAttributes($model);
    }

    private function getMediablesFromAttributes(Model $model)
    {
        $model->mediables = array_intersect_key($model->getAttributes(), $this->getMediableProperties($model));
        $model->setRawAttributes(array_diff_key($model->getAttributes(), $model->mediables));
    }

    private function getMediableProperties(Model $model)
    {
        return Arr::where($model->getAttributes(), function ($value, $key) use ($model) {
            foreach ($model->mediaCollections as $mediaCollections) {
                if ($key === $mediaCollections->name) {
                    return true;
                }
            }

            return false;
        });
    }

    public function saved(Model $model)
    {
        $this->handleMediables($model);
    }

}
