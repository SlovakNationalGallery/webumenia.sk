<?php

namespace Tests\Elasticsearch\Repositories;

use App\Elasticsearch\Repositories\TranslatableRepository;
use Elasticsearch\ClientBuilder;
use Elasticsearch\Common\Exceptions\Missing404Exception;
use Illuminate\Foundation\Testing\RefreshDatabase;

abstract class TestCase extends \Tests\TestCase
{
    use RefreshDatabase;

    /** @var TranslatableRepository */
    protected $repository;

    /** @var string */
    protected $repositoryClass;

    protected function setUp(): void
    {
        parent::setUp();

        $config = config('elasticsearch.client');
        $client = ClientBuilder::fromConfig($config);
        $this->repository = new $this->repositoryClass(['sk'], $client);
        try {
            $this->repository->deleteIndex();
        } catch (Missing404Exception $e) {}
        $this->repository->createIndex();
        $this->repository->createMapping();
    }
}
