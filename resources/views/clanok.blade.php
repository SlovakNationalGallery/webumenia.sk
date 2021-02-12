@extends('layouts.master')

@section('og')
@if (!$article->publish)
<meta name="robots" content="noindex, nofollow">
@endif
<meta property="og:title" content="{!! $article->title !!}" />
<meta property="og:description" content="{!! strip_tags($article->summary) !!}" />
<meta property="og:type" content="website" />
<meta property="og:url" content="{!! Request::url() !!}" />
<meta property="og:image" content="{!! URL::to( $article->header_image_src) !!}" />

@foreach ($article->getContentImages() as $image )
<meta property="og:image" content="{!! $image !!}" />
@endforeach
<meta property="og:site_name" content="Web umenia" />
@stop

@section('title')
{!! $article->title !!} |
@parent
@stop

@section('description')
<meta name="description" content="{!! $article->shortText !!}">
@stop

@section('head-javascript')
{{-- For WEBUMENIA-1462 --}}
{!! Html::script('js/soundcloud.api.js') !!}
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

@component('components.header_carousel', ['item' => $article]))
    @slot('slideContent')
        <h1>{!! $article->title !!}</h1>
        @if ($article->category)
        {{-- keep in one line to prevent formatting failures --}}
        <h2 style="color: {!! $article->title_color !!}">{!! $article->category->name !!}</h2>
        @endif
    @endslot
@endcomponent

<section class="article content-header">
    <div class="article-header">
        <div class="container">
            <div class="row text-center mb-4">
                <div class="col-md-8 col-md-push-2">
                    <div class="row">
                        <div class="col-md-4 col-sm-6  col-xs-12">
                            <div class="v-center">
                                <a href="{!! url_to( 'clanky', ['author' => $article->author ]) !!}">
                                    {!! $article->author!!}
                                </a>
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-6 col-xs-12">
                            <div class="v-center">
                                <i class='fa fa-calendar-o mr-3'></i>
                                @date($article->published_date)
                            </div>
                        </div>

                        <div class="col-md-4 col-md-push-0 col-sm-6 col-sm-push-6 col-xs-12">
                            @if ($article->reading_time)
                            <div class="v-center">
                                <i class='fa fa-clock-o mr-3'></i>{!! $article->reading_time !!}
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="article content-section">
    <div class="article-body">
        <div class="container">
            <div class="row">
                <div class="col-md-8 col-md-push-2 lead attributes long-text">
                    {!! $article->summary !!}

                    <!-- share -->
                    @include('components.share_buttons', [
                        'title' => $article->title,
                        'url' => $article->getUrl(),
                        'img' => URL::to($article->header_image_src),
                    ])
                </div>
            </div>
            <div class="row">
                <div class="col-md-8 col-md-push-2 attributes long-text">
                    {!! $article->content !!}
                </div>
            </div>
        </div>
    </div>
</section>


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
{!! Html::script('js/components/share_buttons.js') !!}
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