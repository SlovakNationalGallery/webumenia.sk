<?php

namespace Tests\Views\Frontend;

use App\Elasticsearch\Repositories\ItemRepository;
use App\Item;
use App\ItemImage;
use App\SearchResult;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class WebRoutesViewTest extends TestCase
{
    use RefreshDatabase;

    public function testGetIntro()
    {
        $response = $this->get('/');
        $response->assertStatus(200);
    }

    public function testGetSlideClicked()
    {
        $response = $this->get('/slideClicked');
        $response->assertStatus(200);
    }

    public function testGetOrder()
    {
        $response = $this->get('/objednavka');
        $response->assertStatus(200);
    }

    public function testPostOrder()
    {
        $response = $this->post('/objednavka');
        $response->assertRedirect('/');
    }

    public function testGetThankYou()
    {
        $response = $this->get('/dakujeme');
        $response->assertStatus(200);
    }

    public function testGetOrderItem()
    {
        $item = factory(Item::class)->create(['gallery' => 'Slovenská národná galéria, SNG']);
        $response = $this->get(sprintf('/dielo/%s/objednat', $item->id));
        $response->assertRedirect(sprintf('/dielo/%s', $item->id));
    }

    public function testGetUnorderItem()
    {
        $item = factory(Item::class)->create();
        $response = $this->get(sprintf('/dielo/%s/odstranit', $item->id));
        $response->assertRedirect('/');
    }

    public function testGetDownloadItem()
    {
        $this->markTestSkipped();

        $item = factory(Item::class)->create([
            'gallery' => 'Slovenská národná galéria, SNG',
            'author' => 'neznámy',
        ]);
        $image = factory(ItemImage::class)->make([
            'iipimg_url' => true
        ]);
        $item->images()->save($image);
        $response = $this->get(sprintf('/dielo/%s/stiahnut', $item->id));
        $response->assertStatus(200);
    }

    public function testGetItemDetail()
    {
        $itemRepositoryMock = $this->createMock(ItemRepository::class);
        $itemRepositoryMock->expects($this->once())
            ->method('getSimilar')
            ->willReturn(new SearchResult(collect(), 0));
        $this->app->instance(ItemRepository::class, $itemRepositoryMock);

        $item = factory(Item::class)->create();
        $response = $this->get(sprintf('/dielo/%s', $item->id));
        $response->assertStatus(200);
    }

    public function testGetColorRelatedItems()
    {
        $this->markTestSkipped();

        $itemRepositoryMock = $this->createMock(ItemRepository::class);
        $itemRepositoryMock->expects($this->once())
            ->method('getSimilarByColor')
            ->willReturn(new SearchResult(collect(), 0));
        $this->app->instance(ItemRepository::class, $itemRepositoryMock);

        $item = factory(Item::class)->create();
        $response = $this->get(sprintf('/dielo/%s/colorrelated', $item->id));
        $response->assertStatus(200);
    }

    public function testGetInfo()
    {
        $itemRepositoryMock = $this->createMock(ItemRepository::class);
        $itemRepositoryMock->expects($this->once())
            ->method('getRandom')
            ->willReturn(new SearchResult(collect(), 0));
        $this->app->instance(ItemRepository::class, $itemRepositoryMock);

        $response = $this->get('/informacie');
        $response->assertStatus(200);
    }

    public function testGetReproductions()
    {
        $itemRepositoryMock = $this->createMock(ItemRepository::class);
        $itemRepositoryMock->expects($this->any())
            ->method('getRandom')
            ->willReturn(new SearchResult(collect(), 0));
        $this->app->instance(ItemRepository::class, $itemRepositoryMock);

        $response = $this->get('/reprodukcie');
        $response->assertStatus(200);
    }
}
