@extends('layouts.master')

@section('title')
{{ trans('master.education') }} | @parent
@endsection

@section('content')

<section class="filters">
    <div class="container content-section">
        <div class="expandable">
            <div class="row mb-4">
                <div class="col-md-push-2 col-md-4 col-xs-6">
                    <filter-custom-select
                        name="category"
                        placeholder="{{ trans('articles.filter.category') }}"
                        :options="{{ $categoriesOptions }}"
                    ></filter-custom-select>
                </div>
                <div class="col-md-push-2 col-md-4 col-xs-6">
                    <filter-custom-select
                        name="media_type"
                        placeholder="{{ trans('edu.filter.media_type') }}"
                        :options="{{ $mediaTypesOptions }}"
                    ></filter-custom-select>
                </div>
            </div>
            <div class="row">
                <div class="col-md-push-2 col-md-4 col-xs-6">
                    <filter-custom-select
                        name="age_group"
                        placeholder="{{ trans('edu.filter.target_group') }}"
                        :options="{{ $targetGroupsOptions }}"
                    ></filter-custom-select>

                    <filter-checkbox
                        class="mt-2"
                        name="parents"
                        label="{{ trans('edu.filter.suitable_for_home') }}"
                        :initial-checked="{{ json_encode(request()->has('parents')) }}"
                    ></filter-checkbox>
                </div>
                <div class="col-md-push-2 col-md-4 col-xs-6">
                    <filter-custom-select
                        name="keyword"
                        placeholder="{{ trans('edu.filter.keywords') }}"
                        :options="{{ $keywordsOptions }}"
                    />
                </div>
            </div>
        </div>
    </div>
</section>

<section class="articles bg-light-grey">
    <div class="container">
        <div class="row content-section">
            <div class="col-xs-6">
                <h4 class="inline">{{ $articles->count() }} {{ trans_choice('articles.count', $articles->count()) }}</h4>
            </div>
            <div class="col-xs-6 text-right">
                <filter-sort-by
                    label="{{ trans('general.sort_by') }}"
                    initial-value="{{ $sortBy }}"
                    :options="{{ $sortingOptions }}"
                />
            </div>

            @if($articles->isEmpty())
            <div class="col-xs-12">
                <p class="text-center">{{ utrans('articles.no_results') }}</p>
            </div>
            @endif
        </div>
        <div class="row content-section">
            @foreach ($articles as $i => $article)
                <div class="col-sm-6 col-xs-12 bottom-space">
                    @include('components.article_thumbnail', [
                        'article' => $article,
                        'url' => route('frontend.educational-article.show', $article->slug),
                    ])
                </div>
                @if ($i % 2 == 1)
                    <div class="clearfix"></div>
                @endif
            @endforeach
        </div>
    </div>
</section>
@endsection
