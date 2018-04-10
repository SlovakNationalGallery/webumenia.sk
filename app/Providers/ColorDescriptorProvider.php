<?php

namespace App\Providers;

use App\Descriptors\ColorDescriptor;
use Illuminate\Support\ServiceProvider;
use League\ColorExtractor\ColorExtractor;

class ColorDescriptorProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton(ColorDescriptor::class, function ($app) {
            $extractor = new ColorExtractor;
            return new ColorDescriptor($extractor, config('colordescriptor.colorCount'));
        });
    }
}
