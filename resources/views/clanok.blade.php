@extends('layouts.master')

@section('og')
@if (!$article->publish)
    <meta name="robots" content="noindex, nofollow">
@endif
<meta property="og:title" content="{!! $article->title !!}" />
<meta property="og:description" content="{!! strip_tags($article->summary) !!}" />
<meta property="og:type" content="website" />
<meta property="og:url" content="{!! Request::url() !!}" />
<meta property="og:image" content="{!! URL::to( $article->getHeaderImage()) !!}" />

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
{{-- <section class="article summary bg-light-grey content-section">
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

                    <!-- share -->
                    @include('components.share_buttons', [
                        'title' => $article->title,
                        'url' => $article->getUrl(),
                        'img' => URL::to($article->getHeaderImage()),
                    ])
                </div>
                <div class="col-md-6 attributes">
                    {!! $article->content !!}
                </div>
                @if($article->references)
                <div class="col-md-6 col-md-offset-4 attributes">
                    <hr/>
                    <div class="references">
                        {!! $article->references !!}
                    </div>
                </div>
                @endif
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
{!! Html::script('js/slick.js') !!}
{!! Html::script('js/components/share_buttons.js') !!}
{!! Html::script('js/readmore.min.js') !!}s
<script type="text/javascript">
    $(document).ready(function(){
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

        $('.references').readmore({
            moreLink: '<a href="#"><i class="fa fa-chevron-down"></i> {{ trans("general.show_more") }}</a>',
            lessLink: '<a href="#"><i class="fa fa-chevron-up"></i> {{ trans("general.show_less") }}</a>',
            maxHeight: 40
        });
    });

</script>
@stop
