@extends('layouts.pnp')

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
@stop

@section('title')
{!! $collection->name !!} |
@parent
@stop

@section('description')
<meta name="description" content="{!! $collection->getShortTextAttribute($collection->text, 350) !!}">
@stop


@section('content')

<div class="webumeniaCarousel">

@if ($collection->hasHeaderImage())
<div class="header-image" style="background-image: url({!! $collection->getHeaderImage() !!}); color: {!! $collection->title_color !!}">
@else
<div class="header-image">
@endif
    <div class="outer-box">
        <div class="inner-box">
            <h1>{!! $collection->name !!}</h1>
            <p class="bottom-space">
                {{ trans_choice('general.artworks_counted', $collection->items()->count(), ['artworks_count' => $collection->items()->count()]) }} &nbsp;&middot;&nbsp;
                {!! $collection->user->name !!} &nbsp;&middot;&nbsp;
                {!! $collection->created_at->format('d. m. Y') !!}
            </p>
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
</div>

<section class="collection content-section">
    <div class="collection-body">
        <div class="container">
            <div class="row">
                <div class="col-md-8 col-md-offset-2 bottom-space description">
                       {!! $collection->text !!}
                </div>
            </div>
        </div>
    </div>
</section>

<section class="collections content-section">
    <div class="collections-body">
        <div class="container">
            <div class="row">
            	<div class="col-xs-12">
                    @if ($collection->items->count() == 0)
                        <p class="text-center">Momentálne žiadne diela</p>
                    @endif
                    <div id="iso">
                    @foreach ($collection->items as $i=>$item)
                        <div class="col-md-3 col-sm-4 col-xs-12 item">
                            <a href="{!! $item->getUrl(['collection' => $collection->id]) !!}">
                                <img src="{!! $item->getImagePath() !!}" class="img-responsive" alt="{!! $item->getTitleWithAuthors() !!} ">
                            </a>
                            <div class="item-title">
                                @if (!empty($item->iipimg_url))
                                    <div class="pull-right"><a href="{!! URL::to('dielo/' . $item->id . '/zoom') !!}" data-toggle="tooltip" data-placement="left" title="Zoom obrázku"><i class="fa fa-search-plus"></i></a></div>
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

 <div class="container text-center">
     <div class="fb-like" data-href="{!! $collection->getUrl() !!}" data-layout="button_count" data-action="like" data-show-faces="false" data-share="false"></div>
     &nbsp;
     <a href="https://twitter.com/share" class="twitter-share-button" data-count="true">Tweet</a>
     <script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');</script>
 </div>
@stop

@section('javascript')
{!! Html::script('js/slick.js') !!}

<script type="text/javascript">
    $(document).ready(function(){

        var $container = $('#iso');

        // az ked su obrazky nacitane aplikuj isotope
        $container.imagesLoaded(function () {
            spravGrid($container);
        });

        $( window ).resize(function() {
            spravGrid($container);
        });

        // carousel
        $('.artworks-preview').slick({
            dots: false,
            lazyLoad: 'progressive',
            infinite: false,
            speed: 300,
            slidesToShow: 1,
            slide: 'a',
            centerMode: false,
            variableWidth: true,
        });
    });
</script>
@stop
