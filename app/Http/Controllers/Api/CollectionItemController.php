<?php

namespace App\Http\Controllers\Api;

use App\Collection;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Request;

class CollectionItemController extends Controller
{
    public function index(Collection $collection)
    {
        $filter = $collection->item_filter;
        if (!$filter) {
            abort(404);
        }

        $parameters = ['filter' => $filter];

        if (request()->has('size')) {
            $parameters['size'] = request()->integer('size');
        }

        if (request()->has('page')) {
            $parameters['page'] = request()->integer('page');
        }

        $request = Request::create(route('api.v1.items.index', $parameters));
        return app()->handle($request);
    }
}
