<?php

namespace Tests\Views\Frontend;

use App\Article;
use App\Elasticsearch\Repositories\ItemRepository;
use App\Item;
use App\ItemImage;
use App\SearchResult;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PatternLibViewTest extends TestCase
{
    use RefreshDatabase;

    public function testGetIndex()
    {
        Article::factory()->create();
        $item = factory(Item::class)->create();
        $image = factory(ItemImage::class)->make(['iipimg_url' => true]);
        $item->images()->save($image);

        $itemRepositoryMock = $this->createMock(ItemRepository::class);
        $itemRepositoryMock
            ->expects($this->exactly(2))
            ->method('getRandom')
            ->willReturn(new SearchResult(collect([$item]), 0));
        $this->app->instance(ItemRepository::class, $itemRepositoryMock);

        $response = $this->get('/patternlib');
        $response->assertSeeText('Pattern Library');
        $response->assertStatus(200);
    }
}
