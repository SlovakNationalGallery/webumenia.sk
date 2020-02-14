<?php

namespace Tests\Forms;

use App\Elasticsearch\Repositories\AuthorityRepository;
use App\Filter\AuthoritySearchRequest;
use App\SearchResult;
use Mockery\MockInterface;
use Tests\TestCase;

class AuthorityFilterFormTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
        $this->spy(AuthorityRepository::class, function (MockInterface $repositoryMock) {
            $repositoryMock->shouldReceive('getMinimum')->andReturn(1990);
            $repositoryMock->shouldReceive('getMaximum')->andReturn(2010);
            $repositoryMock->shouldReceive('listValues')->andReturn(collect());
            $repositoryMock->shouldReceive('search')->andReturn(new SearchResult(collect(), 0));
        });
    }

    public function testYearsMatchRequest()
    {
        $response = $this->get('/autori?years-range=1995,2005');
        $response->assertStatus(200);

        /** @var AuthoritySearchRequest $searchRequest */
        $searchRequest = $response->original->getData()['searchRequest'];
        $this->assertEquals(1995, $searchRequest->getYears()->getFrom());
        $this->assertEquals(2005, $searchRequest->getYears()->getTo());
    }

    public function testYearsInvalidRequest()
    {
        $response = $this->get('/autori?years-range=invalid');
        $response->assertStatus(200);

        /** @var AuthoritySearchRequest $searchRequest */
        $searchRequest = $response->original->getData()['searchRequest'];
        $this->assertEquals(null, $searchRequest->getYears());
    }

    public function testYearsArrayRequest()
    {
        $response = $this->get('/autori?years-range[]=1995,2005');
        $response->assertStatus(200);

        /** @var AuthoritySearchRequest $searchRequest */
        $searchRequest = $response->original->getData()['searchRequest'];
        $this->assertEquals(null, $searchRequest->getYears());
    }

    public function testYearsExceedLimit()
    {
        $response = $this->get('/autori?years-range=2000,3000');
        $response->assertStatus(200);

        /** @var AuthoritySearchRequest $searchRequest */
        $searchRequest = $response->original->getData()['searchRequest'];
        $this->assertEquals(2000, $searchRequest->getYears()->getFrom());
        $this->assertEquals(null, $searchRequest->getYears()->getTo());
    }
}