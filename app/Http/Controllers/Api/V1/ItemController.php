<?php

namespace App\Http\Controllers\Api\V1;

use App\Elasticsearch\Repositories\ItemRepository;
use App\Http\Controllers\Controller;
use App\Item;
use ElasticScoutDriverPlus\Exceptions\QueryBuilderException;
use ElasticScoutDriverPlus\Support\Query;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Primal\Color\Parser as ColorParser;

class ItemController extends Controller
{
    private $filterables = [
        'author',
        'topic',
        'work_type',
        'medium',
        'technique',
        'gallery',
        'has_image',
        'has_iip',
        'has_text',
        'is_free',
        'tag',
        'credit',
        'related_work',
        'is_for_reproduction',
        'contributor',
        'additionals.category.keyword',
        'additionals.frontend.keyword',
        'additionals.set.keyword',
        'additionals.location.keyword',
    ];

    private $rangeables = ['date_earliest', 'date_latest', 'additionals.order'];

    private $sortables = [
        'date_earliest',
        'date_latest',
        'updated_at',
        'created_at',
        'title',
        'author',
        'view_count',
        'additionals.order',
    ];

    public function index(Request $request)
    {
        $filter = (array) $request->get('filter');
        $sort = (array) $request->get('sort');
        $size = (int) $request->get('size', 1);
        $q = (string) $request->get('q');

        try {
            $query = $this->createQueryBuilder($q, $filter)->buildQuery();
        } catch (QueryBuilderException $e) {
            $query = ['match_all' => new \stdClass()];
        }

        if (array_key_exists('random', $sort)) {
            $query = ItemRepository::buildRandomSortQuery($query, $request->get('page', 1) == 1);
        }

        $searchRequest = Item::searchQuery($query);

        $sorts = collect($sort)
            ->only($this->sortables)
            ->filter(fn($direction) => in_array($direction, ['asc', 'desc']));

        $sorts->each(function ($direction, $field) use ($searchRequest) {
            $searchRequest->sort($field, $direction);
        });

        if ($sorts->isEmpty()) {
            $searchRequest->sortRaw(ItemRepository::buildDefaultSortQuery());
        }

        $response = $searchRequest
            ->paginate($size)
            ->onlyDocuments()
            ->toArray();

        return [
            ...$response,
            'data' => collect($response['data'])->map(
                fn($document) => [
                    ...$document,
                    'content' => [
                        ...$document['content'],
                        'authors_formatted' => collect($document['content']['author'])->map(
                            fn($author) => formatName($author)
                        ),
                    ],
                ]
            ),
        ];
    }

    public function aggregations(Request $request)
    {
        $filter = (array) $request->get('filter');
        $terms = (array) $request->get('terms');
        $min = (array) $request->get('min');
        $max = (array) $request->get('max');
        $size = (int) $request->get('size', 1);
        $q = (string) $request->get('q');

        $aggregationsQuery = [];

        foreach ($terms as $aggregationName => $field) {
            $aggregationsQuery[$aggregationName]['aggregations']['filtered']['terms'] = [
                'field' => $field,
                'size' => $size,
            ];
        }

        foreach ($min as $aggregationName => $field) {
            $aggregationsQuery[$aggregationName]['aggregations']['filtered']['min'] = [
                'field' => $field,
            ];
        }

        foreach ($max as $aggregationName => $field) {
            $aggregationsQuery[$aggregationName]['aggregations']['filtered']['max'] = [
                'field' => $field,
            ];
        }

        // Add filter terms to each aggregation
        // Based on https://madewithlove.com/blog/faceted-search-using-elasticsearch/
        foreach (array_keys($aggregationsQuery) as $term) {
            $termFilter = Arr::except($filter, $term);

            // Exclude date_latest for date_earliest and vice versa
            if ($term === 'date_earliest') {
                $termFilter = Arr::except($termFilter, 'date_latest');
            }
            if ($term === 'date_latest') {
                $termFilter = Arr::except($termFilter, 'date_earliest');
            }

            $aggregationsQuery[$term]['filter'] = $this->createQueryBuilder(
                $q,
                $termFilter
            )->buildQuery();
        }

        $searchRequest = Item::searchQuery()
            ->size(0) // No documents are needed, only aggregations
            ->aggregateRaw([
                'all_items' => [
                    'global' => (object) [],
                    'aggregations' => (object) $aggregationsQuery,
                ],
            ]);

        return collect(Arr::get($searchRequest->execute()->raw(), 'aggregations.all_items'))
            ->only(array_keys($aggregationsQuery))
            ->map(function (array $aggregation) {
                if (Arr::has($aggregation, 'filtered.value')) {
                    return Arr::get($aggregation, 'filtered.value');
                }

                return collect(Arr::get($aggregation, 'filtered.buckets'))->map(
                    fn(array $bucket) => [
                        'value' => $bucket['key'],
                        'count' => $bucket['doc_count'],
                    ]
                );
            });
    }

    public function detail(Request $request, $id)
    {
        $filter = (array) $request->get('filter');
        $q = (string) $request->get('q');

        try {
            $query = $this->createQueryBuilder($q, $filter)
                ->must(Query::ids()->values([$id]))
                ->buildQuery();
        } catch (QueryBuilderException $e) {
            $query = Query::ids()->values([$id]);
        }

        $items = Item::searchQuery($query)->execute();

        if (!$items->total()) {
            return response()->json(['message' => 'Not found'], 404);
        }

        return $items->documents()->first();
    }

    protected function createQueryBuilder($q, $filter)
    {
        if (empty($q) && empty($filter)) {
            return Query::matchAll();
        }

        $builder = Query::bool();

        if ($q) {
            $query = Query::multiMatch()
                ->fields(['title.*', 'description.*'])
                ->query($q);
            $builder->must($query);
        }

        foreach ($filter as $field => $value) {
            if ($field === 'color') {
                $color = ColorParser::parse($value);
                $colorQuery = Query::nested()
                    ->path('hsl')
                    ->query(ItemRepository::buildBoolQueryForColor($color));

                $builder->filter($colorQuery);
                continue;
            }
            if (is_string($value) && in_array($field, $this->filterables, true)) {
                if ($value === 'false') {
                    $value = false;
                }
                if ($value === 'true') {
                    $value = true;
                }
                $builder->filter(['term' => [$field => $value]]);
                continue;
            }
            if (is_array($value) && in_array($field, $this->filterables, true)) {
                $builder->filter(['terms' => [$field => $value]]);
                continue;
            }
            if (is_array($value) && in_array($field, $this->rangeables, true)) {
                $range = collect($value)
                    ->only(['lt', 'lte', 'gt', 'gte'])
                    ->transform(function ($value) {
                        return (string) $value;
                    })
                    ->all();
                $builder->filter(['range' => [$field => $range]]);
                continue;
            }
        }

        return $builder;
    }
}
