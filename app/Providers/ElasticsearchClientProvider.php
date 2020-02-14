<?php

namespace App\Providers;

use Elasticsearch\Client;
use Elasticsearch\ClientBuilder;
use Illuminate\Support\ServiceProvider;

class ElasticsearchClientProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton(Client::class, function() {
            return ClientBuilder::fromConfig(config('elasticsearch.client'));
        });
    }

}