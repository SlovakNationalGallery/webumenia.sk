<?php

namespace App\Elasticsearch\Repositories;

use App\Contracts\IndexableModel;
use App\Filter\Contracts\Filter;
use App\Filter\Contracts\SearchRequest;
use App\SearchResult;
use Elasticsearch\Client;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;

abstract class TranslatableRepository extends AbstractRepository
{
    /** @var string[] */
    protected $locales;

    public function __construct(array $locales, Client $elasticsearch)
    {
        parent::__construct($elasticsearch);
        $this->locales = $locales;
    }

    public function get(string $id, string $locale = null): Model
    {
        $response = $this->elasticsearch->get([
            'index' => $this->getLocalizedIndexName($locale),
            'id' => $id,
        ]);

        return $this->newFromElasticResults($response);
    }

    public function search(SearchRequest $request, $locale = null): SearchResult
    {
        if ($request->getSearchWindowSize() > $this->getMaxResultWindowConfig($locale))
        {
            Log::warning("Search request size ({$request->getSearchWindowSize()}) is larger than index.max_result_window. Returning empty set.");
            return $this->createEmptySearchResult();
        }

        $response = $this->elasticsearch->search([
            'index' => $this->getLocalizedIndexName($locale),
            'body' => $this->buildBodyFromSearchRequest($request),
        ]);

        return $this->createSearchResult($response);
    }

    public function count(Filter $filter = null, string $locale = null): int
    {
        $query = $this->buildQueryFromFilter($filter);
        $body = $query ? ['query' => $query] : [];
        $response = $this->elasticsearch->count([
            'index' => $this->getLocalizedIndexName($locale),
            'body' => $body,
        ]);

        return (int)$response['count'];
    }

    /**
     * @param Model|IndexableModel $model
     * @param string|null $locale
     */
    public function index(Model $model, string $locale = null): void
    {
        $this->elasticsearch->index([
            'index' => $this->getLocalizedIndexName($locale),
            'id' => $model->getKey(),
            'body' => $model->getIndexedData($locale),
        ]);
    }

    public function delete(Model $model, string $locale = null): void
    {
        $this->elasticsearch->delete([
            'index' => $this->getLocalizedIndexName($locale),
            'id' => $model->getKey(),
        ]);
    }

    public function getMinimum(string $attribute, Filter $filter = null, string $locale = null): ?int
    {
        return $this->getExtreme('min', $attribute, $filter, $locale);
    }

    public function getMaximum(string $attribute, Filter $filter = null, string $locale = null): ?int
    {
        return $this->getExtreme('max', $attribute, $filter, $locale);
    }

    public function getExtreme(string $extreme, string $attribute, Filter $filter = null, string $locale = null): ?int
    {
        $query = $this->buildQueryFromFilter($filter);
        $body = $query ? ['query' => $query] : [];
        $body['aggs']['extreme'][$extreme]['field'] = $attribute;

        $response = $this->elasticsearch->search([
            'index' => $this->getLocalizedIndexName($locale),
            'size' => 0,
            'body' => $body,
        ]);

        return $response['aggregations']['extreme']['value'] ?? null;
    }

    public function getRandom(int $size, Filter $filter = null, string $locale = null): SearchResult
    {
        $response = $this->elasticsearch->search([
            'index' => $this->getLocalizedIndexName($locale),
            'size' => $size,
            'body' => [
                'query' => [
                    'function_score' => [
                        'query' => $this->buildQueryFromFilter($filter),
                        'random_score' => new \stdClass,
                        'boost_mode' => 'replace',
                    ]
                ]
            ]
        ]);

        return $this->createSearchResult($response);
    }

    public function listValues(string $attribute, Filter $filter, string $locale = null): Collection
    {
        $query = $this->buildQueryFromFilter($filter);
        $body = $query ? ['query' => $query] : [];
        $body['aggs'][$attribute]['terms'] = [
            'field' => $attribute,
            'size' => 1000, // todo ?
        ];

        $response = $this->elasticsearch->search([
            'index' => $this->getLocalizedIndexName($locale),
            'size' => 0,
            'body' => $body,
        ]);

        return $this->createBucketCollection($response, $attribute);
    }

    public function getIndex(string $locale = null): array
    {
        return $this->elasticsearch->indices()->get([
            'index' => $this->getLocalizedIndexName($locale)
        ]);
    }

    public function deleteIndex(string $locale = null): void
    {
        $this->elasticsearch->indices()->delete([
            'index' => $this->getLocalizedIndexName($locale)
        ]);
    }

    public function createIndex(string $locale = null): void
    {
        $this->elasticsearch->indices()->create([
            'index' => $this->getLocalizedIndexName($locale),
            'body' => $this->getIndexConfig($locale)
        ]);
    }

    public function createMapping(string $locale = null): void
    {
        $this->elasticsearch->indices()->putMapping([
            'index' => $this->getLocalizedIndexName($locale),
            'body' => $this->getMappingConfig($locale)
        ]);
    }

    public function refreshIndex(string $locale = null): void
    {
        $this->elasticsearch->indices()->refresh([
            'index' => $this->getLocalizedIndexName($locale)
        ]);
    }

    public function indexAllLocales(Model $model): void
    {
        foreach ($this->locales as $locale) {
            $this->index($model, $locale);
        }
    }

    public function deleteAllLocales(Model $model): void
    {
        foreach ($this->locales as $locale) {
            $this->delete($model, $locale);
        }
    }

    public function deleteIndexAllLocales(): void
    {
        foreach ($this->locales as $locale) {
            $this->deleteIndex($locale);
        }
    }

    public function createIndexAllLocales(): void
    {
        foreach ($this->locales as $locale) {
            $this->createIndex($locale);
        }
    }

    public function refreshIndexAllLocales(): void
    {
        foreach ($this->locales as $locale) {
            $this->refreshIndex($locale);
        }
    }

    public function createMappingAllLocales(): void
    {
        foreach ($this->locales as $locale) {
            $this->createMapping($locale);
        }
    }

    public function getLocalizedIndexName(string $locale = null): string
    {
        return sprintf(
            '%s_%s_%s',
            $this->prefix,
            $this->index,
            $this->getLocale($locale)
        );
    }

    protected function getLocale(string $locale = null): string
    {
        return $locale ?? app()->getLocale();
    }

    protected function getMaxResultWindowConfig(string $locale = null): int
    {
        return Arr::get($this->getIndexConfig($locale), 'settings.max_result_window');
    }

    abstract public function getSuggestions(int $size, string $search, string $locale = null): SearchResult;

    abstract public function getIndexConfig(string $locale = null): array;

    abstract public function getMappingConfig(string $locale = null): array;
}
