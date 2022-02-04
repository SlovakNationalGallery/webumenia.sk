<?php

namespace App\Http\Controllers;

use App\Article;
use App\Collection;

class HomeController extends Controller
{
    public function index()
    {
        // TODO caching

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
            ->published()
            ->orderBy('published_at', 'desc')
            ->take(5)
            ->get();

        $collectionsTotalCount = Collection::published()->count();
        $collectionsRemainingCount = floor(($collectionsTotalCount - 5) / 10) * 10; // Round down to nearest 10

        return view('home.index')->with(compact([
            'articles',
            'articlesRemainingCount',
            'collections',
            'collectionsRemainingCount'
        ]));
    }
}
