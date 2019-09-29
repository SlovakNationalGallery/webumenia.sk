<?php

namespace Tests\Views\Frontend;

use App\Authority;
use App\Elasticsearch\Repositories\AuthorityRepository;
use App\Elasticsearch\Repositories\ItemRepository;
use App\SearchResult;

class AuthorViewTest extends FrontendViewTestCase
{
    public function testGetIndex()
    {
        $authorityRepositoryMock = $this->createMock(AuthorityRepository::class);
        $authorityRepositoryMock->expects($this->any())
            ->method('listValues')
            ->willReturn(collect());
        $authorityRepositoryMock->expects($this->once())
            ->method('search')
            ->willReturn(new SearchResult(collect(), 0));
        $this->app->instance(AuthorityRepository::class, $authorityRepositoryMock);

        $response = $this->get('/autori');
        $response->assertStatus(200);
    }

    public function testGetSuggestions()
    {
        $authorityRepositoryMock = $this->createMock(AuthorityRepository::class);
        $authorityRepositoryMock->expects($this->once())
            ->method('getSuggestions')
            ->willReturn(new SearchResult(collect(), 0));
        $this->app->instance(AuthorityRepository::class, $authorityRepositoryMock);

        $response = $this->get('/autori/suggestions?search=test');
        $response->assertStatus(200);
    }

    public function testGetSuggestionsMissingParameter()
    {
        $authorityRepositoryMock = $this->createMock(AuthorityRepository::class);
        $this->app->instance(AuthorityRepository::class, $authorityRepositoryMock);

        $response = $this->get('/autori/suggestions');
        $response->assertStatus(200);
    }

    public function testGetSuggestionsArrayParameter()
    {
        $authorityRepositoryMock = $this->createMock(AuthorityRepository::class);
        $this->app->instance(AuthorityRepository::class, $authorityRepositoryMock);

        $response = $this->get('/autori/suggestions?search[]=test');
        $response->assertStatus(200);
    }

    public function testGetDetail()
    {
        $itemRepositoryMock = $this->createMock(ItemRepository::class);
        $itemRepositoryMock->expects($this->once())
            ->method('getPreviewItems')
            ->willReturn(collect());
        $this->app->instance(ItemRepository::class, $itemRepositoryMock);

        $author = factory(Authority::class)->create();

        $response = $this->get(sprintf('/autor/%d', $author->id));
        $response->assertStatus(200);
    }
}