@extends('layouts.master')

@section('og')
@if (!$collection->publish)
    <meta name="robots" content="noindex, nofollow">
@endif
<meta property="og:title" content="{!! $collection->name !!}" />
<meta property="og:description" content="{!! $collection->getShortTextAttribute($collection->text, 500) !!}" />
<meta property="og:type" content="website" />
<meta property="og:url" content="{!! Request::url() !!}" />
<meta property="og:image" content="{!! URL::to($collection->getHeaderImage()) !!}" />
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

<div class="webumeniaCarousel">

    <div class="gallery-cell header-image">

        @if ($collection->hasHeaderImage())
        <img src="{!! $collection->getHeaderImage() !!}" onerror="this.onerror=null;this.srcset=''">
        @endif

        <div class="outer-box" >
            <div class="inner-box" style="text-shadow:0px 1px 0px {!! $collection->title_shadow !!}; color: {!! $collection->title_color !!}">
                <h1>{!! $collection->name !!}</h1>
            <p class="bottom-space">
                @if ($collection->type)
                    <h2>{!! $collection->type !!}</h2>
                @endif
            </p>
            </div>
        </div>
    </div>


    <!-- share -->
    {{-- <div class="shareon-container">
        <div class="container text-right">
            <div class="fb-like" data-href="http://dvekrajiny.sng.sk/" data-layout="button_count" data-action="like" data-show-faces="false" data-share="false"></div>
            &nbsp;
            <a href="https://twitter.com/share" class="twitter-share-button" data-count="true">Tweet</a>
            <script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');</script>
    </div>
    </div> --}}
</div>

<section class="collection content-section pb-0">
    <div class="collection-body">
        <div class="container">
            <div class="row text-center mb-4">
               {!! $collection->user->name !!}&nbsp;&middot;&nbsp; 
                {!! $collection->published_date !!}
            </div>
            <div class="row">
                <div class="col-md-8 col-md-offset-2 bottom-space description">
                       {!! $collection->text !!}
                </div>
            </div>
        </div>
    </div>
</section>

<section>
    @include('components.share_buttons', [
        'title' => $collection->name,
        'url' => $collection->getUrl(),
        'img' => URL::to($collection->getHeaderImage()),
        'class' => 'text-center mb-5'
    ])
</section>

<section class="collections content-section">
    <div class="collections-body">
        <div class="container">
            <div class="row">
            	<div class="col-xs-12 isotope-wrapper">
                    @if ($collection->items->count() == 0)
                        <p class="text-center">Momentálne žiadne diela</p>
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
                                    <div class="pull-right"><a href="{{ route('item.zoom', ['id' => $item->id])  }}" data-toggle="tooltip" data-placement="left" title="Zoom obrázku"><i class="fa fa-search-plus"></i></a></div>
                                @endif
                                <a href="{!! $item->getUrl(['collection' => $collection->id]) !!}">
                                    <em>{!! implode(', ', $item->authors) !!}</em><br>
                                <strong>{!! $item->title !!}</strong><br> <em>{!! $item->getDatingFormated() !!}</em>

                                {{-- <span class="">{!! $item->gallery !!}</span> --}}
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
