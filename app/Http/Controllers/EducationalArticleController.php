<?php

namespace App\Http\Controllers;

use App\Article;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class EducationalArticleController extends Controller
{
    public function index(Request $request)
    {
        $articles = Article::educational()->published()->with('category');

        // Filtering
        $request->whenFilled('category', function ($category) use ($articles) {
            $articles = $articles->whereHas('category', function (Builder $query) use ($category) {
                $query->where('name', $category);
            });
        });

        $request->whenFilled('media_type', function ($mediaType) use ($articles) {
            $articles = $articles->whereJsonContains('edu_media_types', $mediaType);
        });

        $request->whenFilled('age_group', function ($ageGroup) use ($articles) {
            $articles = $articles->whereJsonContains('edu_target_age_groups', $ageGroup);
        });

        $request->whenFilled('keyword', function ($keyword) use ($articles) {
            $articles = $articles->whereJsonContains('edu_keywords', $keyword);
        });

        $request->whenFilled('parents', function () use ($articles) {
            $articles = $articles->where('edu_suitable_for_home', true);
        });

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
            ->paginate(12);

        $categoriesOptions = $this->buildSelectOptions(
            $unpaginatedArticles->countBy('category.name'), 
            $request->input('category')
        );

        $mediaTypesOptions = $this->buildSelectOptions(
            $unpaginatedArticles->pluck('edu_media_types')->flatten()->countBy(),
            $request->input('media_type'),
            'edu.media_type',
        );

        $targetGroupsOptions = $this->buildSelectOptions(
            $unpaginatedArticles->pluck('edu_target_age_groups')->flatten()->countBy(),
            $request->input('age_group'),
            'edu.age_group',
        );

        $keywordsOptions = $this->buildSelectOptions(
            $unpaginatedArticles->pluck('edu_keywords')->flatten()->countBy(),
            $request->input('keyword')
        );

        $sortingOptions = collect([
            [ 'value' => 'date_asc', 'text' => trans('articles.filter.sort_by.date_asc') ],
            [ 'value' => 'date_desc', 'text' => trans('articles.filter.sort_by.date_desc') ],
        ]);

        return view('frontend.educational-articles.index', compact(
            'articles',
            'categoriesOptions',
            'mediaTypesOptions',
            'targetGroupsOptions',
            'keywordsOptions',
            'sortingOptions',
            'sortBy'
        ));
    }

    public function show(Article $article)
    {
        return view('frontend.articles.show', compact('article'));
    }

    private function buildSelectOptions(Collection $optionsWithcounts, $selectedValue = null, $translationPath = null)
    {
        return $optionsWithcounts
            ->sort()->reverse() // Sort by counts in reverse
            ->map(function ($count, $value) use ($selectedValue, $translationPath) {
                $label = $translationPath ? trans("$translationPath.$value") : $value;
                
                return [
                    'value' => $value,
                    'text' => "$label ($count)",
                    'selected' => $value === $selectedValue,
                ];
            });
    }
}
