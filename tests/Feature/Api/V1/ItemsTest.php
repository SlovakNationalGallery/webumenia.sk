<?php

namespace Tests\Feature\Api\V1;

use App\Authority;
use App\Elasticsearch\Repositories\ItemRepository;
use App\Item;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\RecreateSearchIndex;
use Tests\TestCase;

class ItemsTest extends TestCase
{
    use RefreshDatabase;
    use RecreateSearchIndex;

    public function test_no_filters_applied()
    {
        Item::factory()
            ->count(3)
            ->create();
        app(ItemRepository::class)->refreshIndex();

        $url = route('api.v1.items.index');

        $this->getJson($url)->assertJson([
            'total' => 3,
        ]);
    }

    public function test_single_term_filtering()
    {
        Item::factory()->create(['topic' => 'spring']);
        Item::factory()->create(['topic' => 'summer']);
        app(ItemRepository::class)->refreshIndex();

        $url = route('api.v1.items.index', [
            'filter[topic]' => 'spring',
        ]);

        $this->getJson($url)->assertJson([
            'total' => 1,
        ]);
    }

    public function test_multi_terms_filtering()
    {
        Item::factory()->create(['topic' => 'spring']);
        Item::factory()->create(['topic' => 'summer']);
        Item::factory()->create(['topic' => 'autumn']);
        app(ItemRepository::class)->refreshIndex();

        $url = route('api.v1.items.index', [
            'filter[topic]' => ['spring', 'summer'],
        ]);

        $this->getJson($url)->assertJson([
            'total' => 2,
        ]);
    }

    public function test_filtering_by_boolean_values()
    {
        Item::factory()->create(['has_image' => true]);
        Item::factory()->create(['has_image' => false]);
        app(ItemRepository::class)->refreshIndex();

        $url = route('api.v1.items.index', [
            'filter[has_image]' => 'true',
        ]);

        $this->getJson($url)->assertJson([
            'total' => 1,
        ]);
    }

    public function test_filtering_by_color()
    {
        Item::factory()->create(['colors' => ['#ff0000' => 1]]);
        Item::factory()->create(['colors' => ['#fe0000' => 1]]);
        $different = Item::factory()->create(['colors' => ['#0000ff' => 1]]);

        app(ItemRepository::class)->refreshIndex();

        $url = route('api.v1.items.index', [
            'size' => 10,
            'filter[color]' => 'ff0000',
        ]);

        $response = $this->getJson($url);

        $this->assertNotContains($different->id, collect($response['data'])->pluck('id'));

        $response->assertJson([
            'total' => 2,
        ]);
    }

    public function test_distinguishes_authors_with_same_name()
    {
        Item::factory()
            ->has(
                Authority::factory()->state([
                    'id' => 'same-name-1',
                    'name' => 'Věšín, Jaroslav',
                ])
            )
            ->create(['author' => 'Věšín, Jaroslav; Neznámy autor']);

        Item::factory()
            ->has(
                Authority::factory()->state([
                    'id' => 'same-name-2',
                    'name' => 'Věšín, Jaroslav',
                ])
            )
            ->create(['author' => 'Věšín, Jaroslav']);

        app(ItemRepository::class)->refreshIndex();

        $searchByName = route('api.v1.items.index', [
            'size' => 10,
            'filter[authors.name]' => 'Věšín, Jaroslav',
        ]);

        $this->getJson($searchByName)->assertJson([
            'total' => 2,
        ]);

        $searchById = route('api.v1.items.index', [
            'size' => 10,
            'filter[authors.authority.id]' => 'same-name-1',
        ]);

        $this->getJson($searchById)->assertJson([
            'total' => 1,
        ]);

        $searchByNameOrId = route('api.v1.items.index', [
            'size' => 10,
            'filter[authors.authority.id]' => 'same-name-2',
            'filter[authors.name]' => 'Neznámy autor',
        ]);

        $this->getJson($searchByNameOrId)->assertJson([
            'total' => 2,
        ]);
    }

    public function test_sorting()
    {
        Item::factory()->create(['title' => 'zebra']);
        Item::factory()->create(['title' => 'aardvark']);
        Item::factory()->create(['title' => 'cat']);
        app(ItemRepository::class)->refreshIndex();

        $url = route('api.v1.items.index', [
            'size' => 10,
            'sort[title]' => 'asc',
        ]);

        $response = $this->getJson($url);

        $this->assertEquals(
            collect(['aardvark', 'cat', 'zebra']),
            collect($response['data'])->pluck('content.title')
        );
    }

