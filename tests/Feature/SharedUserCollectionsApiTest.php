<?php

namespace Tests\Feature;

use App\Item;
use App\SharedUserCollection;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class SharedUserCollectionsApiTest extends TestCase
{
    use DatabaseTransactions;

    public function testShow()
    {
        $items = factory(Item::class, 2)->create();
        $collection = factory(SharedUserCollection::class)->create([
            'name' => 'name',
            'items' => $items->map->only('id')
        ]);

        $this
            ->get(route('api.shared-user-collections.show', $collection->public_id))
            ->assertExactJson([
                'name' => 'name',
                'items_count' => 2,
            ]);
    }
}
