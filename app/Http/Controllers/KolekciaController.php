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
        $sort_order = ($sort_by == 'name') ? 'asc' : 'desc';

        $collections = Collection::published()->with('user')->orderBy($sort_by, $sort_order)->paginate($per_page);
        return view('kolekcie', array('collections'=>$collections, 'sort_by'=>$sort_by));
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

        $result = Collection::published()->where('name', 'like', '%'.$q.'%')->limit(5)->get();

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
