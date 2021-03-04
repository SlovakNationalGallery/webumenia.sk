@extends('layouts.master')

@section('title')
{{ trans('clanky.title') }} | @parent
@endsection

@section('content')

<section class="filters">
    <div class="container content-section">
        <div class="expandable">
            <div class="row">
                <div class="col-md-push-2 col-md-4 col-xs-6 bottom-space">
                    <filter-custom-select 
                        name="category"
                        placeholder="{{ trans('clanky.filter.category') }}" 
                        :options="{{ $categoriesOptions }}"
                    />
                </div>
                <div class="col-md-push-2 col-md-4 col-xs-6 bottom-space">
                    <filter-custom-select 
                        name="author"
                        placeholder="{{ trans('clanky.filter.author') }}" 
                        :options="{{ $authorsOptions }}"
                    />
                </div>
            </div>
        </div>
    </div>
</section>

<section class="articles bg-light-grey content-section">
    <div class="articles-body">
        <div class="container">
            <div class="row">
            	@foreach ($articles as $i=>$article)
	                <div class="col-sm-6 col-xs-12 bottom-space">
                        @include('components.article_thumbnail', [
                            'article' => $article
                        ])
	                </div>
                    @if ($i % 2 == 1)
                        <div class="clearfix"></div>
                    @endif
            	@endforeach
            </div>
        </div>
    </div>
</section>
@endsection
