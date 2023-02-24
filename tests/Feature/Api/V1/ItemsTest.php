<?php

namespace Tests\Feature\Api\V1;

use App\Elasticsearch\Repositories\ItemRepository;
use App\Item;
use Tests\RecreateSearchIndex;
use Tests\TestCase;

class ItemsTest extends TestCase
{
    use RecreateSearchIndex;

    public function setUp(): void
    {
        parent::setUp();

        Item::factory()->create(['topic' => 'spring']);
        Item::factory()->create(['topic' => 'summer']);
        Item::factory()->create(['topic' => 'autumn']);

        // Ensure the index is up-to-date
        app(ItemRepository::class)->refreshIndex();
    }

    public function test_no_filters_applied()
    {
        $url = route('api.v1.items.index');

        $this->getJson($url)->assertJson([
            'total' => 3,
        ]);
    }

    public function test_single_term_filtering()
    {
        $url = route('api.v1.items.index', [
            'filter[topic]' => 'spring',
        ]);

        $this->getJson($url)->assertJson([
            'total' => 1,
        ]);
    }

    public function test_multi_terms_filtering()
    {
        $url = route('api.v1.items.index', [
            'filter[topic]' => ['spring', 'summer'],
        ]);

        $this->getJson($url)->assertJson([
            'total' => 2,
        ]);
    }
}