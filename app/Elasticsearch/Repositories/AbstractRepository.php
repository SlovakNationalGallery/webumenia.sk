<?php

namespace App\Elasticsearch\Repositories;

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

    protected function createSearchResult(array $response): SearchResult
    {
        return new SearchResult(
            $this->elasticToModels($response),
            $response['hits']['total']['value']
        );
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
        $model->forceFill($hit['_source']);
        return $model;
    }

    protected function createBucketCollection(array $response, string $attribute): Collection
    {
        $choices = collect();
        foreach ($response['aggregations'][$attribute]['buckets'] as $bucket) {
            $label = sprintf('%s (%d)', $bucket['key'], $bucket['doc_count']);
            $choices[$label] = $bucket['key'];
        }

        return $choices;
    }
}