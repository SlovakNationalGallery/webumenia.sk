<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Item;
use Elasticsearch\Client;
use Illuminate\Pagination\Paginator;

$wrapDocument = function (array $document) {
    return [
        'model' => [
            'image_url' => sprintf('%s%s',
                config('app.url'),
                Item::getImagePathForId($document['_id'])
            )
        ],
        'document' => [
            'id' => $document['_id'],
            'content' => $document['_source'],
        ]
    ];
};

Route::get('/items', function (Request $request) use ($wrapDocument) {
    $filter = (array)$request->get('filter');
    $size = (int)$request->get('size', 1);
    $page = (int)$request->get('page', 1);

    $filterables = [
        'location',
    ];

    $query = [];
    foreach ($filter as $field => $value) {
        if (is_string($value) && in_array($field, $filterables, true)) {
            $query['bool']['filter'][]['term'][$field] = $value;
        }
    }

    if (!$query) {
        $query['match_all'] = new \stdClass;
    }

    $response = app(Client::class)->search([
        'index' => config('bouncy.index'),
        'type' => Item::ES_TYPE,
        'body' => ['query' => $query],
        'size' => (int)$size,
        'from' => ($page - 1) * $size,
    ]);

    $items = array_map($wrapDocument, $response['hits']['hits']);

    $paginator = new Paginator(
        $items,
        $size,
        $page
    );

    return response()->json($paginator)
        ->withHeaders([
            'Access-Control-Allow-Origin' => '*',
            'Access-Control-Allow-Methods' => '*',
            'Access-Control-Allow-Headers' => '*',
        ]);
});

Route::get('/items/{id}', function (Request $request, $id) use ($wrapDocument) {
    $response = app(Client::class)->get([
        'index' => config('bouncy.index'),
        'type' => Item::ES_TYPE,
        'id' => $id,
    ]);

    return response()->json($wrapDocument($response))
        ->withHeaders([
            'Access-Control-Allow-Origin' => '*',
            'Access-Control-Allow-Methods' => '*',
            'Access-Control-Allow-Headers' => '*',
        ]);
});
