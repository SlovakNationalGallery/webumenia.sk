<?php

namespace App\Http\Controllers\Api\V1;

use App\Elasticsearch\Repositories\ItemRepository;
use App\Http\Controllers\Controller;
use App\Item;
use ElasticAdapter\Search\Aggregation;
use ElasticAdapter\Search\Bucket;
use ElasticScoutDriverPlus\Exceptions\QueryBuilderException;
use ElasticScoutDriverPlus\Support\Query;
use Illuminate\Http\Request;
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
        'has_test',
        'is_free',
        'additionals.category.keyword',
        'additionals.frontend.keyword',
        'additionals.set.keyword',
        'additionals.location.keyword',
    ];

    private $rangeables = [
        'date_earliest',
        'date_latest',
        'additionals.order',
    ];

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

        $searchRequest = Item::searchQuery($query);

        collect($sort)
            ->only($this->sortables)
            ->intersect(['asc', 'desc'])
            ->each(function ($direction, $field) use ($searchRequest) {
                $searchRequest->sort($field, $direction);
            });

        return $searchRequest->paginate($size)->onlyDocuments();
    }

    public function aggregations(Request $request)
    {
        $filter = (array) $request->get('filter');
        $terms = (array) $request->get('terms');
        $min = (array) $request->get('min');
        $max = (array) $request->get('max');
        $size = (int) $request->get('size', 1);
        $q = (string) $request->get('q');

        try {
            $query = $this->createQueryBuilder($q, $filter)->buildQuery();
        } catch (QueryBuilderException $e) {
            $query = ['match_all' => new \stdClass()];
        }

        $searchRequest = Item::searchQuery($query);

        foreach ($terms as $agg => $field) {
            $searchRequest->aggregate($agg, [
                'terms' => [
                    'field' => $field,
                    'size' => $size,
                ],
            ]);
        }

        foreach ($min as $agg => $field) {
            $searchRequest->aggregate($agg, [
                'min' => [
                    'field' => $field,
                ],
            ]);
        }

        foreach ($max as $agg => $field) {
            $searchRequest->aggregate($agg, [
                'max' => [
                    'field' => $field,
                ],
            ]);
        }

        $searchResult = $searchRequest->execute();
        return response()->json(
            $searchResult->aggregations()->map(function (Aggregation $aggregation) {
                $raw = $aggregation->raw();
                if (array_key_exists('value', $raw)) {
                    return $raw['value'];
                }

                return $aggregation->buckets()->map(function (Bucket $bucket) {
                    return [
                        'value' => $bucket->key(),
                        'count' => $bucket->docCount(),
                    ];
                });
            })
        );
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
