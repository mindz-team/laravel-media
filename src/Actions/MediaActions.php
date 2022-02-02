<?php

namespace Mindz\LaravelMedia\Actions;

use Mindz\LaravelMedia\Models\MediaLibrary;
use Mindz\LaravelMedia\Resources\UploadResource;

class MediaActions
{
    private $resource;
    private $mediaLibrary;

    public function usingResource($resourceClass = null)
    {
        if (!$resourceClass) {
            $resourceClass = UploadResource::class;
        }

        $this->setResource($resourceClass);

        return $this;
    }

    public function usingMedia($mediaLibraryClass = null)
    {
        if (!$mediaLibraryClass) {
            $mediaLibraryClass = MediaLibrary::class;
        }

        $this->setMediaLibrary($mediaLibraryClass);

        return $this;
    }

    private function setResource($resourceClass)
    {
        $this->resource = $resourceClass;
    }

    private function setMediaLibrary($mediaLibraryClass)
    {
        $this->mediaLibrary = $mediaLibraryClass;
    }

    public function upload($file)
    {
        $mediaLibraryClass = $this->getMediaClass();
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

    private function getMediaClass()
    {
        return $this->mediaLibrary ?? MediaLibrary::class;
    }
}
