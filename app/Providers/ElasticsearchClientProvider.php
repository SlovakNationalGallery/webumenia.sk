<?php

namespace App\Providers;

use Elasticsearch\Client;
use Illuminate\Support\ServiceProvider;

class ElasticsearchClientProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton(Client::class, function() {
            return new Client(config('elasticsearch'));
        });
    }

}