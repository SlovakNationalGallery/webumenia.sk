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
        $space = Space::find($id);
        if (empty($space)) {
            App::abort(404);
        }

        $space->timestamps = false;
        $space->view_count += 1;
        $space->save();

        $archive = $space->getMedia('document.'.\LaravelLocalization::getCurrentLocale());

        return view('khb.priestor', [
            'space' => $space,
            'archive' => $archive,
        ]);

    }
}
