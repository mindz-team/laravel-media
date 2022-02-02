<?php

namespace Mindz\LaravelMedia\Traits;

use Illuminate\Database\Eloquent\Model;
use Mindz\LaravelMedia\Models\MediaLibrary;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

trait HandleMediables
{
    private function handleMediables(Model $model)
    {
        foreach ($model->mediables as $mediable => $value) {
            foreach ($model->mediaCollections as $mediaCollection) {
                if ($mediaCollection->name === $mediable && $mediaCollection->singleFile) {
                    $this->handleSingleMedia($model, $value, $mediable);
                    break;
                } elseif ($mediaCollection->name === $mediable && !$mediaCollection->singleFile) {
                    $this->handleMultipleMedia($model, $value, $mediable);
                    break;
                }
            }
        }
    }

    private function handleSingleMedia(Model $model, $id, $collection)
    {
        if (!$id) {
            optional($model->getFirstMedia($collection))->delete();
            return;
        }

        Media::where('model_type', get_class($model))
            ->where('model_id', $model->id)
            ->where('id', $id)
            ->delete();

        $mediaLibraryClass = config('media-library.temporary_upload_model', MediaLibrary::class);
        $media = Media::where('model_type', $mediaLibraryClass)->where('id', $id)->first();

        if ($media) {
            $media->move($model, $collection);
        }
    }

    private function handleMultipleMedia(Model $model, $ids, $collection)
    {
        if (!is_array($ids)) {
            abort(404);
        }

        if (isset($ids['delete'])) {
            $this->handleDeleteMultipleMedia($model, $ids['delete']);
            return;
        }

        if (isset($ids['add'])) {
            $this->handleAddMultipleMedia($model, $ids['add'], $collection);
            return;
        }

        Media::where('model_type', get_class($model))
            ->where('model_id', $model->id)
            ->where('collection_name', $collection)
            ->whereNotIn('id', $ids)
            ->delete();

        $mediaLibraryClass = config('media-library.temporary_upload_model', MediaLibrary::class);
        $media = Media::where('model_type', $mediaLibraryClass)->whereIn('id', $ids);

        foreach ($media->get() as $item) {
            $newMedia = $item->move($model, $collection);

            if (($key = array_search($item->id, $ids)) !== false) {
                $ids[$key] = $newMedia->id;
            }
        }

        Media::setNewOrder($ids);
    }

    private function handleDeleteMultipleMedia(Model $model, $ids)
    {
        Media::where('model_type', get_class($model))
            ->where('model_id', $model->id)
            ->whereIn('id', $ids)
            ->delete();
    }

    private function handleAddMultipleMedia(Model $model, $ids, $collection)
    {
        foreach ($ids as $id) {
            $media = Media::where('id', $id)->first();

            if ($media) {
                $newMedia = $media->move($model, $collection);
                $newMedia->manipulations = $media->manipulations;
                $newMedia->save();
            }
        }
    }

}
