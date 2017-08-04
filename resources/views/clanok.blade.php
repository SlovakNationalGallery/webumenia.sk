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

<div class="container">
    @if ( ! $article->hasTranslation(App::getLocale()) )
        @include('includes.message_untranslated')
    @endif
</div>

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
    <div class="container text-center">
        <div class="fb-like" data-href="{!! $article->getUrl() !!}" data-layout="button_count" data-action="like" data-show-faces="false" data-share="false"></div>
        &nbsp;
        <a href="https://twitter.com/share" class="twitter-share-button" data-count="true">Tweet</a>
        <script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');</script>
    </div>
    &nbsp;
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

<script type="text/javascript">
</script>
@stop
