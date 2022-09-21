<?php

namespace App\Http\Controllers;

use App\Article;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request as HttpRequest;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Blade;

class ClanokController extends Controller
{
    public function getIndex(HttpRequest $request)
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

        $unpaginatedArticles = (clone $articles)
            ->with(['category'])
            ->get();

        $articles = $articles
            ->with(['translations', 'category'])
            ->paginate(12)
            ->withQueryString();

        $categoriesOptions = $this->buildSelectOptions($unpaginatedArticles, 'category.name', $request->input('category'));
        $authorsOptions = $this->buildSelectOptions($unpaginatedArticles, 'author', $request->input('author'));

        $sortingOptions = collect([
            [ 'value' => 'date_asc', 'text' => trans('articles.filter.sort_by.date_asc') ],
            [ 'value' => 'date_desc', 'text' => trans('articles.filter.sort_by.date_desc') ],
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
        $q = (Request::has('search')) ? str_to_alphanumeric(Request::input('search')) : 'null';

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

    public static function replaceTags($content) {
        $squareTags = array("[x-article_thumbnail", "[x-collection_thumbnail", "/]");
        $angleTags = array("<x-article_thumbnail", "<x-collection_thumbnail" ,"/>");
        return str_replace($squareTags, $angleTags, $content);
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
        $article->content = Blade::render($this->replaceTags("$article->content"));

        return view('frontend.articles.show', array('article'=>$article));

    }

    private function buildSelectOptions(Collection $collection, string $countBy, $selectedValue = null)
    {
        return $collection
            ->countBy($countBy)
            ->sort()->reverse() // sort by count, descending
            ->map(function ($count, $value) use ($selectedValue) {
                return [
                    'value' => $value,
                    'text' => "$value ($count)",
                    'selected' => $value === $selectedValue,
                ];
            })
            ->values();
    }
}
