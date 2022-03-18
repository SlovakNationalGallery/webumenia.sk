<?php

namespace App\Http\Controllers\Admin;

use App\FeaturedPiece;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class FeaturedPieceController extends Controller
{
    private static $rules = [
        'title' => 'required',
        'url' => 'required',
        'publish' => 'boolean',
        'image' => 'image|dimensions:min_width=1200',
        'type' => 'required|in:article,collection',
    ];

    public function index()
    {
        $featuredPieces = FeaturedPiece::orderBy('id', 'desc')
            ->with('media')
            ->get();

        return view('featured_pieces.index')
            ->with('featuredPieces', $featuredPieces);
    }

    public function create()
    {
        return view('featured_pieces.form');
    }

    public function store(Request $request)
    {
        $request->validate(self::$rules);

        $featuredPiece = FeaturedPiece::create($request->input());
        if ($request->hasFile('image')) {
            $featuredPiece
                ->addMediaFromRequest('image')
                ->toMediaCollection('image');
        }

        return redirect()
            ->route('featured-pieces.index')
            ->with('message', "Odporúčaný obsah \"{$featuredPiece->title}\" bol vytvorený");
    }

    public function edit(FeaturedPiece $featuredPiece)
    {
        return view('featured_pieces.form')->with('featuredPiece', $featuredPiece);
    }

    public function update(Request $request, FeaturedPiece $featuredPiece)
    {
        $request->validate(self::$rules);

        $featuredPiece->update($request->input());
        if ($request->hasFile('image')) {
            $featuredPiece
                ->addMediaFromRequest('image')
                ->toMediaCollection('image');
        }

        return redirect()
            ->route('featured-pieces.index')
            ->with('message', "Odporúčaný obsah \"{$featuredPiece->title}\" bol upravený");
    }

    public function destroy(FeaturedPiece $featuredPiece)
    {
        $featuredPiece->delete();

        return redirect()
            ->route('featured-pieces.index')
            ->with('message', 'Odporúčaný obsah bol zmazaný');
    }
}
