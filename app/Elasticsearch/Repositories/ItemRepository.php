<?php

namespace App\Elasticsearch\Repositories;

use App\Authority;
use App\Filter\Contracts\Filter;
use App\Filter\Contracts\SearchRequest;
use App\IntegerRange;
use App\Item;
use App\SearchResult;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Session;
use Primal\Color\Color;
use Primal\Color\Parser;

class ItemRepository extends TranslatableRepository
{
    protected $modelClass = Item::class;

    protected $index = 'items';

    public static function buildRandomSortQuery(array $query, $firstPage = true): array
    {
        if ($firstPage) {
            Session::put('ItemRepository::random-seed', mt_rand());
        }

        return [
            'function_score' => [
                'query' => $query,
                'functions' => [
                    [
                        'random_score' => [
                            'seed' => Session::get('ItemRepository::random-seed'),
                            'field' => '_seq_no'
                        ],
                    ],
                    [
                        "field_value_factor" => [
                            "field" => "has_image",
                            "factor" => 10
                        ]
                    ]
                ],
                "boost_mode" => "sum",
            ],

        ];
    }

    public static function buildDefaultSortQuery() {
        return [
            '_score',
            ['has_image' => ['order' => 'desc']],
            ['has_iip' => ['order' => 'desc']],
            ['updated_at' => ['order' => 'desc']],
            ['created_at' => ['order' => 'desc']],
        ];
    }

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
        $query = [
            'bool' => [
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
                    ],
                    [
                        'term' => [
                            'has_image' => [
                                'value' => true,
                                'boost' => 10,
                            ]
                        ]
                    ]
                ],
                'should' => [
                    [
                        'term' => ['has_iip' => true]
                    ]
                ]
            ]
        ];

        if ($model->related_work) {
            $query['bool']['must_not'] = [
                [
                    'term' => [
                        'related_work' => [
                            'value' => $model->related_work,
                        ]
                    ]
                ]
            ];
        }


        $response = $this->elasticsearch->search([
            'index' => $this->getLocalizedIndexName($locale),
            'size' => $size,
            'body' => [
                'query' => $query
            ]
        ]);

        return $this->createSearchResult($response);
    }

    public function getSimilarByColor(int $size, Item $item, $locale = null): SearchResult
    {
        $diff = 15;
        $musts = [];
        foreach ($item->getColors() as $color => $amount) {
            $hsl = Parser::Parse($color)->toHSL();
            $amountDiff = max($amount / 2, 0.15);
            $musts[] = [
                'bool' => [
                    'must' => [
                        self::buildHueRangeQuery($hsl->hue, $diff),
                        [
                            'range' => [
                                'hsl.s' => [
                                    'gte' => $hsl->saturation - $diff,
                                    'lte' => $hsl->saturation + $diff,
                                ]
                            ]
                        ],
                        [
                            'range' => [
                                'hsl.l' => [
                                    'gte' => $hsl->luminance - $diff,
                                    'lte' => $hsl->luminance + $diff,
                                ]
                            ]
                        ],
                        [
                            'range' => [
                                'hsl.amount' => [
                                    'gte' => $amount - $amountDiff,
                                    'lte' => $amount + $amountDiff,
                                ]
                            ]
                        ]
                    ]
                ]
            ];
        }

        $query = [];
        foreach ($musts as $must) {
            $query['bool']['should'][]['nested'] = [
                'path' => 'hsl',
                'query' => $must,
            ];
        }
        $query['bool']['must_not'][]['term']['id'] = $item->id;
        $query['bool']['minimum_should_match'] = '-30%';

        $response = $this->elasticsearch->search([
            'index' => $this->getLocalizedIndexName($locale),
            'size' => $size,
            'body' => ['query' => $query],
        ]);

        return $this->createSearchResult($response);
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

    public function buildQueryFromFilter(?Filter $filter): ?array
    {
        if (!$filter) {
            return null;
        }

        $query = [];
        $query = $this->addFilterablesQuery($query, $filter);
        $query = $this->addSearchQuery($query, $filter->get('search'));
        $query = $this->addYearsQuery($query, $filter->get('years'));
        $query = $this->addColorQuery($query, $filter->get('color'));
        return $query ?: null;
    }

    protected function addSearchQuery(array $query, ?string $search): array
    {
        if ($search === null) {
            return $query;
        }

        $should_match = [
            'identifier' => [
                'query' => $search,
                'boost' => 10,
            ],
            'author.folded' => [
                'query' => $search,
                'boost' => 5,
            ],
            'title' => $search,
            'title.folded' => $search,
            'title.stemmed' => [
                'query' => $search,
                'analyzer' => 'synonyms_analyzer'
            ],
            'tag.folded' => $search,
            'tag.stemmed' => $search,
            'place.folded' => $search,
            'description' => $search,
            'description.stemmed' => [
                'query' => $search,
                'analyzer' => 'synonyms_analyzer',
                'boost' => 0.5,
            ],
        ];

        $should = [];
        foreach ($should_match as $key => $match) {
            $should[] = ['match' => [$key => $match]];
        }

        $query['bool']['should'] = $should;
        $query['bool']['minimum_should_match'] = 1;
        return $query;
    }

    protected function addYearsQuery(array $query, ?IntegerRange $years): array
    {
        if (!$years) {
            return $query;
        }

        if ($years->getFrom() !== null) {
            $query['bool']['filter'][]['range']['date_latest']['gte'] = $years->getFrom();
        }

        if ($years->getTo() !== null) {
            $query['bool']['filter'][]['range']['date_earliest']['lte'] = $years->getTo();
        }

        return $query;
    }

    public static function buildBoolQueryForColor(Color $color)
    {
        $diff = 15;
        $hsl = $color->toHSL();
        return [
            'bool' => [
                'must' => [
                    self::buildHueRangeQuery($hsl->hue, $diff),
                    [
                        'range' => [
                            'hsl.s' => [
                                'gte' => $hsl->saturation - $diff,
                                'lte' => $hsl->saturation + $diff,
                            ]
                        ]
                    ],
                    [
                        'range' => [
                            'hsl.l' => [
                                'gte' => $hsl->luminance - $diff,
                                'lte' => $hsl->luminance + $diff,
                            ]
                        ]
                    ]
                ]
            ]
        ];
    }

    protected function addColorQuery(array $query, ?Color $color): array
    {
        if (!$color) {
            return $query;
        }

        $query['bool']['filter'][]['nested'] = [
            'path' => 'hsl',
            'query' => self::buildBoolQueryForColor($color)
        ];

        return $query;
    }

    private static function buildHueRangeQuery(float $hue, float $diff): array
    {
        if ($hue < $diff) {
            return [
                'bool' => [
                    'should' => [
                        [
                            'range' => [
                                'hsl.h' => [
                                    'gte' => $hue - $diff + 360,
                                ]
                            ]
                        ],
                        [
                            'range' => [
                                'hsl.h' => [
                                    'lte' => $hue + $diff,
                                ]
                            ]
                        ]
                    ]
                ]
            ];
        }

        if ($hue > 360 - $diff) {
            return [
                'bool' => [
                    'should' => [
                        [
                            'range' => [
                                'hsl.h' => [
                                    'gte' => $hue - $diff,
                                ]
                            ]
                        ],
                        [
                            'range' => [
                                'hsl.h' => [
                                    'lte' => $hue + $diff - 360,
                                ]
                            ]
                        ]
                    ]
                ]
            ];
        }

        return [
            'range' => [
                'hsl.h' => [
                    'gte' => $hue - $diff,
                    'lte' => $hue + $diff,
                ]
            ]
        ];
    }

    protected function addSort(array $body, SearchRequest $request): array
    {
        $sortBy = $request->getSortBy();

        if ($sortBy === 'random') {
            $body['query'] = self::buildRandomSortQuery($body['query'] ?? ['match_all' => new \stdClass], $request->getFrom() === 0);
            return $body;
        }

        if ($sortBy === null) {
            $body['sort'] = self::buildDefaultSortQuery();
            return $body;
        }

        if ($sortBy === 'newest') {
            $body['sort'] = ['date_earliest' => ['order' => 'desc']];
            return $body;
        }

        if ($sortBy === 'oldest') {
            $body['sort'] = ['date_earliest' => ['order' => 'asc']];
            return $body;
        }

        $sortOrder = in_array($sortBy, ['author', 'title']) ? 'asc' : 'desc';
        $body['sort'] = [$sortBy => ['order' => $sortOrder]];
        return $body;
    }

    public function reindexAllLocales(Carbon $viewedSince = null): int
    {
        $processedCount = 0;

        $query = $this->modelClass::with(['images', 'authorities', 'translations', 'tagged']);

        if ($viewedSince) {
            $query->where('last_viewed_at', '>=', $viewedSince);
        }

        $query
            ->lazy()
            ->tapEach(function () use (&$processedCount) {
                $processedCount++;
            })
            ->flatMap(function (Item $item) {
                $operations = [];

                foreach ($this->locales as $locale) {
                    // Action
                    $operations[] = [
                        'index' => [
                            '_index' => $this->getLocalizedIndexName($locale),
                            '_id' => $item->getKey(),
                        ],
                    ];

                    // Data
                    $operations[] = $item->getIndexedData($locale);
                }

                return $operations;
            })
            // chunk size = 2 operations * number of locales * 200 items
            ->chunk(2 * count($this->locales) * 200)
            ->each(function ($operations) use (&$processedCount) {
                $this->elasticsearch->bulk(['body' => $operations]);

                // Progress report
                if (app()->runningInConsole()) {
                    echo date('h:i:s') . ' ' . $processedCount . "\n";
                }
            });

        return $processedCount;
    }

    public function reindexAllViewedSince(Carbon $viewedSince): int
    {
        return $this->reindexAllLocales($viewedSince);
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
