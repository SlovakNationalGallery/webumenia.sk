<?php

namespace App\Elasticsearch\Repositories;

use App\Filter\Contracts\Filter;
use App\Filter\Contracts\SearchRequest;
use App\SearchResult;
use Elasticsearch\Client;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

abstract class AbstractRepository
{
    protected $prefix;

    protected $index;

    protected $modelClass;

    protected $elasticsearch;

    public function __construct(Client $elasticsearch)
    {
        $this->elasticsearch = $elasticsearch;
        $this->prefix = config('elasticsearch.common.prefix');
    }

    public function buildBodyFromSearchRequest(SearchRequest $request): ?array
    {
        $body = [];
        $body = $this->addQuery($body, $this->buildQueryFromFilter($request));
        $body = $this->addSort($body, $request);
        $body = $this->addFrom($body, $request->getFrom());
        $body = $this->addSize($body, $request->getSize());
        return $body ?: null;
    }

    protected function createSearchResult(array $response): SearchResult
    {
        return new SearchResult(
            $this->elasticToModels($response),
            $response['hits']['total']['value']
        );
    }

    protected function createEmptySearchResult(): SearchResult
    {
        return new SearchResult(collect(), 0);
    }

    protected function elasticToModels(array $response): Collection
    {
        $models = collect();
        foreach ($response['hits']['hits'] as $hit) {
            $models->add($this->newFromElasticResults($hit));
        }

        return $models;
    }

    protected function newFromElasticResults(array $hit): Model
    {
        /** @var Model $model */
        $model = new $this->modelClass();
        $model->exists = true;
        $model->setRawAttributes($hit['_source'], true);
        return $model;
    }

    protected function createBucketCollection(array $response, string $attribute): Collection
    {
        $choices = collect();
        foreach ($response['aggregations'][$attribute]['buckets'] as $bucket) {
            $choices[] = [
                'value' => $bucket['key'],
                'count' => $bucket['doc_count'],
            ];
        }

        return $choices;
    }

    protected function addQuery(array $body, ?array $query): array
    {
        if ($query === null) {
            return $body;
        }

        $body['query'] = $query;
        return $body;
    }

    protected function addFrom(array $body, ?int $from): array
    {
        if ($from === null) {
            return $body;
        }

        $body['from'] = $from;
        return $body;
    }

    protected function addSize(array $body, ?int $size): array
    {
        if ($size === null) {
            return $body;
        }

        $body['size'] = $size;
        return $body;
    }

    protected function addFilterablesQuery(array $query, Filter $filter): array
    {
        foreach ($filter->getFilterables() as $filterable) {
            $value = $filter->get($filterable);
            if ($value !== null) {
                $query['bool']['filter'][]['term'][$filterable] = $value;
            }
        }

        return $query;
    }

    abstract public function buildQueryFromFilter(?Filter $filter): ?array;

    abstract protected function addSort(array $body, SearchRequest $request): array;
}
