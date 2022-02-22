<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Item;
use ElasticAdapter\Search\Aggregation;
use ElasticAdapter\Search\Bucket;
use ElasticScoutDriverPlus\Exceptions\QueryBuilderException;
use ElasticScoutDriverPlus\Support\Query;
use Illuminate\Http\Request;

class ItemController extends Controller
{
    public function index(Request $request)
    {
        $filter = (array)$request->get('filter');
        $sort = (array)$request->get('sort');
        $size = (int)$request->get('size', 1);
        $q = (string)$request->get('q');

        try {
            $builder = Query::bool();
            $this->searchQuery($q, $builder)
                ->filterQuery($filter, $builder);

            $query = $builder->buildQuery();
        } catch (QueryBuilderException $e) {
            $query = ['match_all' => new \stdClass];
        }

        $searchRequest = Item::searchQuery($query);

        collect($sort)
            ->only(Item::$sortables)
            ->intersect(['asc', 'desc'])
            ->each(function ($direction, $field) use ($searchRequest) {
                $searchRequest->sort($field, $direction);
            });

        $items = $searchRequest->paginate($size);
        $items->setCollection($items->models());
        $items->appends($request->query());

        return $items;
    }

    protected function searchQuery($query, $builder)
    {
        if (!$query) {
            return $this;
        }

        foreach (['title', 'description'] as $field) {
            $match = Query::match()
                ->field($field)
                ->query($query);
            $builder->should($match);
        }

        return $this;
    }

    protected function filterQuery($filter,  $builder)
    {
        foreach ($filter as $field => $value) {
            if (is_string($value) && in_array($field, Item::$filterables, true)) {
                $builder->filter(['term' => [$field => $value]]);
            } else if (is_array($value) && in_array($field, Item::$rangeables, true)) {
                $range = collect($value)
                    ->only(['lt', 'lte', 'gt', 'gte'])
                    ->transform(function ($value) {
                        return (string)$value;
                    })
                    ->all();
                $builder->filter(['range' => [$field => $range]]);
            } else {
                throw new \Exception;
            }
        }

        return $this;
    }

    public function aggregations(Request $request)
    {
        $filter = (array)$request->get('filter');
        $terms = (array)($request->get('terms'));
        $min = (array)$request->get('min');
        $max = (array)$request->get('max');
        $size = (int)$request->get('size', 1);
        $q = (string)$request->get('q');

        try {
            $builder = Query::bool();
            $this->searchQuery($q, $builder)
                ->filterQuery($filter, $builder);

            $query = $builder->buildQuery();
        } catch (QueryBuilderException $e) {
            $query = ['match_all' => new \stdClass];
        }

        $searchRequest = Item::searchQuery($query);

        foreach ($terms as $agg => $field) {
            $searchRequest->aggregate($agg, [
                'terms' => [
                    'field' => $field,
                    'size' => $size,
                ]
            ]);
        }

        foreach ($min as $agg => $field) {
            $searchRequest->aggregate($agg, [
                'min' => [
                    'field' => $field,
                ]
            ]);
        }

        foreach ($max as $agg => $field) {
            $searchRequest->aggregate($agg, [
                'max' => [
                    'field' => $field,
                ]
            ]);
        }

        $searchResult = $searchRequest->execute();
        return response()->json($searchResult->aggregations()->map(function (Aggregation $aggregation) {
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
        }));
    }

    public function detail(Request $request, $id)
    {
        $query = Query::ids()->values([(string)$id]);
        $items = Item::searchQuery($query)->execute();

        if (!$items->total()) {
            abort(404);
        }

        return $items->models()->first();
    }
}