    public function test_random_sort()
    {
        Item::factory()
            ->count(5)
            ->create(['has_image' => true]); // Images are required for random sort
        app(ItemRepository::class)->refreshIndex();

        $url = route('api.v1.items.index', [
            'size' => 5,
            'sort[random]' => 'asc',
        ]);

        $response1 = $this->getJson($url);
        $response2 = $this->getJson($url);

        $this->assertNotSame(
            collect($response1['data'])->pluck('id'),
            collect($response2['data'])->pluck('id')
        );
    }

    public function test_formatted_authors()
    {
        Item::factory()->create(['author' => 'Věšín, Jaroslav']);
        app(ItemRepository::class)->refreshIndex();

        $url = route('api.v1.items.index', [
            'size' => 1,
        ]);

        $response = $this->getJson($url);

        $this->assertEquals(
            'Jaroslav Věšín',
            $response['data'][0]['content']['authors_formatted'][0]
        );
    }

    public function test_increment_view_count()
    {
        $item = Item::factory()->create(['view_count' => 0]);

        $this->postJson(route('api.v1.items.views', ['id' => $item->id]))->assertOk();

        $this->assertEquals(1, $item->fresh()->view_count);
    }

    public function test_suggestions()
    {
        $url = route('api.v1.items.suggestions', ['q' => 'test']);
        $this->get($url)->assertOk();
    }

    public function test_similar()
    {
        [$item, $similar] = Item::factory()
            ->count(2)
            ->create([
                'title' => fake()->word,
                'has_image' => true,
            ]);
        app(ItemRepository::class)->refreshIndex();

        $url = route('api.v1.items.similar', [
            'id' => $item->id,
            'size' => 10,
        ]);

        $this->get($url)
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.id', $similar->id);
    }

    public function test_authors_field()
    {
        $authorWithAuthority = Authority::factory()->create([
            'id' => 'authority-id',
            'name' => 'Věšín, Jaroslav',
        ]);
        Item::factory()
            ->create([
                'id' => 'item-id',
                'author' => 'Věšín, Jaroslav; Viedenský maliar z konca 18. storočia',
            ])
            ->authorities()
            ->save($authorWithAuthority);

        app(ItemRepository::class)->refreshIndex();

        $response = $this->getJson(
            route('api.v1.items.index', [
                'size' => 100,
            ])
        );

        $response->assertJson([
            'data' => [
                [
                    'id' => 'item-id',
                    'content' => [
                        'authors' => [
                            [
                                'name' => 'Věšín, Jaroslav',
                                'name_formatted' => 'Jaroslav Věšín',
                                'authority' => ['id' => 'authority-id'],
                            ],
                            [
                                'name' => 'Viedenský maliar z konca 18. storočia',
                                'name_formatted' => 'Viedenský maliar z konca 18. storočia',
                                'authority' => null,
                            ],
                        ],
                    ],
                ],
            ],
        ]);
    }

    public function test_show()
    {
        $item = Item::factory()->create([
            'work_type' => 'grafika, voľná',
            'object_type' => 'tlač, veľký formát',
        ]);
        app(ItemRepository::class)->refreshIndex();

        $url = route('api.v1.items.show', ['id' => $item->id]);

        $response = $this->getJson($url);
        $response->assertJson([
            'id' => $item->id,
            'content' => [
                'work_type_tree' => [
                    [
                        [
                            'name' => 'grafika',
                            'path' => 'grafika',
                        ],
                        [
                            'name' => 'voľná',
                            'path' => 'grafika/voľná',
                        ],
                    ],
                ],
                'object_type_tree' => [
                    [
                        [
                            'name' => 'tlač',
                            'path' => 'tlač',
                        ],
                        [
                            'name' => 'veľký formát',
                            'path' => 'tlač/veľký formát',
                        ],
                    ],
                ],
            ],
        ]);
    }

    public function test_search_by_id()
    {
        $item = Item::factory()->create();
        app(ItemRepository::class)->refreshIndex();

        $url = route('api.v1.items.index', [
            'q' => $item->id,
        ]);

        $this->getJson($url)->assertJson([
            'total' => 1,
            'data' => [
                [
                    'id' => $item->id,
                ],
            ],
        ]);
    }

    public function test_search_by_identifier()
    {
        $item = Item::factory()->create(['identifier' => 'G 3415']);
        app(ItemRepository::class)->refreshIndex();

        $url = route('api.v1.items.index', [
            'q' => 'G 3415',
        ]);

        $this->getJson($url)->assertJson([
            'total' => 1,
            'data' => [
                [
                    'id' => $item->id,
                    'content' => [
                        'identifier' => 'G 3415',
                    ],
                ],
            ],
        ]);
    }
}
