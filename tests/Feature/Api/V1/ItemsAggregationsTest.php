<?php

namespace Tests\Feature\Api\V1;

use App\Elasticsearch\Repositories\ItemRepository;
use App\Item;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\RecreateSearchIndex;
use Tests\TestCase;

class ItemsAggregationsTest extends TestCase
{
    use RefreshDatabase;
    use RecreateSearchIndex;

    private $initialized = false;

    public function setUp(): void
    {
        parent::setUp();

        if ($this->initialized) {
            return;
        }

        Item::factory()->createMany([
            [
                'author' => 'Galanda, Mikuláš',
                'topic' => 'spring',
                'date_earliest' => 1000,
            ],
            [
                'author' => 'Wouwerman, Philips',
                'topic' => 'summer',
                'date_earliest' => 2000,
            ],
        ]);

        app(ItemRepository::class)->refreshIndex();
        $this->initialized = true;
    }

    private function getAggregations(array $params = [])
    {
        $defaultParams = ['size' => 10];
        $url = route('api.v1.items.aggregations', array_merge($defaultParams, $params));
        return $this->getJson($url);
    }

    public function test_shows_nothing_by_default()
    {
        $this->getAggregations()->assertExactJson([]);
    }

    public function test_shows_terms()
    {
        $this->getAggregations(['terms' => ['my_author' => 'author']])->assertExactJson([
            'my_author' => [
                ['value' => 'Galanda, Mikuláš', 'count' => 1],
                ['value' => 'Wouwerman, Philips', 'count' => 1],
            ],
        ]);
    }

    public function test_filters_results()
    {
        $this->getAggregations([
            'filter' => ['topic' => ['summer']],
            'terms' => ['author' => 'author'],
        ])->assertExactJson([
            'author' => [['value' => 'Wouwerman, Philips', 'count' => 1]],
        ]);
    }
    public function test_filtered_faced_does_not_affect_itself()
    {
        $this->getAggregations([
            'filter' => ['topic' => ['summer']],
            'terms' => ['topic' => 'topic'],
        ])->assertExactJson([
            'topic' => [['value' => 'spring', 'count' => 1], ['value' => 'summer', 'count' => 1]],
        ]);
    }

    public function test_gets_min_and_max()
    {
        $this->getAggregations([
            'min' => ['date_earliest_min' => 'date_earliest'],
            'max' => ['date_earliest_max' => 'date_earliest'],
        ])->assertExactJson([
            'date_earliest_min' => 1000,
            'date_earliest_max' => 2000,
        ]);
    }

    public function test_size_limits_number_of_buckets()
    {
        $this->assertCount(
            1,
            $this->getAggregations([
                'size' => null, // reset to default
                'terms' => ['topic' => 'topic'],
            ])['topic']
        );

        $this->assertCount(
            2,
            $this->getAggregations([
                'terms' => ['topic' => 'topic'],
                'size' => 2,
            ])['topic']
        );
    }

    public function test_selected_facet_value_is_always_present()
    {
        $this->getAggregations([
            'filter' => ['topic' => ['spring'], 'author' => ['Wouwerman, Philips']],
            'terms' => ['topic' => 'topic', 'author' => 'author'],
        ])
            ->assertJsonFragment(['value' => 'spring', 'count' => 0])
            ->assertJsonFragment(['value' => 'Wouwerman, Philips', 'count' => 0]);
    }
}
