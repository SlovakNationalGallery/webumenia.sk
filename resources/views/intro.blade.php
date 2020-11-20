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
        <div class="gallery-cell header-image">
            <img src="{!! $slide->header_image_src !!}" srcset="{!! $slide->header_image_srcet !!}" onerror="this.onerror=null;this.srcset=''">
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

<div class="container">
        <div class="slick-pagination"></div>
</div>

<section class="intro bg-light-grey content-section">
    <div class="intro-body">
        <div class="container">
            <p class="lead tagline text-center">
                {{ utrans('intro.definition_start') }} <strong><a href="/katalog">{!! formatNum($itemCount) !!}</a></strong> {{ trans('intro.definition_end') }}<br>
                {!! $subtitle !!}</p>
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
                        @include('components.article_thumbnail', [
                            'article' => $article
                        ])
	                </div>
                    @if ($i%2 == 1)
                        <div class="clearfix"></div>
                    @endif
            	@endforeach
            </div>
        </div>
    </div>
</section>

@stop

@section('javascript')

<script type="text/javascript">
$(document).ready(function(){
    var $carousel = $('.webumeniaCarousel').slick({
        infinite: true,
        slidesToShow: 1,
        variableWidth: false,
        dots: true,
        centerMode: true,
        centerPadding: '5vw',
        appendDots: $('.slick-pagination')[0]
    });
});

</script>
@stop
