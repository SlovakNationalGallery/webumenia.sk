<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class ElasticTranslatableServiceProvider extends ServiceProvider
{

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('ElasticTranslatableService', function ($app) {
            $index = config('bouncy.index');
            return new \App\Services\ElasticTranslatableService($index);
        });
    }

}
