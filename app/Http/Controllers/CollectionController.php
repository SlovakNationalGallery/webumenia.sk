<?php

namespace App\Http\Controllers;

use App\Collection;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Intervention\Image\ImageManagerStatic;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Str;

class CollectionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        if (Gate::allows('administer')) {
            $collections = Collection::orderBy('created_at', 'desc')->with(['user'])->paginate(20);
        } else {
            $collections = Collection::where('user_id', '=', Auth::user()->id)->orderBy('published_at', 'desc')->with(['user'])->paginate(20);
        }

        return view('collections.index')->with('collections', $collections);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        return view('collections.form');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store()
    {
        $input = Request::all();

        $rules = Collection::$rules;
        $v = Validator::make($input, $rules);

        if ($v->passes()) {
            $collection = new Collection();

            // store translatable attributes
            foreach (\Config::get('translatable.locales') as $i => $locale) {
                if (hasTranslationValue($locale, $collection->translatedAttributes)){
                    foreach ($collection->translatedAttributes as $attribute) {
                        $collection->translateOrNew($locale)->$attribute = Request::input($locale . '.' . $attribute);
                    }
                }
            }

            $collection->published_at = Request::input('published_at');

            if (Request::has('title_color')) {
                $collection->title_color = Request::input('title_color');
            }
            if (Request::has('title_shadow')) {
                $collection->title_shadow = Request::input('title_shadow');
            }
            if (Request::has('user_id') && Gate::allows('administer')) {
                $collection->user_id = Request::input('user_id');
            } else {
                $collection->user_id = Auth::user()->id;
            }

            $collection->save();

            if (Request::hasFile('main_image')) {
                $this->uploadMainImage($collection);
            }

            return Redirect::route('collection.index');
        }

        return Redirect::back()->withInput()->withErrors($v);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $collection = Collection::find($id);

        return view('collections.show')->with('collection', $collection);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $collection = Collection::find($id);

        if (is_null($collection)) {
            return Redirect::route('collection.index');
        }

        return view('collections.form')->with('collection', $collection);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param int $id
     *
     * @return Response
     */
    public function update($id)
    {
        $v = Validator::make(Request::all(), Collection::$rules);

        if ($v->passes()) {
            $input = Arr::except(Request::all(), array('_method'));

            $collection = Collection::find($id);

            foreach (\Config::get('translatable.locales') as $i => $locale) {
                if (hasTranslationValue($locale, $collection->translatedAttributes)){
                    foreach ($collection->translatedAttributes as $attribute) {
                        $collection->translateOrNew($locale)->$attribute = Request::input($locale . '.' . $attribute);
                    }
                }
            }

            $collection->published_at = Request::input('published_at', null);

            if (Request::has('user_id') && Gate::allows('administer')) {
                $collection->user_id = Request::input('user_id');
            }

            if (Request::has('title_color')) {
                $collection->title_color = Request::input('title_color');
            }
            if (Request::has('title_shadow')) {
                $collection->title_shadow = Request::input('title_shadow');
            }
            $collection->save();

            if (Request::hasFile('main_image')) {
                $this->uploadMainImage($collection);
            }

            Session::flash('message', 'Kolekcia <code>' . $collection->name . '</code> bola upravená');

            return Redirect::route('collection.index');
        }

        return Redirect::back()->withErrors($v);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        Collection::find($id)->delete();

        return Redirect::route('collection.index')->with('message', 'Kolekcia bola zmazaná');
    }

    /**
     * Fill the collection with items.
     *
     * @param
     *
     * @return Response
     */
    public function fill()
    {
        if ($collection = Collection::find(Request::input('collection'))) {
            $items = Request::input('ids');
            if (!is_array($items)) {
                $items = explode(';', str_replace(' ', '', $items));
            }
            foreach ($items as $item_id) {
                if (!$collection->items->contains($item_id)) {
                    $collection->items()->attach($item_id);
                }
            }

            return Redirect::back()->withMessage('Do kolekcie ' . $collection->name . ' bolo pridaných ' . count($items) . ' diel');
        } else {
            return Redirect::back()->withMessage('Chyba: zvolená kolekcia nebola nájdená. ');
        }
    }

    public function detach($collection_id, $item_id)
    {
        $collection = Collection::find($collection_id);
        $collection->items()->detach($item_id);

        return Redirect::back()->withMessage('Z kolekcie <strong>' . $collection->name . '</strong> bolo odstrádené dielo <code>' . $item_id . '</code>');
    }

    private function uploadMainImage($collection)
    {
        $main_image = Request::file('main_image');
        $collection->main_image = $collection->uploadHeaderImage($main_image);
        $collection->save();
    }

    public function sort()
    {
        $entity = Request::input('entity');
        $model_name = Str::studly($entity);
        // $model  = $model_name::find(\Request::input('id'));
        $collection = Collection::find(Request::input('id'));

        $ids = (array) Request::input('ids');
        $order = 0;
        $ordered_items = [];
        // $orders     = [];

        foreach ($ids as $id) {
            $ordered_items[$id] = ['order' => $order];
            ++$order;
        }

        $collection->items()->sync($ordered_items);

        $response = [
            'result' => 'success',
            'message' => 'poradie zmenene',
            'entity' => $entity,
            // 'orders'  => $orders,
        ];

        return response()->json($response);
    }
}
