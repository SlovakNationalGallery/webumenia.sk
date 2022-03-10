<?php

namespace App\Http\Controllers;

use App\Article;
use App\Authority;
use App\Collection;
use App\FeaturedArtwork;
use App\FeaturedPiece;

class HomeController extends Controller
{
    public function index()
    {
        // TODO caching
        $featuredPiece = FeaturedPiece::query()
            ->published()
            ->with('media')
            ->orderBy('updated_at', 'desc')
            ->first();

        $featuredArtwork = FeaturedArtwork::query()
            ->published()
            ->orderBy('updated_at', 'desc')
            ->first();

        $featuredAuthor = Authority::query()
            ->where('has_image', true)
            ->where('type', 'person')
            ->has('items', '>', 3)
            ->inRandomOrder()
            ->first();

        $articles = Article::query()
            ->with(['translations', 'category'])
            ->published()
            ->orderBy('published_date', 'desc')
            ->limit(5)
            ->get();

        $articlesTotalCount = Article::published()->count();
        $articlesRemainingCount = floor(($articlesTotalCount - 5) / 10) * 10; // Round down to nearest 10

        $collections = Collection::query()
            ->with(['translations', 'user'])
            ->withCount('items')
            ->published()
            ->orderBy('published_at', 'desc')
            ->take(5)
            ->get();

        $collectionsTotalCount = Collection::published()->count();
        $collectionsRemainingCount = floor(($collectionsTotalCount - 5) / 10) * 10; // Round down to nearest 10

        return view('home.index')->with(
            compact([
                'featuredPiece',
                'featuredArtwork',
                'featuredAuthor',
                'articles',
                'articlesRemainingCount',
                'collections',
                'collectionsRemainingCount',
            ])
        );
    }
}
