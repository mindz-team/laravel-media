<?php

namespace Mindz\LaravelMedia\Actions;

use Mindz\LaravelMedia\Models\MediaLibrary;
use Mindz\LaravelMedia\Resources\UploadResource;

class MediaActions
{
    private $resource;
    private $model;
    private $collectionName;

    public function usingObject(Model $object)
    {
        $this->model = $object;
        return $this;
    }

    public function asCollection($collectionName)
    {
        $this->collectionName = $collectionName;
        return $this;
    }

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
        $mediaLibraryClass = config('media-library.temporary_upload_model', MediaLibrary::class);
        $mediaLibrary = new $mediaLibraryClass();

        if ($this->model && $this->collectionName) {
            $mediaLibrary = $this->model;
        }

        $media = $mediaLibrary->addMedia($file)->toMediaCollection($this->collectionName ?? 'default');

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
