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
<meta property="og:site_name" content="web umenia" />
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
        <p class="bottom-space">
            @if ($collection->type)
            <a href="{!! url_to( 'kolekcie', ['type' => $collection->type ]) !!}">
            <h2>{!! $collection->type !!}</h2>
            </a>
            @endif
        </p>
    @endslot
@endcomponent

<section class="collection content-header">
    <div class="collection-header">
        <div class="container">
            <div class="row text-center mb-4">
                <div class="col-md-8 col-md-push-2">
                    <div class="row">
                        <div class="col-md-4 col-sm-6 col-xs-12">
                            <a href="{!! url_to( 'kolekcie', ['author' => $collection->user->name ]) !!}">
                                <div class="v-center">
                                    <i class="fa fa-user-circle-o mr-3" aria-hidden="true"></i>
                                    {!!   $collection->user->name !!}
                                </div>
                            </a>
                        </div>
                        <div class="col-md-4 col-sm-6 col-xs-12">
                            <div class="v-center">
                                <i class='fa fa-calendar-o mr-3'></i>
                                @date($collection->published_at)
                            </div>
                        </div>
            
                        <div class="col-md-4 col-sm-6 col-md-push-0 col-sm-push-6 col-xs-12">
                            @if ($collection->reading_time)
                            <div class="v-center">
                            <i class='fa fa-clock-o mr-3'></i>
                            {{ utrans('general.reading_time') }}: {!! $collection->reading_time !!}
                            @endif
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-6 col-md-pull-0 col-sm-pull-6 col-xs-12">
                            @if ($collection->items->count() != 0)
                            <div class="v-center">
                                <i class='fa fa-picture-o mr-3'></i> 
                                {{trans_choice('general.artworks_counted', $collection->items->count(), ['artworks_count' => $collection->items->count()])}}
                            </div>
                            @endif
                        </div>
                        <div class="col-md-4 col-sm-6 col-xs-12">
                            <div class="v-center">
                                <a href="#artworks">
                                    <button class="btn btn-default">prejsť na diela</button>
                                </a>
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-6 col-xs-12">
                            <div class="v-center">
                            @include('components.share_buttons', [
                            'title' => $collection->name,
                            'url' => $collection->getUrl(),
                            'img' => $collection->header_image_src,
                            'class' => 'text-center p-0'
                            ])
                            </div>
                        </div>
                        </div>
                </div>
            </div>
        </div>
    </div>
</section>
<section class="collection content-section pb-0">
    <div class="collection-body">
        <div class="container">
            <div class="row">
                <div class="col-md-8 col-md-offset-2 bottom-space description long-text">
                    {!! $collection->text !!}
                </div>
            </div>
        </div>
    </div>
</section>

<section class="collections content-section">
    <div class="collections-body">
        <div class="container">
            <div class="row" id="artworks">
                <div class="col-xs-12 isotope-wrapper">
                    @if ($collection->items->count() == 0)
                    <p class="text-center">{{utrans("katalog.catalog_no_artworks")}}</p>
                    @endif
                    <div id="iso">
                        @foreach ($collection->items as $i=>$item)
                        <div class="col-md-3 col-sm-4 col-xs-12 item">
                            @include('components.item_image_responsive', [
                            'item' => $item,
                            'url' => $item->getUrl(['collection' => $collection->id]) ,
                            'limitRatio' => 3
                            ])
                            </a>
                            <div class="item-title">
                                @if (!$item->images->isEmpty())
                                <div class="pull-right"><a href="{{ route('item.zoom', ['id' => $item->id])  }}"
                                       data-toggle="tooltip" data-placement="left" title="Zoom obrázku"><i
                                           class="fa fa-search-plus"></i></a></div>
                                @endif
                                <a href="{!! $item->getUrl(['collection' => $collection->id]) !!}">
                                    <em>{!! implode(', ', $item->authors) !!}</em><br>
                                    <strong>{!! $item->title !!}</strong><br> <em>{!! $item->getDatingFormated()
                                        !!}</em>
                                </a>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    <div class="col-sm-12 text-center">
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
{{--
<section class="map content-section">
    <div class="map-body">
        <div class="container">
            <div class="row">
                <div class="col-xs-12">
                    <h3>Diela na mape: </h3>
                </div>
                <div id="big-map"></div>
            </div>
        </div>
    </div>
</section>
 --}}
@stop

@section('javascript')
{!! Html::script('js/slick.js') !!}
{!! Html::script('js/components/share_buttons.js') !!}

<script type="text/javascript">
    // start with isotype even before document is ready
    $('.isotope-wrapper').each(function(){
        var $container = $('#iso', this);
        spravGrid($container);
    });

    $(document).ready(function(){

        $( window ).resize(function() {
            var $container = $('#iso');
            spravGrid($container);
        });
    });
</script>
@stop