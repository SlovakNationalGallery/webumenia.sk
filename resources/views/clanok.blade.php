@extends('layouts.master')

@section('og')
@if (!$article->publish)
    <meta name="robots" content="noindex, nofollow">
@endif
<meta property="og:title" content="{!! $article->title !!}" />
<meta property="og:description" content="{!! strip_tags($article->summary) !!}" />
<meta property="og:type" content="website" />
<meta property="og:url" content="{!! Request::url() !!}" />
<meta property="og:image" content="{!! URL::to('images/clanky/' . $article->getHeaderImage()) !!}" />
<meta property="og:site_name" content="Web umenia" />
@stop

@section('title')
{!! $article->title !!} | 
@parent
@stop

@section('description')
<meta name="description" content="{!! $article->shortText !!}">
@stop


@section('content')

@if ( ! $article->hasTranslation(App::getLocale()) )
    <section>
        <div class="container top-section">
            <div class="row">
                @include('includes.message_untranslated')
            </div>
        </div>
    </section>
@endif

<div class="webumeniaCarousel">
<div class="header-image" style="background-image: url({!! $article->getHeaderImage() !!}); text-shadow:0px 1px 0px {!! $article->title_shadow !!}; color: {!! $article->title_color !!}">
    <div class="outer-box">
        <div class="inner-box">
            @if ($article->category)
                <h2>{!! $article->category->name !!}</h2>
            @endif
            <h1>{!! $article->title !!}</h1>
            <p class="bottom-space">
                <a href="{!! url_to( 'clanky', ['author' => $article->author ]) !!}" style="color: {!! $article->title_color !!}">{!! $article->author !!}</a> &nbsp;&middot;&nbsp; 
                {!! $article->published_date !!}
            </p>
        </div>
    </div>
</div>
</div>

{{-- <section class="article summary light-grey content-section">
        <div class="container">
            <div class="row">
                <div class="col-md-8 col-md-offset-2 ">
                    {!! $article->summary !!}
                </div>
            </div>
        </div>
</section> --}}
<section class="article content-section">
    <div class="article-body">
        <div class="container">
            <div class="row">
                <div class="col-md-4 lead attributes">
                    {!! $article->summary !!}
                </div>
                <div class="col-md-6 attributes">
                    {!! $article->content !!}
                </div>
            </div>
        </div>
    </div>
</section>

<!-- share -->
{{-- <div class="shareon-container"> --}}
<div class="container text-center share-icons">
        <a href='https://www.facebook.com/dialog/share?&appId=1429726730641216&version=v2.0&display=popup&href=https%3A%2F%2Fdevelopers.facebook.com%2Fdocs%2F&redirect_uri=https%3A%2F%2Fdevelopers.facebook.com%2Ftools%2Fexplorer'
           target='_blank' class="no-border" data-toggle="tooltip" title="{{ trans('general.share_facebook') }}">
            <i class='fa fa-facebook fa-lg'></i>
        </a>
    
        <a href='https://twitter.com/intent/tweet?text={!! $article->title !!}&url={!! $article->getUrl() !!}'
           target='_blank' class="no-border" data-toggle="tooltip" title='{{ trans('general.share_twitter') }}'>
            <i class='fa fa-twitter fa-lg'></i>
        </a>
    
        <a href='//www.pinterest.com/pin/create/button/?url={!! $article->getUrl() !!}' class='pin-it-button no-border'
           count-layout='none' target='_blank' data-toggle="tooltip" title="{{ trans('general.share_pinterest') }}">
            <i class='sng-icon'>pinterest</i>
        </a>
        <a href='mailto:?subject={!! $article->title !!}, {{trans('informacie.info_gallery_SNG')}}&body={!!$article->getUrl()!!}'
           style="font-size:0.9em" target='_blank' class="no-border" data-toggle="tooltip"
           title="{{ trans('general.share_mail') }}">
            <i class='fa fa-envelope fa-lg'></i>
        </a>
        <a onclick='copyLinkToClipboard("{!!$article->getUrl()!!}")' style='cursor:pointer' data-toggle="tooltip" class="no-border"
           title="{{ trans('general.copy_url') }}">
            <i class='fa fa-link fa-lg'></i>
        </a>
</div>
{{-- </div> --}}

{{-- zoznam diel ??? --}}

{{-- 
mapa??
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
<script type="text/javascript">
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
</script>
@stop
