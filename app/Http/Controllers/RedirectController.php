<?php

namespace App\Http\Controllers;

use App\Redirect;
use Illuminate\Http\Request;

class RedirectController extends Controller
{
    public function index()
    {
        return view('redirects.index')
            ->with('redirects', Redirect::orderBy('id', 'desc')->get());
    }

    public function create()
    {
        return view('redirects.form');
    }

    public function store(Request $request)
    {
        $request->validate(Redirect::$rules);

        $redirect = Redirect::create($request->input());

        return redirect()
            ->route('redirects.index')
            ->with('message', 'Presmerovanie pre ' .$redirect->source_url. ' bolo vytvorené');
    }

    public function edit(Redirect $redirect)
    {
        return view('redirects.form')->with('redirect', $redirect);
    }

    public function update(Request $request, Redirect $redirect)
    {
        $rules = Redirect::$rules;
        // validation unique source_url to ignore current redirect ID
        // https://stackoverflow.com/a/27194281/2660740
        $rules['source_url'] = $rules['source_url'] . ',source_url,' . $redirect->id;

        $request->validate($rules);

        $redirect->update($request->input());

        return redirect()
            ->route('redirects.index')
            ->with('message', 'Presmerovanie pre ' .$redirect->source_url. ' bolo upravené');
    }

    public function destroy(Redirect $redirect)
    {
        $redirect->delete();

        return redirect()
            ->route('redirects.index')
            ->with('message', 'Presmerovanie bolo zmazané');
    }
}
