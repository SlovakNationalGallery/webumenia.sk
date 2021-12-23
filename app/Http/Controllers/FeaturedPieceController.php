<?php

namespace App\Http\Controllers;

use App\FeaturedPiece;
use Illuminate\Http\Request;

class FeaturedPieceController extends Controller
{
    public function index()
    {
        return view('slides.index')
            ->with('slides', FeaturedPiece::orderBy('id', 'desc')->get());
    }

    public function create()
    {
        return view('slides.form');
    }

    public function store(Request $request)
    {
        $request->validate(FeaturedPiece::$rules);

        $slide = FeaturedPiece::create($request->input());
        if ($request->hasFile('image')) {
            $slide
                ->addMediaFromRequest('image')
                ->toMediaCollection('image');
        }

        return redirect()
            ->route('slide.index')
            ->with('message', 'Carousel ' .$slide->name. ' bol vytvorený');
    }

    public function edit(FeaturedPiece $slide)
    {
        return view('slides.form')->with('slide', $slide);
    }

    public function update(Request $request, FeaturedPiece $slide)
    {
        $request->validate(FeaturedPiece::$rules);

        $slide->update($request->input());
        if ($request->hasFile('image')) {
            $slide
                ->addMediaFromRequest('image')
                ->toMediaCollection('image');
        }

        return redirect()
            ->route('slide.index')
            ->with('message', 'Carousel ' .$slide->name. ' bol upravený');
    }

    public function destroy(FeaturedPiece $slide)
    {
        $slide->delete();

        return redirect()
            ->route('slide.index')
            ->with('message', 'Carousel bol zmazaný');
    }
}
