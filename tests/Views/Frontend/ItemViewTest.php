<?php

namespace Tests\Views\Frontend;

use App\Collection;
use App\Item;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ItemViewTest extends TestCase
{
    use RefreshDatabase;

    public function testGetItemDetail()
    {
        $item = factory(Item::class)->create();
        $response = $this->get("/dielo/{$item->id}");
        $response->assertStatus(200);
    }

    public function testListsAssociatedPublishedCollections()
    {
        $item = factory(Item::class)->create();
        $publishedCollection = Collection::factory()->create([
            'is_published' => true,
            'name' => 'a published collection',
        ]);
        $unpublishedCollection = Collection::factory()->create([
            'is_published' => false,
            'name' => 'an unpublished collection',
        ]);

        $publishedCollection->items()->save($item);
        $unpublishedCollection->items()->save($item);

        $this->get("/dielo/{$item->id}")
            ->assertSeeText($publishedCollection->name)
            ->assertDontSeeText($unpublishedCollection->name);
    }
}
