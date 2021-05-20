<?php

namespace App\Http\Controllers;

use App\Article;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;

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
    public function store(Request $request)
    {
        $request->validate(Article::getValidationRules());

        $article = new Article;

        // store translatable attributes
        foreach (\Config::get('translatable.locales') as $i => $locale) {
            if (hasTranslationValue($locale, $article->translatedAttributes)){
                foreach ($article->translatedAttributes as $attribute) {
                    $article->translateOrNew($locale)->$attribute = $request->input($locale . '.' . $attribute);
                }
            }
        }

        $article->author = $request->input('author');
        $article->slug = $request->input('slug');
        if ($request->has('category_id')) {
            $article->category_id = $request->input('category_id');
        }
        $article->publish = $request->input('publish', false);
        $article->promote = $request->input('promote', false);
        $article->edu_media_types = $request->input('edu_media_types', null);
        $article->edu_target_age_groups = $request->input('edu_target_age_groups', null);
        $article->edu_suitable_for_home = $request->input('edu_suitable_for_home', false);
        $article->edu_keywords = $request->input('edu_keywords', null);

        if ($request->has('title_color')) {
            $article->title_color = $request->input('title_color');
        }
        if ($request->has('title_shadow')) {
            $article->title_shadow = $request->input('title_shadow');
        }
        
        $article->save();

        if ($request->hasFile('main_image')) {
            $this->uploadMainImage($article, $request);
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
    public function update(Article $article, Request $request)
    {
        $request->validate(Article::getValidationRules());

        // update translatable attributes
        foreach (\Config::get('translatable.locales') as $i => $locale) {
            if (hasTranslationValue($locale, $article->translatedAttributes)){
                foreach ($article->translatedAttributes as $attribute) {
                    $article->translateOrNew($locale)->$attribute = $request->input($locale . '.' . $attribute);
                }
            }
        }

        $article->author = $request->input('author');
        $article->slug = $request->input('slug');
        $article->category_id = $request->input('category_id', null);
        $article->publish = $request->input('publish', false);
        $article->promote = $request->input('promote', false);
        $article->edu_media_types = $request->input('edu_media_types', null);
        $article->edu_target_age_groups = $request->input('edu_target_age_groups', null);
        $article->edu_suitable_for_home = $request->input('edu_suitable_for_home', false);
        $article->edu_keywords = $request->input('edu_keywords', null);

        if ($request->has('title_color')) {
            $article->title_color = $request->input('title_color');
        }
        if ($request->has('title_shadow')) {
            $article->title_shadow = $request->input('title_shadow');
        }

        $article->save();

        if ($request->hasFile('main_image')) {
            $this->uploadMainImage($article, $request);
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


    private function uploadMainImage($article, $request)
    {
        $main_image = $request->file('main_image');
        $article->main_image = $article->uploadHeaderImage($main_image);
        $article->save();
    }

    private function buildSelectOptions()
    {
        return [
            'eduMediaTypesOptions' => collect(Article::$eduMediaTypes)
                ->reduce(function ($options, $value) {
                    $options[$value] = trans("edu.media_type.$value");
                    return $options;
                 }),
            'eduAgeGroupsOptions' => collect(Article::$eduAgeGroups)
                ->reduce(function ($options, $value) {
                    $options[$value] = trans("edu.age_group.$value");
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
