@extends('layouts.master')

@section('title')
@parent

@stop

@section('link')
<script type="application/ld+json">
{
    "@context": "http://schema.org",
    "@type": "WebSite",
    "logo": " {!! asset('images/logo.png') !!}",
    "url": "{!! URL::to('') !!}",
    "sameAs" : [
        "https://www.facebook.com/webumenia.sk",
        "https://twitter.com/webumeniask",
        "http://webumenia.tumblr.com/",
        "https://vimeo.com/webumeniask",
        "https://sk.pinterest.com/webumeniask/",
        "https://instagram.com/web_umenia/"
    ],
    "potentialAction": {
      "@type": "SearchAction",
      "target": "{!! URL::to('katalog') !!}/?search={query}",
      "query-input": "required name=query"
    }
}
</script>
@stop

@section('content')

<div class="webumeniaCarousel">
    @foreach ($slides as $slide)
        <div class="gallery-cell"  style="background-image: url({!! $slide->image_path !!})">
            <a href="{!! $slide->url !!}" class="outer-box" data-id="{!! $slide->id !!}" >
                <div class="inner-box">
                    <h1>{!! $slide->title !!}</h1>
                    @if ($slide->subtitle)
                        <h2>{!! $slide->subtitle !!}</h2>
                    @endif
                </div>
            </a>
        </div>
    @endforeach
</div>

<section class="collections content-section top-space">
    <div class="collections-body">
        <div class="container">
            <div class="row">
                <div class="col-xs-12">
                    @if ($items->count() == 0)
                        <p class="text-center">{{ utrans('katalog.catalog_no_artworks') }}</p>
                    @endif
                    <div id="iso">
                    @foreach ($items as $i=>$item)
                        <div class="col-md-3 col-sm-4 col-xs-12 item">
                            <a href="{!! $item->getUrl() !!}">
                                <img src="{!! $item->getImagePath() !!}" class="img-responsive" alt="{!! $item->getTitleWithAuthors() !!} ">
                            </a>
                            <div class="item-title">
                                @if (!empty($item->iipimg_url))
                                    <div class="pull-right"><a href="{!! URL::to('dielo/' . $item->id . '/zoom') !!}" data-toggle="tooltip" data-placement="left" title="Zoom obrázku"><i class="fa fa-search-plus"></i></a></div>
                                @endif
                                <a href="{!! $item->getUrl() !!}">
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

<div class="container text-center">
    <div class="fb-like" data-href="{!! Config::get('app.url') !!}" data-layout="button_count" data-action="like" data-show-faces="false" data-share="false"></div>
    &nbsp;
    <a href="https://twitter.com/share" class="twitter-share-button" data-count="true">Tweet</a>
    <script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');</script>
</div>

@stop

@section('javascript')

<script type="text/javascript">
$(document).ready(function(){
    var $carousel = $('.webumeniaCarousel').flickity({
      wrapAround: true,
      percentPosition: false,
      autoPlay: 4000, // 4 seconds
      // setGallerySize: false,
      // resize: false,
      arrowShape: 'M42.7 15.5c1.1 0 2.1 0.4 2.9 1.2s1.2 1.8 1.2 2.9c0 1.2-0.4 2.1-1.2 2.9L23.7 44.5h64.5c1.1 0 2.1 0.4 2.9 1.2 0.8 0.8 1.2 1.8 1.2 2.9s-0.4 2.1-1.2 2.9c-0.8 0.8-1.8 1.2-2.9 1.2h-64.5l21.9 21.9c0.8 0.8 1.2 1.8 1.2 2.9 0 1.1-0.4 2.1-1.2 2.9 -0.8 0.8-1.8 1.2-2.9 1.2 -1.2 0-2.1-0.4-2.9-1.2L10.8 51.6c-0.8-0.8-1.2-1.8-1.2-2.9 0-1.1 0.4-2.1 1.2-2.9l29-29c0.8-0.8 1.8-1.2 2.9-1.2V15.5z'
    });
    $carousel.children('.flickity-page-dots').css('left',  parseInt($('.flickity-slider').css('transform').split(',')[4]) );

    $carousel.on( 'staticClick', function( event, pointer, cellElement, cellIndex ) {
        event.preventDefault();
        var $link = $( cellElement ).find('a');
        var url = $link.attr('href');
        var id = $link.data('id');
        $.get('/slideClicked', {'id': id});

    });

    // grid
    var $container = $('#iso');

    // az ked su obrazky nacitane aplikuj isotope
    $container.imagesLoaded(function () {
        spravGrid($container);
    });

    $( window ).resize(function() {
        spravGrid($container);
    });

});

$(window).on('resize',function() {
    if ( $( ".flickity-slider" ).length ) {
        setTimeout(function(){
          $('.webumeniaCarousel').children('.flickity-page-dots').css('left',  parseInt($('.flickity-slider').css('transform').split(',')[4]) );
        }, 200);
    }
});



</script>
@stop
