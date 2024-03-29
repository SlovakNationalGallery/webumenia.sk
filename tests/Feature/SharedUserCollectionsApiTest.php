<?php

namespace Tests\Feature;

use App\Item;
use App\SharedUserCollection;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SharedUserCollectionsApiTest extends TestCase
{
    use RefreshDatabase;

    public function testShow()
    {
        $items = Item::factory()
            ->count(2)
            ->create();
        $collection = SharedUserCollection::factory()->create([
            'name' => 'name',
            'author' => 'author',
            'items' => $items->map->only('id'),
        ]);

        $this->get(
            route('api.shared-user-collections.show', $collection->public_id)
        )->assertExactJson([
            'name' => 'name',
            'author' => 'author',
            'items_count' => 2,
            'created_at' => $collection->created_at->toISOString(),
        ]);
    }
}
