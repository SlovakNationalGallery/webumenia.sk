<?php

namespace Tests\Elasticsearch\Repositories;

use App\Authority;
use App\Elasticsearch\Repositories\AuthorityRepository;
use App\Filter\AuthoritySearchRequest;

class AuthorityRepositoryTest extends TestCase
{
    /** @var AuthorityRepository */
    protected $repository;

    protected $repositoryClass = AuthorityRepository::class;

    public function testCount()
    {
        factory(Authority::class, 5)
            ->make()
            ->each(function (Authority $authority) {
                $this->repository->index($authority);
            });
        $this->repository->refreshIndex();

        $count = $this->repository->count();
        $this->assertEquals(5, $count);
    }

    public function testSearch()
    {
        factory(Authority::class, 5)
            ->make()
            ->each(function (Authority $authority) {
                $this->repository->index($authority);
            });
        $this->repository->refreshIndex();

        $response = $this->repository->search(new AuthoritySearchRequest());
        $this->assertEquals(5, $response->getTotal());
    }
}
