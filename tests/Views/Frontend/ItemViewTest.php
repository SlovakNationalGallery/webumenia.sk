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
        $publishedCollection = factory(Collection::class)->create([
            'is_published' => true,
        ]);
        $unpublishedCollection = factory(Collection::class)->create([
            'is_published' => false,
        ]);

        $publishedCollection->items()->save($item);
        $unpublishedCollection->items()->save($item);

        $this->get("/dielo/{$item->id}")
            ->assertSeeText($publishedCollection->name)
            ->assertDontSeeText($unpublishedCollection->name);
    }
}
