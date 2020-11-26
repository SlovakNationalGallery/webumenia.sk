<?php

namespace Tests;

use App\Elasticsearch\Repositories\AuthorityRepository;
use App\Elasticsearch\Repositories\ItemRepository;
use Elasticsearch\Common\Exceptions\Missing404Exception;

trait RefreshSearchIndex
{
    /**
     * Define hooks to migrate the database before and after each test.
     *
     * @return void
     */
    public function refreshSearchIndex()
    {
        $authorityRepository = $this->app->make(AuthorityRepository::class);
        $itemRepository = $this->app->make(ItemRepository::class);
        $locale = \App::getLocale();

        collect([$authorityRepository, $itemRepository])->each(function ($repository) use ($locale) {

            // Drop previous index if it exists
            try {
                $repository->deleteIndex($locale);
            } catch (Missing404Exception $e) {}

            $repository->createIndex($locale);
            $repository->createMapping($locale);
        });
    }
}
