<?php

namespace Tests\Feature;

use App\Item;
use App\SharedUserCollection;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SharedUserCollectionsTest extends TestCase
{
    use RefreshDatabase;

    public function testCreation()
    {
        $item = Item::factory()->webumeniaFrontend()->create();

        $this->get(
            route('frontend.shared-user-collections.create', ['ids' => [$item->id]])
        )->assertSee($item->title);

        $response = $this->post(route('frontend.shared-user-collections.store'), [
            'items' => [
                [
                    'id' => $item->id,
                ],
            ],
            'name' => 'Some name',
            'description' => 'Some description',
        ]);

        $collection = SharedUserCollection::first();
        $this->assertNotEmpty($collection->update_token);

        $response->assertRedirect(
            route('frontend.shared-user-collections.edit', [
                'collection' => $collection,
                'token' => $collection->update_token,
            ])
        );
    }

    public function testUpdateTokenIsNecessaryForEditing()
    {
        $items = Item::factory()
            ->webumeniaFrontend()
            ->count(2)
            ->create();
        $collection = SharedUserCollection::factory()->create([
            'items' => $items->map->only('id'),
        ]);

        $this->get(
            route('frontend.shared-user-collections.edit', [
                'collection' => $collection,
                'token' => 'bad_token',
            ])
        )->assertRedirect(
            route('frontend.shared-user-collections.show', ['collection' => $collection])
        );

        $this->put(
            route('frontend.shared-user-collections.update', [
                'collection' => $collection,
                'token' => 'bad_token',
            ]),
            ['name' => 'new_name']
        )->assertForbidden();

        $this->patch(
            route('frontend.shared-user-collections.update', [
                'collection' => $collection,
                'token' => $collection->update_token,
            ]),
            [
                'name' => 'new_name',
                'items' => $collection->items->toArray(),
            ]
        );

        $this->assertEquals($collection->refresh()->name, 'new_name');
    }
}
