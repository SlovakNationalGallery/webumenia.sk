@extends('layouts.master')

@section('og')
@if (!$collection->publish)
<meta name="robots" content="noindex, nofollow">
@endif
<meta property="og:title" content="{!! $collection->name !!}" />
<meta property="og:description" content="{!! $collection->getShortTextAttribute($collection->text, 500) !!}" />
<meta property="og:type" content="website" />
<meta property="og:url" content="{!! Request::url() !!}" />
<meta property="og:image" content="{!! URL::to($collection->header_image_src) !!}" />
@foreach ($collection->getContentImages() as $image )
<meta property="og:image" content="{!! $image !!}" />
@endforeach
@stop

@section('title')
{!! $collection->name !!} |
@parent
@stop

@section('description')
<meta name="description" content="{!! $collection->getShortTextAttribute($collection->text, 350) !!}">
@stop

@section('head-javascript')
{{-- For WEBUMENIA-1462 --}}
{!! Html::script('js/soundcloud.api.js') !!}
@stop

@section('content')

@if ( ! $collection->hasTranslation(App::getLocale()) )
<section>
    <div class="container top-section">
        <div class="row">
            @include('includes.message_untranslated')
        </div>
    </div>
</section>
@endif

@component('components.header_carousel', ['item' => $collection]))
    @slot('slideContent')
        <h1>{!! $collection->name !!}</h1>
        @if ($collection->type)
        {{-- keep in one line to prevent formatting failures --}}
        <h2><a href="{!! url_to( 'kolekcie', ['type' => $collection->type ]) !!}">{!! $collection->type !!}</a></h2>
        @endif
    @endslot
@endcomponent

<section class="collection content-header my-5">
    <div class="collection-header">
        <div class="container">
            <div class="row text-center">
                <div class="col-md-8 col-md-push-2">
                    <div class="row">
                        @if ($collection->items->count() != 0)
                        <div class="col-sm-6">
                            <div class="v-center min-h-3em">
                                <a href="{!! url_to( 'kolekcie', ['author' => $collection->user->name ]) !!}">
                                    {!! $collection->user->name !!}
                                </a>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="v-center min-h-3em">
                                <i class='fa fa-calendar-o mr-3'></i>
                                @date($collection->published_at)
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="v-center min-h-3em">
                                <span>
                                    {{trans('kolekcie.collections_items_count')}} <a
                                       href="#artworks">{{trans_choice('general.artworks_counted', $collection->items->count(), ['artworks_count' => $collection->items->count()])}}</a>
                                </span>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            @if ($collection->reading_time)
                            <div class="v-center min-h-3em">
                                <span>
                                    <i class='fa fa-clock-o mr-3'></i>
                                    {!! $collection->reading_time !!}
                                </span>
                            </div>
                            @endif
                        </div>
                        @else
                        <div class="col-md-4 col-sm-6 col-xs-12">
                            <div class="v-center min-h-3em">
                                <a href="{!! url_to( 'kolekcie', ['author' => $collection->user->name ]) !!}">
                                    {!! $collection->user->name !!}
                                </a>
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-6 col-xs-12">
                            <div class="v-center min-h-3em">
                                <i class='fa fa-calendar-o mr-3'></i>
                                @date($collection->published_at)
                            </div>
                        </div>
                        <div class="col-md-4 col-md-push-0 col-sm-6 col-sm-push-6 col-xs-12">
                            @if ($collection->reading_time)
                            <div class="v-center min-h-3em">
                                <span>
                                    <i class='fa fa-clock-o mr-3'></i>
                                    {!! $collection->reading_time !!}
                                </span>
                            </div>
                            @endif
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="collection my-5">
    <div class="collection-body">
        <div class="container">
            <div class="row">
                <div class="col-md-8 col-md-offset-2 bottom-space description long-text">
                    {!! $collection->text !!}
                </div>
            </div>
            <div class="row">
                <div class="col-md-8 col-md-push-2">
                    <div class="bg-blue p-4 p-md-5">
                        <div class="mx-md-2">
                            <livewire:newsletter-signup-form />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section>
    @include('components.share_buttons', [
        'title' => $collection->name,
        'url' => $collection->getUrl(),
        'img' => URL::to($collection->header_image_src),
        'class' => 'text-center mb-5'
    ])
</section>

<section class="collections" id="artworks">
    <div class="collections-body">
        <div class="container">
            <div class="row">
                @if ($collection->items->count() == 0)
                    <p class="text-center">{{ utrans('katalog.catalog_no_artworks') }}</p>
                @endif
                <div class="isotope">
                    @foreach ($collection->items as $item)
                        @include('components.artwork_grid_item', [
                            'item' => $item,
                            'url' => $item->getUrl(['collection' => $collection->id]),
                            'class_names' => 'col-md-3 col-sm-4 col-xs-12',
                        ])
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>
@stop

@section('javascript')
{!! Html::script('js/components/share_buttons.js') !!}

<script type="text/javascript">
    $('.isotope').isotope({
        itemSelector: '.item',
        layoutMode: 'masonry'
    });
</script>
@stop