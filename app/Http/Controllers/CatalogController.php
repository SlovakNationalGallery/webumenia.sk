<?php

namespace App\Http\Controllers;

use App\Color;
use Illuminate\Support\Facades\Input;
use App\Item;
use Illuminate\Support\Facades\DB;
use Fadion\Bouncy\Facades\Elastic;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Response;

use Illuminate\Pagination\Paginator;
use Illuminate\Pagination\LengthAwarePaginator;

class CatalogController extends ElasticController
{
    protected function resolveSortOptions() {
        $sortables = Item::getSortables();

        $key = Input::has('search') ? 'updated_at' : 'relevance';
        unset($sortables[$key]);

        return $sortables;
    }

    protected function resolveSortKey(array $sortOptions) {
        $sortKey = (string)Input::get('sort_by');

        if (!isset($sortOptions[$sortKey])) {
            reset($sortOptions);
            $sortKey = key($sortOptions);
        }

        return $sortKey;
    }

    protected function resolveSortParams($sortKey) {
        $sortParams = [];

        if (in_array($sortKey, ['relevance', 'updated_at'])) {
            $sortParams[] = '_score';
            $sortParams[] = ['has_image' => ['order' => 'desc']];
            $sortParams[] = ['has_iip' => ['order' => 'desc']];
            $sortParams[] = ['updated_at' => ['order' => 'desc']];
            $sortParams[] = ['created_at' => ['order' => 'desc']];
        } else if ($sortKey == 'random') {
            $sortParams[]['_script'] = [
                'script' => 'Math.random() * 200000',
                'type' => 'number',
                'params' => [],
                'order' => 'asc',
            ];
        } else {
            $sortOrder = in_array($sortKey, ['author', 'title', 'oldest']) ? 'asc' : 'desc';
            $sortBy = in_array($sortKey, ['newest', 'oldest']) ? 'date_earliest' : $sortKey;

            $sortParams[] = [$sortBy => ['order' => $sortOrder]];
        }

        return $sortParams;
    }

    public function getIndex()
    {
        $search = Input::get('search', null);
        $input = Input::all();

        //ak zada presne ID
        if (strpos($search, 'SVK:') !== false) {
            $item = Item::find($search);
            if ($item) {
                return redirect($item->getUrl());
            }
        }
        $search = trim(preg_replace('/(grid|table)Layout.*/', '', $search)); // zdedene zo zaindexovanych url zo stareho webu

        $per_page = 18;
        $page   = Paginator::resolveCurrentPage() ?: 1;
        $max_pages = floor(10000/$per_page); // ES max_result_window = 10000
        if ($page > $max_pages) {
            return redirect()->route(\Route::currentRouteName(), Input::except('page'));
        }
        $offset = ($page * $per_page) - $per_page;

        $params = array();
        $params['from'] = $offset;
        $params['size'] = $per_page;

        $sortOptions = $this->resolveSortOptions();
        $sortKey = $this->resolveSortKey($sortOptions);
        $sortParams = $this->resolveSortParams($sortKey);
        $params['sort'] = $sortParams;

        if (!empty($input)) {
            if (Input::has('search')) {
                $search = str_to_alphanumeric($search);

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
                    'title.stemmed' => $search,
                    'title.stemmed' => [
                        'query' => $search,
                        'analyzer' => $this->elastic_translatable->getAnalyzerNameForSynonyms(),
                    ],
                    'tag.folded' => $search,
                    'tag.stemmed' => $search,
                    'place.folded' => $search,
                    'description' =>  $search,
                    'description.stemmed' => [
                        'query' => $search,
                        'boost' => 0.9,
                    ],
                    'description.stemmed' => [
                        'query' => $search,
                        'analyzer' => $this->elastic_translatable->getAnalyzerNameForSynonyms(),
                        'boost' => 0.5,
                    ],
                ];

                $should = [];
                foreach ($should_match as $key => $match) {
                    $should[] = ['match' => [$key => $match]];
                }

                $params['query']['bool']['should'] = $should;
                $params['query']['bool']['minimum_should_match'] = 1;
            }

            foreach ($input as $filter => $value) {
                if (in_array($filter, Item::$filterable) && !empty($value)) {
                    $params['query']['bool']['filter']['and'][]['term'][$filter] = $value;
                }
            }
            if (!empty($input['year-range']) &&
                $input['year-range'] != Item::sliderMin().','.Item::sliderMax() //nezmenena hodnota
            ) {
                $range = explode(',', $input['year-range']);
                $params['query']['bool']['filter']['and'][]['range']['date_earliest']['gte'] = (isset($range[0])) ? $range[0] : Item::sliderMin();
                $params['query']['bool']['filter']['and'][]['range']['date_latest']['lte'] = (isset($range[1])) ? $range[1] : Item::sliderMax();
            }
        }

