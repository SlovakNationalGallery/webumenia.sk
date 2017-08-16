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

<section class="intro light-grey content-section">
    <div class="intro-body">
        <div class="container">
            <p class="lead tagline text-center">
                {{ utrans('intro.definition_start') }} <strong><a href="/katalog">{!! formatNum(App\Item::amount()) !!}</a></strong> {{ trans('intro.definition_end') }}<br>
                {!! App\Subtitle::random() !!}</p>
        </div>
    </div>
</section>

@foreach ($articles as $i=>$article)
    @if ( ! $article->hasTranslation(App::getLocale()) )
        <section>
            <div class="container content-section">
                <div class="row">
                    @include('includes.message_untranslated')
                    @break
                </div>
            </div>
        </section>
    @endif
@endforeach

<section class="articles content-section">
    <div class="articles-body">
        <div class="container">
            <div class="row">
            	@foreach ($articles as $i=>$article)
	                <div class="col-sm-6 col-xs-12 bottom-space">
	                	<a href="{!! $article->getUrl() !!}" class="featured-article">
	                		<img src="{!! $article->getThumbnailImage() !!}" class="img-responsive" alt="{!! $article->title !!}">
	                	</a>
                        <a href="{!! $article->getUrl() !!}"><h4 class="title">
                            @if ($article->category)
                                {!! $article->category->name !!}:
                            @endif
                            {!! $article->title !!}
                        </h4></a>
                        <p class="attributes">{!! $article->getShortTextAttribute($article->summary, 250) !!}
                        (<a href="{!! $article->getUrl() !!}">{{ trans('general.more') }}</a>)
                        </p>
                        <p class="meta">{!!$article->published_date!!} / {!!$article->author!!}</p>
	                    
	                </div>
                    @if ($i%2 == 1)
                        <div class="clearfix"></div>
                    @endif
            	@endforeach
            </div>
        </div>
    </div>
</section>

<div class="container text-center top-space">
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
