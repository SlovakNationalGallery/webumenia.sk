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
        Item::factory()->count(3)->create();
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

        $this->getJson($url)->assertJson([
            'total' => 1,
        ]);
    }
}
