<?php

namespace Tests\Views\Frontend;

use App\Elasticsearch\Repositories\ItemRepository;
use App\SearchResult;
use Tests\TestCase;

class CatalogViewTest extends TestCase
{
    public function testGetIndex()
    {
        $response = $this->get('/katalog');

        $response->assertStatus(200);
        $response->assertViewIs('frontend.catalog-new.index');
        $response->assertViewHas('title');
    }

    public function testGetSuggestions()
    {
        $itemRepositoryMock = $this->createMock(ItemRepository::class);
        $itemRepositoryMock->expects($this->once())
            ->method('getSuggestions')
            ->willReturn(new SearchResult(collect(), 0));
        $this->app->instance(ItemRepository::class, $itemRepositoryMock);

        $response = $this->get('/katalog/suggestions?search=test');
        $response->assertStatus(200);
    }

    public function testGetSuggestionsMissingParameter()
    {
        $response = $this->get('/katalog/suggestions');
        $response->assertStatus(200);
    }

    public function testGetRandom()
    {
        $itemRepositoryMock = $this->createMock(ItemRepository::class);
        $itemRepositoryMock->expects($this->once())
            ->method('getRandom')
            ->willReturn(new SearchResult(collect(), 0));
        $this->app->instance(ItemRepository::class, $itemRepositoryMock);

        $response = $this->get('/katalog/random');
        $response->assertStatus(200);
    }

    // test the legacy catalog - should be removed after no longer needed/supported
    public function testOldCatalog()
    {
        $response = $this->get('/katalog-old');
        $response->assertStatus(200);
    }
}
