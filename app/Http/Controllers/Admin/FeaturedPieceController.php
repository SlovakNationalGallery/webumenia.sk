<?php

namespace App\Http\Controllers\Admin;

use App\FeaturedPiece;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class FeaturedPieceController extends Controller
{
    public function index()
    {
        $featuredPieces = FeaturedPiece::orderBy('id', 'desc')
            ->with('media')
            ->get();

        return view('admin.featured_pieces.index')
            ->with('featuredPieces', $featuredPieces);
    }

    public function create()
    {
        return view('slides.form');
    }

    public function store(Request $request)
    {
        $request->validate(FeaturedPiece::$rules);

        $featuredPiece = FeaturedPiece::create($request->input());
        if ($request->hasFile('image')) {
            $featuredPiece
                ->addMediaFromRequest('image')
                ->toMediaCollection('image');
        }

        return redirect()
            ->route('featured-pieces.index')
            ->with('message', "Odporúčaný obsah \"{$featuredPiece->name}\" bol vytvorený");
    }

    public function edit(FeaturedPiece $featuredPiece)
    {
        return view('admin.featured_pieces.form')->with('featuredPiece', $featuredPiece);
    }

    public function update(Request $request, FeaturedPiece $featuredPiece)
    {
        $request->validate(FeaturedPiece::$rules);

        $featuredPiece->update($request->input());
        if ($request->hasFile('image')) {
            $featuredPiece
                ->addMediaFromRequest('image')
                ->toMediaCollection('image');
        }

        return redirect()
            ->route('featured-pieces.index')
            ->with('message', "Odporúčaný obsah \"{$featuredPiece->name}\" bol upravený");
    }

    public function destroy(FeaturedPiece $featuredPiece)
    {
        $featuredPiece->delete();

        return redirect()
            ->route('featured-pieces.index')
            ->with('message', 'Odporúčaný obsah bol zmazaný');
    }
}
