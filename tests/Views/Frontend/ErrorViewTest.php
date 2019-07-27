<?php

namespace Tests\Views\Frontend;

use App\Elasticsearch\Repositories\ItemRepository;
use App\SearchResult;

class ErrorViewTest extends FrontendViewTestCase
{
    public function test404()
    {
        $itemRepositoryMock = $this->createMock(ItemRepository::class);
        $itemRepositoryMock->expects($this->any())
            ->method('getRandom')
            ->willReturn(new SearchResult(collect(), 0));
        $this->app->instance(ItemRepository::class, $itemRepositoryMock);

        $response = $this->get('/404');
        $response->assertStatus(404);
    }
}