        if (Input::has('color')) {
            // get color used for filter
            $color = $input['color'];

            try {
                // build color descriptor
                $hex = new Color($color, Color::TYPE_HEX);
                $lab = $hex->convertTo(Color::TYPE_LAB);

                $value = $lab->getValue();
                $block = [$value['L'], $value['a'], $value['b'], sqrt(100)];

                $descriptor = [];
                for ($i = 0; $i < config('colordescriptor.colorCount'); $i++) {
                    $descriptor = array_merge($descriptor, $block);
                }

                $params['query']['bool']['should'][]['descriptor'] = [
                    'color_descriptor' => [
                        'hash' => 'LSH',
                        'descriptor' => $descriptor,
                    ]
                ];

                $params['min_score'] = pow(10, -4);
            } catch (\InvalidArgumentException $e) {}
        } else {
            $color = false;
        }

        $items = Item::search($params);
        $path   = '/' . \Request::path();

        $paginator = new LengthAwarePaginator($items->all(), $items->total(), $per_page, $page, ['path' => $path]);

        $authors = Item::listValues('author', $params);
        $work_types = Item::listValues('work_type', $params);
        $tags = Item::listValues('tag', $params);
        $galleries = Item::listValues('gallery', $params);
        $topics = Item::listValues('topic', $params);
        $techniques = Item::listValues('technique', $params);

        $queries = DB::getQueryLog();
        $last_query = end($queries);

        return view('katalog', array(
            'items' => $items,
            'authors' => $authors,
            'work_types' => $work_types,
            'tags' => $tags,
            'galleries' => $galleries,
            'topics' => $topics,
            'techniques' => $techniques,
            'color' => $color,
            'search' => $search,
            'sort_by' => $sortKey,
            'sort_options' => $sortOptions,
            'input' => $input,
            'paginator' => $paginator,
            ));
    }

    public function getSuggestions()
    {
        $q = (Input::has('search')) ? str_to_alphanumeric(Input::get('search')) : 'null';

        $result = Elastic::search([
                'index' => Config::get('bouncy.index'),
                'type' => Item::ES_TYPE,
                'body' => array(
                    'query' => array(
                        'multi_match' => array(
                            'query' => $q,
                            'type' => 'cross_fields',
                            // 'fuzziness' =>  2,
                            // 'slop'		=>  2,
                            'fields' => array('identifier', 'title.suggest', 'author.suggest'),
                            'operator' => 'and',
                        ),
                    ),
                    'size' => '10',
                ),
            ]);

        $data = array();
        $data['results'] = array();
        $data['count'] = 0;

        // $data['items'] = array();
        foreach ($result['hits']['hits'] as $key => $hit) {
            $authors = array();
            foreach ($hit['_source']['author'] as $author) {
                $authors[] = preg_replace('/^([^,]*),\s*(.*)$/', '$2 $1', $author);
            }

            ++$data['count'];
            $params = array(
                'id' => $hit['_id'],
                'title' => $hit['_source']['title'],
                'author' => $authors,
                'image' => Item::getImagePathForId($hit['_id'], false, 70),
            );
            $data['results'][] = array_merge($params);
        }

        return Response::json($data);
    }

    public function getRandom()
    {
        $item = Item::random()->first();
        $result_item = [
            'id' => $item->id,
            'identifier' => $item->identifier,
            'title' => $item->title,
            'author' => $item->author,
            'description' => $item->description,
            'dating' => $item->dating,
            'medium' => $item->medium,
            'technique' => $item->technique,
            'gallery' => $item->gallery,
            'url' => $item->getUrl(),
            'img_url' => \URL::to($item->getImagePath()),
        ];

        return response()->json($result_item);
    }

}
