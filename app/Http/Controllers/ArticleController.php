<?php

namespace App\Http\Controllers;

use App\Article;
use Illuminate\Http\Request as HttpRequest;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Request;

class ArticleController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $articles = Article::orderBy('created_at', 'desc')->paginate(20);
        return view('articles.index')->with('articles', $articles);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        return view('articles.form', $this->buildSelectOptions());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(HttpRequest $request)
    {
        $request->validate(Article::getValidationRules());

        $article = new Article;

        // store translatable attributes
        foreach (\Config::get('translatable.locales') as $i => $locale) {
            if (hasTranslationValue($locale, $article->translatedAttributes)){
                foreach ($article->translatedAttributes as $attribute) {
                    $article->translateOrNew($locale)->$attribute = Request::input($locale . '.' . $attribute);
                }
            }
        }

        $article->author = Request::input('author');
        $article->slug = Request::input('slug');
        if (Request::has('category_id')) {
            $article->category_id = Request::input('category_id');
        }
        $article->publish = Request::input('publish', false);
        $article->promote = Request::input('promote', false);
        $article->edu_media_types = Request::input('edu_media_types', null);
        $article->edu_target_age_groups = Request::input('edu_target_age_groups', null);
        $article->edu_suitable_for_home = Request::input('edu_suitable_for_home', false);
        $article->edu_keywords = Request::input('edu_keywords', null);

        if (Request::has('title_color')) {
            $article->title_color = Request::input('title_color');
        }
        if (Request::has('title_shadow')) {
            $article->title_shadow = Request::input('title_shadow');
        }
        
        $article->save();

        if (Request::hasFile('main_image')) {
            $this->uploadMainImage($article);
        }

        return Redirect::route('article.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        $article = Article::find($id);
        return view('articles.show')->with('article', $article);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        $article = Article::find($id);

        if (is_null($article)) {
            return Redirect::route('article.index');
        }

        return view('articles.form', array_merge(
            $this->buildSelectOptions(),
            ['article' => $article]
        ));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update(Article $article, HttpRequest $request)
    {
        $request->validate(Article::getValidationRules());

        // update translatable attributes
        foreach (\Config::get('translatable.locales') as $i => $locale) {
            if (hasTranslationValue($locale, $article->translatedAttributes)){
                foreach ($article->translatedAttributes as $attribute) {
                    $article->translateOrNew($locale)->$attribute = Request::input($locale . '.' . $attribute);
                }
            }
        }

        $article->author = Request::input('author');
        $article->slug = Request::input('slug');
        $article->category_id = Request::input('category_id', null);
        $article->publish = Request::input('publish', false);
        $article->promote = Request::input('promote', false);
        $article->edu_media_types = Request::input('edu_media_types', null);
        $article->edu_target_age_groups = Request::input('edu_target_age_groups', null);
        $article->edu_suitable_for_home = Request::input('edu_suitable_for_home', false);
        $article->edu_keywords = Request::input('edu_keywords', null);

        if (Request::has('title_color')) {
            $article->title_color = Request::input('title_color');
        }
        if (Request::has('title_shadow')) {
            $article->title_shadow = Request::input('title_shadow');
        }

        $article->save();

        if (Request::hasFile('main_image')) {
            $this->uploadMainImage($article);
        }

        Session::flash('message', 'Článok <code>' . $article->title . '</code> bol upravený');
        return Redirect::route('article.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        Article::find($id)->delete();
        return Redirect::route('article.index')->with('message', 'Článok bol zmazaný');;
    }


    private function uploadMainImage($article)
    {
        $main_image = Request::file('main_image');
        $article->main_image = $article->uploadHeaderImage($main_image);
        $article->save();
    }

    private function buildSelectOptions()
    {
        return [
            'eduMediaTypesOptions' => collect(Article::$eduMediaTypes)
                ->reduce(function ($options, $value) {
                    $options[$value] = $value;
                    return $options;
                 }),
            'eduAgeGroupsOptions' => collect(Article::$eduAgeGroups)
                ->reduce(function ($options, $value) {
                    $options[$value] = $value;
                    return $options;
                 }),
            'eduKeywordsOptions' => Article::all()
                ->pluck('edu_keywords')
                ->filter()
                ->flatten()
                ->unique()
                ->sort()
                ->reduce(function ($options, $value) {
                    $options[$value] = $value;
                    return $options;
                 }) ?? collect(),
        ];
    }
}
