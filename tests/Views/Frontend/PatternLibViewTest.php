<?php

namespace Tests\Views\Frontend;

use App\Article;
use App\Elasticsearch\Repositories\ItemRepository;
use App\Item;
use App\ItemImage;
use App\SearchResult;

class PatternLibViewTest extends FrontendViewTestCase
{
    public function testGetIndex()
    {
        $itemRepositoryMock = $this->createMock(ItemRepository::class);
        $itemRepositoryMock->expects($this->once())
            ->method('getRandom')
            ->willReturn(new SearchResult(collect(), 0));
        $this->app->instance(ItemRepository::class, $itemRepositoryMock);

        factory(Article::class)->create();
        $item = factory(Item::class)->create();
        $image = factory(ItemImage::class)->make(['iipimg_url' => true]);
        $item->images()->save($image);

        $response = $this->get('/patternlib');
        $response->assertStatus(200);
    }
}