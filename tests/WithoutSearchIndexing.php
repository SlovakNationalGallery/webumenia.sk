<?php

namespace Tests;

use App\Elasticsearch\Repositories\AuthorityRepository;
use App\Elasticsearch\Repositories\ItemRepository;
use Elasticsearch\Client;

trait WithoutSearchIndexing
{
    public function disableModelSearchIndexing(): void
    {
        $this->mock(Client::class)->shouldIgnoreMissing();
        // forces re-instantiation of repositories with client mock
        $this->app->forgetInstance(ItemRepository::class);
        $this->app->forgetInstance(AuthorityRepository::class);
    }
}
