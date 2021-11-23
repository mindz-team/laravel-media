<?php

namespace Mindz\LaravelMedia;

use Illuminate\Support\Facades\App;
use Illuminate\Support\ServiceProvider;
use Mindz\LaravelMedia\Actions\MediaActions;

class LaravelMediaServiceProvider extends ServiceProvider
{
    public function boot()
    {
    }

    public function register() {
        App::bind('media-actions',function() {
            return new MediaActions();
        });
    }
}
