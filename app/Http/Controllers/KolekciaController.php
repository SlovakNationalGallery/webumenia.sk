<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Input;
use App\Collection;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Response;

class KolekciaController extends Controller
{

    public function getIndex()
    {
        $per_page = 18;
        if (Input::has('sort_by') && array_key_exists(Input::get('sort_by'), Collection::$sortable)) {
            $sort_by = Input::get('sort_by');
        } else {
            $sort_by = 'created_at';
        }

        $collections = Collection::published()->with('user');

        if ($sort_by == 'name') {
            $collections = $collections
                ->with('translations')
                ->join('collection_translations as t', function ($join) {
                    $join->on('collections.id', '=', 't.collection_id')
                        ->where('t.locale', '=', config('app.locale'));
                })
                ->orderBy('t.name', 'asc');
        } else {
            $collections = $collections->orderBy($sort_by, 'desc');
        }

        return view('kolekcie', [
            'collections' => $collections->paginate($per_page),
            'sort_by' => $sort_by
        ]);
    }

    public function getDetail($id)
    {
        // dd($id);
        $collection = Collection::find($id);
        if (empty($collection)) {
            App::abort(404);
        }
        $collection->view_count += 1;
        $collection->save();

        return view('kolekcia', array('collection'=>$collection));

    }

    public function getSuggestions()
    {
        $q = (Input::has('search')) ? str_to_alphanumeric(Input::get('search')) : 'null';

        $result = Collection::published()->whereTranslationLike('name', '%'.$q.'%')->limit(5)->get();

        $data = array();
        $data['results'] = array();
        $data['count'] = 0;

        foreach ($result as $key => $hit) {
            $data['count']++;
            $params = array(
                'name' => $hit->name,
                'author' => $hit->user->name,
                'items' => $hit->items->count(),
                'url' => $hit->getUrl(),
                'image' => $hit->getResizedImage(70),
            );
            $data['results'][] = array_merge($params) ;
        }

        return Response::json($data);
    }
}
