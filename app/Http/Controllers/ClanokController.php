<?php

namespace App\Http\Controllers;

use App\Article;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Response;

class ClanokController extends Controller
{
    public function getIndex(Request $request)
    {
        $articles = Article::published()->with('category');

        // Filtering
        if ($request->has('category')) {
            $articles = $articles->whereHas('category', function (Builder $query) use ($request) {
                $query->where('name', $request->input('category'));
            });
        }

        if ($request->has('author')) {
            $articles = $articles->where('author', 'LIKE', $request->input('author'));
        }

        // Sorting
        $sortBy = $request->input('sort_by', 'date_desc');

        if ($sortBy === 'date_desc') {
            $articles = $articles->orderBy('published_date', 'desc');
        }

        if ($sortBy === 'date_asc') {
            $articles = $articles->orderBy('published_date', 'asc');
        }

        $articles = $articles->get();

        // Options for filters
        $categoriesOptions = $articles
            ->countBy('category.name')
            ->map(function ($count, $category) use ($request) {
                return [
                    'value' => $category,
                    'text' => "$category ($count)",
                    'selected' => $category === $request->input('category'),
                ];
            });

        $authorsOptions = $articles
            ->countBy('author')
            ->map(function ($count, $author) use ($request) {
                return [
                    'value' => $author,
                    'text' => "$author ($count)",
                    'selected' => $author === $request->input('author'),
                ];
            });

        $sortingOptions = collect([
            [ 'value' => 'date_asc', 'text' => trans('clanky.filter.sort_by.date_asc') ],
            [ 'value' => 'date_desc', 'text' => trans('clanky.filter.sort_by.date_desc') ],
        ]);

        return view('frontend.articles.index', compact(
            'articles',
            'categoriesOptions',
            'authorsOptions',
            'sortingOptions',
            'sortBy'
        ));
    }

    public function getSuggestions()
    {
        $q = (Input::has('search')) ? str_to_alphanumeric(Input::get('search')) : 'null';

        $result = Article::published()->whereTranslationLike('title', '%'.$q.'%')->limit(5)->get();

        $data = array();
        $data['results'] = array();
        $data['count'] = 0;

        // $data['items'] = array();
        foreach ($result as $key => $hit) {

            $data['count']++;
            $params = array(
                'title' => $hit->title_with_category,
                'author' => $hit->author,
                'url' => $hit->getUrl(),
                'image' => $hit->getResizedImage(70),
            );
            $data['results'][] = array_merge($params) ;
        }

        return Response::json($data);
    }

    public function getDetail($slug)
    {
        // dd($slug);
        $article = Article::where('slug', '=', $slug)->firstOrFail();
        if (empty($article)) {
            abort(404);
        }
        $article->view_count += 1;
        $article->save();

        return view('frontend.articles.show', array('article'=>$article));

    }
}
