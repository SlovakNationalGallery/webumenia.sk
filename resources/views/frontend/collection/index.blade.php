@extends('layouts.master')

@section('title')
{{ trans('kolekcie.title') }} |
@parent
@stop

@section('link')
@include('includes.pagination_links', ['paginator' => $collections])
@stop

@foreach ($collections as $i=>$collection)
@if ( ! $collection->hasTranslation(App::getLocale()) )
<section>
    <div class="container top-section">
        <div class="row">
            @include('includes.message_untranslated')
            @break
        </div>
    </div>
</section>
@endif
@endforeach

@section('content')

<section class="filters">
    <div class="container content-section">
        <div class="expandable">
            <div class="row">
                <div class="col-md-push-2 col-md-4 col-xs-6">
                    <filter-custom-select
                        name="author"
                        placeholder="{{ trans('kolekcie.filter.author') }}"
                        :options="{{ $filterOptions['author'] }}"
                    />
                </div>
                <div class="col-md-push-2 col-md-4 col-xs-6">
                    <filter-custom-select
                        name="category"
                        placeholder="{{ trans('kolekcie.filter.type') }}"
                        :options="{{ $filterOptions['type'] }}"
                    />
                </div>
            </div>
        </div>
    </div>
</section>
<section class="collections">
    <div class="container">
        <div class="row content-section">
            <div class="col-xs-6">
                @if (!empty($search))
                <h4 class="inline">{{ utrans('kolekcie.collections_found_collections') }} &bdquo;{!! $search !!}&ldquo;
                    (<span>{!! $collections->total() !!}</span>) </h4>
                @else
                <h4 class="inline">{!! $collections->total() !!} {{ trans('kolekcie.collections_collections') }}</h4>
                @endif
                @if ($collections->count() == 0)
                <p class="text-center">{{ utrans('kolekcie.collections_no_collections') }}</p>
                @endif
                {{--  @if (count(Request::all()) > 0)
                    <a class="btn btn-sm btn-default btn-outline  sans" href="{!! URL::to('kolekcie')!!}">zrušiť filtre <i class="icon-cross"></i></a>
                @endif --}}
            </div>
            {{-- <div class="col-xs-6 text-right">
                <div class="dropdown">
                  <a class="dropdown-toggle" type="button" id="dropdownSortBy" data-toggle="dropdown" aria-expanded="true">
                    {{ trans('general.sort_by') }} {!! trans(App\Collection::$sortable[$sort_by]) !!}
            <span class="caret"></span>
            </a>
            <ul class="dropdown-menu dropdown-menu-right dropdown-menu-sort" role="menu"
                aria-labelledby="dropdownSortBy">
                @foreach (App\Collection::$sortable as $sort=>$labelKey)
                @if ($sort != $sort_by)
                <li role="presentation"><a role="menuitem" tabindex="-1" href="#" rel="{!! $sort !!}">{!!
                        trans($labelKey) !!}</a></li>
                @endif
                @endforeach
            </ul>
        </div>
    </div> --}}
    <div class="col-xs-6 text-right">
        <filter-sort-by
            label="{{ trans('general.sort_by') }}"
            initial-value="{{ $sortBy }}"
            :options="{{ $sortingOptions }}"
        />
    </div>
    </div>
    <div class="kolekcie">
        @foreach ($collections as $i=>$collection)
        <div class="row collection">
            {{-- <div class="col-sm-2 col-xs-4">
            	<a href="{!! $collection->getUrl() !!}">
            		<img src="{!! $collection->getHeaderImage() !!}" class="img-responsive pentagon" alt="{{ $collection->name }}">
            	</a>
            </div> --}}
            <div class="col-sm-6 col-xs-12">
                <div class="collection-title">
                    <a href="{!! $collection->getUrl() !!}" class="underline">
                        <strong>{!! $collection->name !!}</strong>
                    </a>
                </div>
                <div class="collection-meta grey">
                    {{--  {!! $collection->author !!} &nbsp;&middot;&nbsp; --}}
                    {!! $collection->created_at->format('d. m. Y') !!} &nbsp;&middot;&nbsp;
                    {!! $collection->user->name !!} &nbsp;&middot;&nbsp;
                    {!! $collection->items->count() !!} {{ trans('kolekcie.collections_artworks') }}
                </div>
                <div>
                    {!! $collection->getShortTextAttribute($collection->text, 350) !!}
                </div>
            </div>
            <div class="clearfix visible-xs bottom-space"></div>
            <div class="col-sm-6">
                @include('components.artwork_carousel', [
                'slick_target' => "artworks-preview",
                'slick_variant' => "small",
                'items' => $collection->items->take(10),
                ])
            </div>
        </div>
        @endforeach
        <div class="row">
            <div class="col-sm-12 text-center">
                {!! $collections->appends(@Request::except('page'))->render() !!}
            </div>
        </div>
    </div>{{-- kolekcie --}}

    </div>
    </div>
</section>

@stop

@section('javascript')

{!! Html::script('js/components/artwork_carousel.js') !!}
{!! Html::script('js/jquery.dropdown-select.js') !!}

@stop
