<?php

namespace Tests\Feature\Api\V1;

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
            ->webumeniaFrontend()
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
        Item::factory()
            ->webumeniaFrontend()
            ->create(['topic' => 'spring']);
        Item::factory()
            ->webumeniaFrontend()
            ->create(['topic' => 'summer']);
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
        Item::factory()
            ->webumeniaFrontend()
            ->create(['topic' => 'spring']);
        Item::factory()
            ->webumeniaFrontend()
            ->create(['topic' => 'summer']);
        Item::factory()
            ->webumeniaFrontend()
            ->create(['topic' => 'autumn']);
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
        Item::factory()
            ->webumeniaFrontend()
            ->create(['has_image' => true]);
        Item::factory()
            ->webumeniaFrontend()
            ->create(['has_image' => false]);
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
        Item::factory()
            ->webumeniaFrontend()
            ->create(['colors' => ['#ff0000' => 1]]);
        Item::factory()
            ->webumeniaFrontend()
            ->create(['colors' => ['#fe0000' => 1]]);
        $different = Item::factory()
            ->webumeniaFrontend()
            ->create(['colors' => ['#0000ff' => 1]]);

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

    public function test_sorting()
    {
        Item::factory()
            ->webumeniaFrontend()
            ->create(['title' => 'zebra']);
        Item::factory()
            ->webumeniaFrontend()
            ->create(['title' => 'aardvark']);
        Item::factory()
            ->webumeniaFrontend()
            ->create(['title' => 'cat']);
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
            ->webumeniaFrontend()
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
        Item::factory()
            ->webumeniaFrontend()
            ->create(['author' => 'Věšín, Jaroslav']);
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
}
