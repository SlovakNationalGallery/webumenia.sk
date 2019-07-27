<?php

namespace App\Elasticsearch\Repositories;

use App\Authority;
use App\SearchResult;
use Illuminate\Database\Eloquent\Model;

class AuthorityRepository extends TranslatableRepository
{
    protected $modelClass = Authority::class;

    protected $index = 'authorities';

    public function getSuggestions(int $size, string $search, string $locale = null): SearchResult
    {
        $response = $this->elasticsearch->search([
            'index' => $this->getLocalizedIndexName($locale),
            'body' => [
                'size' => $size,
                'query' => [
                    'multi_match' => [
                        'query' => $search,
                        'type' => 'cross_fields',
                        'fields' => ['name.suggest', 'alternative_name.suggest'],
                        'operator' => 'and',
                    ]
                ],
                'sort' => [
                    'items_count' => ['order' => 'desc'],
                    'has_image' => ['order' => 'desc'],
                ],
            ],
        ]);

        return $this->createSearchResult($response);
    }

    public function index(Model $model, string $locale = null): void
    {
        if ($model->type === 'person') {
            parent::index($model, $locale);
        }
    }

    protected function getIndexConfig(string $locale = null): array
    {
        return config('elasticsearch.index.authorities')[$this->getLocale($locale)];
    }

    protected function getMappingConfig(string $locale = null): array
    {
        return config('elasticsearch.mapping.authorities')[$this->getLocale($locale)];
    }
}