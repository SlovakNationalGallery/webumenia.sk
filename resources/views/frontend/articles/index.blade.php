@extends('layouts.master')

@section('title')
{{ trans('articles.title') }} | @parent
@endsection

@section('content')

<section class="filters">
    <div class="container content-section">
        <div class="expandable">
            <div class="row">
                <div class="col-md-push-2 col-md-4 col-xs-6">
                    <filter-custom-select
                        name="author"
                        placeholder="{{ trans('articles.filter.author') }}"
                        :options="{{ $authorsOptions }}"
                    />
                </div>
                <div class="col-md-push-2 col-md-4 col-xs-6">
                    <filter-custom-select
                        name="category"
                        placeholder="{{ trans('articles.filter.category') }}"
                        :options="{{ $categoriesOptions }}"
                    />
                </div>
            </div>
        </div>
    </div>
</section>

<section class="bg-light-grey">
    <div class="container">
        <div class="row content-section">
            <div class="col-xs-6">
                <h4 class="inline">{{ $articles->total() }} {{ trans_choice('articles.count', $articles->total()) }}</h4>
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
    </div>
        @foreach ($articles as $article)
            <x-article_thumbnail :article="$article" />
        @endforeach
    <div class="row text-center">
            {{ $articles->links() }}
        </div>
</section>
@endsection
