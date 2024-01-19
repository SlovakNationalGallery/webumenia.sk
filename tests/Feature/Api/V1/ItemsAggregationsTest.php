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

        Item::factory()
            ->webumeniaFrontend()
            ->createMany([
                [
                    'author' => 'Galanda, Mikuláš',
                    'topic' => 'spring',
                    'date_earliest' => 1000,
                    'date_latest' => 1000,
                ],
                [
                    'author' => 'Wouwerman, Philips',
                    'topic' => 'summer',
                    'date_earliest' => 2000,
                    'date_latest' => 2000,
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

    public function test_filtered_date_field_does_not_affect_other_date_fields()
    {
        $this->getAggregations([
            'filter' => ['date_earliest' => ['gte' => 2000]],
            'terms' => ['date_latest' => 'date_latest'],
        ])->assertExactJson([
            'date_latest' => [['value' => 1000, 'count' => 1], ['value' => 2000, 'count' => 1]],
        ]);

        $this->getAggregations([
            'filter' => ['date_latest' => ['gte' => 2000]],
            'terms' => ['date_earliest' => 'date_earliest'],
        ])->assertExactJson([
            'date_earliest' => [['value' => 1000, 'count' => 1], ['value' => 2000, 'count' => 1]],
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
}
