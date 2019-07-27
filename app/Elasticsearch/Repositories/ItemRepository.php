<?php

namespace App\Elasticsearch\Repositories;

use App\Authority;
use App\Item;
use App\SearchResult;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class ItemRepository extends TranslatableRepository
{
    protected $modelClass = Item::class;

    protected $index = 'items';

    public function getSuggestions(int $size, string $search, string $locale = null): SearchResult
    {
        $response = $this->elasticsearch->search([
            'index' => $this->getLocalizedIndexName($locale),
            'size' => $size,
            'body' => [
                'query' => [
                    'multi_match' => [
                        'query' => $search,
                        'type' => 'cross_fields',
                        'fields' => ['identifier', 'title.suggest', 'author.suggest'],
                        'operator' => 'and',
                    ]
                ]
            ]
        ]);

        return $this->createSearchResult($response);
    }

    public function getSimilar(int $size, Model $model, $locale = null): SearchResult
    {
        $response = $this->elasticsearch->search([
            'index' => $this->getLocalizedIndexName($locale),
            'size' => $size,
            'body' => [
                'query' => [
                    'bool'=> [
                        'must' => [
                            [
                                'more_like_this' => [
                                    'like' => [
                                        [
                                            '_index' => $this->getLocalizedIndexName($locale),
                                            '_id' => $model->id,
                                        ]
                                    ],
                                    'fields' => [
                                        'author.folded',
                                        'title',
                                        'title.stemmed',
                                        'description.stemmed',
                                        'tag.folded',
                                        'place',
                                        'technique',
                                    ],
                                    'min_term_freq' => 1,
                                    'min_doc_freq' => 1,
                                    'minimum_should_match' => 1,
                                    'min_word_length' => 1,
                                ]
                            ]
                        ],
                        'should' => [
                            [
                                'term' => [
                                    'has_image' => [
                                        'value' => true,
                                        'boost' => 10,
                                    ]
                                ]
                            ],
                            [
                                'term' => ['has_iip' => true]
                            ]
                        ]
                    ]
                ]
            ]
        ]);

        return $this->createSearchResult($response);
    }

    protected function createBucketCollection(array $response, string $attribute): Collection
    {
        $choices = collect();
        foreach ($response['aggregations'][$attribute]['buckets'] as $bucket) {
            $key = $bucket['key'];

            if ($attribute === 'author') {
                $key = formatName($key);
            }

            $label = sprintf('%s (%d)', $key, $bucket['doc_count']);
            $choices[$label] = $bucket['key'];
        }

        return $choices;
    }

    public function getPreviewItems(int $size, Authority $authority, string $locale = null): Collection
    {
        $response = $this->elasticsearch->search([
            'index' => $this->getLocalizedIndexName($locale),
            'body' => [
                'size' => $size,
                'query' => [
                    'bool' => [
                        'must' => [
                            ['term' => ['authority_id' => $authority->id]],
                        ],
                        'should' => [
                            ['term' => ['has_image' => true]],
                            ['term' => ['has_iip' => true]],
                        ],
                    ],
                ],
                'sort' => [
                    '_score',
                    ['created_at' => ['order' => 'asc']],
                ]
            ],
        ]);

        return $this->createSearchResult($response)->getCollection();
    }

    protected function getIndexConfig(string $locale  = null): array
    {
        return config('elasticsearch.index.items')[$this->getLocale($locale)];
    }

    protected function getMappingConfig(string $locale = null): array
    {
        return config('elasticsearch.mapping.items')[$this->getLocale($locale)];
    }
}