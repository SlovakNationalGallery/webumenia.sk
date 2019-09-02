<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Input;
use App\Space;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\App;
use Illuminate\Http\Request;

use Illuminate\Pagination\Paginator;

class PriestorController extends Controller
{

    public function getIndex(Request $request)
    {
        $per_page = 18;

        // $spaces = Space::orderBy('name', 'asc'); // @TODO if neccessary, follow https://github.com/dimsav/laravel-translatable#how-do-i-sort-by-translations
        $spaces = Space::orderBy('opened_date', 'asc');

        if ($request->has('first-letter')) {
            $spaces = $spaces->whereTranslationLike('name', $request->input('first-letter') . '%');
        }

        $spaces = $spaces->paginate($per_page);


        return view('khb.priestory', [
            'spaces'=>$spaces,
        ]);
    }

    public function getSuggestions()
    {
        $q = (Input::has('search')) ? str_to_alphanumeric(Input::get('search')) : 'null';

        $data = array();
        $data['results'] = array();
        $data['count'] = 0;

        return Response::json($data);
    }

    public function getDetail($id)
    {
        $author = Space::find($id);
        if (empty($author)) {
            App::abort(404);
        }

        $author->timestamps = false;
        $author->view_count += 1;
        $author->save();

        $archive = $author->getMedia('document.'.\LaravelLocalization::getCurrentLocale());

        $items = \App\Item::where('author', 'like', $author->name)->get();
        return view('autor', [
            'author' => $author,
            'items' => $items,
            'archive' => $archive,
        ]);

    }
}
