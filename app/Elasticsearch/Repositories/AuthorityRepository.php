<?php

namespace App\Elasticsearch\Repositories;

use App\Authority;
use App\Filter\Contracts\Filter;
use App\Filter\Contracts\SearchRequest;
use App\IntegerRange;
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

    public function buildQueryFromFilter(?Filter $filter): ?array
    {
        if (!$filter) {
            return null;
        }

        $query = [];
        $query = $this->addFilterablesQuery($query, $filter);
        $query = $this->addYearsQuery($query, $filter->get('years'));
        $query = $this->addFirstLetterQuery($query, $filter->get('first_letter'));
        return $query ?: null;
    }

    protected function addSort(array $body, SearchRequest $request): array
    {
        $sort = [];
        $sortBy = $request->getSortBy();
        if ($sortBy === null) {
            $sort[] = ['items_with_images_count' => ['order' => 'desc']];
        } else {
            $sortOrder = $sortBy === 'name' ? 'asc' : 'desc';
            $sort[] = [$sortBy => ['order' => $sortOrder]];
        }

        $body['sort'] = $sort;
        return $body;
    }


    protected function addYearsQuery(array $query, ?IntegerRange $years): array
    {
        if (!$years) {
            return $query;
        }

        if ($years->getFrom() !== null) {
            $query["bool"]["should"][]["range"]["death_year"]["gte"] = $years->getFrom();
            $query["bool"]["should"][]["bool"]["must_not"]["exists"]["field"] = "death_year";
            $query["bool"]["minimum_should_match"] = 1;
        }

        if ($years->getTo() !== null) {
            $query["bool"]["must"][]["range"]["birth_year"]["lte"] = $years->getTo();
        }

        return $query;
    }

    protected function addFirstLetterQuery(array $query, ?string $firstLetter): array
    {
        if ($firstLetter === null) {
            return $query;
        }

        $query["bool"]["must"][]["prefix"]["name"] = $firstLetter;
        return $query;
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