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
                {!! App\Subtitle::random() !!}
            </p>
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

<div class="container">
    <h3 class="text-center mt-3 mb-2">Vybrané články</h3>

    <div class="row">
        <div class="col-sm-offset-2 col-sm-4 col-xs-12 bottom-space">
            @include('components.article_thumbnail', [
                'article' => $articles[0]
            ])
        </div>
        <div class="col-sm-4 col-xs-12 bottom-space">
            @include('components.article_thumbnail', [
                'article' => $articles[1]
            ])
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12 text-center">
            <a href="/clanky" class="underline"><strong>Všetky články →</strong></a>
        </div>
    </div>

    <h3 class="text-center mt-3 mb-2">Autori z kolekcie</h3>
    <div class="row">
        @foreach($items_by_author as $item)
        <div class="@if($item->id == $items_by_author[0]->id) col-md-offset-1 @endif col-md-2 col-sm-3 col-xs-6 item">
            <a href="{!! $item->getUrl() !!}">
                @php
                    list($width, $height) = getimagesize(public_path() . $item->getImagePath());
                    $width =  max($width,1); // prevent division by zero exception
                @endphp
                <div class="ratio-box" style="padding-bottom: {{ round(($height / $width) * 100, 4) }}%;">
                        @include('components.item_image_responsive', ['item' => $item])
                </div>
            </a>
            <div class="p-1">
                <h4 class="mb-1">
                    <strong>
                        <a href="{!!  url_to('katalog', ['author' => $item->author ]) !!}">{!! $item->getAuthorFormated() !!}</a>
                    </strong>
                </h4>
                {{-- <em>{!! $item->getDatingFormated() !!}</em> --}}
            </div>
        </div>
        @endforeach
    </div>
    <div class="row mt-2">
        <div class="col-xs-12 text-center">
            <a href="/autori" class="underline"><strong>Viac autorov →</strong></a>
        </div>
    </div>

    <h3 class="text-center mt-4 mb-2">Vyhľadávanie podľa techniky, žánru, farieb</h3>
    @foreach(array($metadata_item) as $item)
    <div class="row mb-3">
        <div class="col-md-offset-2 col-md-4">
            <a href="{!! $item->getUrl() !!}">
                @php
                    list($width, $height) = getimagesize(public_path() . $item->getImagePath());
                    $width =  max($width,1); // prevent division by zero exception
                @endphp
                <div class="ratio-box" style="padding-bottom: {{ round(($height / $width) * 100, 4) }}%;">
                        @include('components.item_image_responsive', ['item' => $item])
                </div>
            </a>
        </div>
        <div class="col-md-4">
            <h3 class="mt-1"><strong>{!! $item->title !!}</strong></h3>
            <h4 class=""><strong>{!! $item->title !!}</strong></h4>
            <table class="table">
                <tbody>
                    <tr>
                        <td class="atribut">{{ trans('dielo.item_attr_work_type') }}:</td>
                        <td>
                            @foreach ($item->work_types as $i => $work_type)
                                @if ($i == 0)
                                    <a href="{!! URL::to('katalog?work_type=' . $work_type) !!}">{!! addMicrodata($work_type, "artform") !!}</a>
                                @else
                                    {!! $work_type !!}
                                @endif
                                @if (count($item->work_types) > ($i+1))
                                        &rsaquo;
                                @endif
                            @endforeach
                        </td>
                    </tr>
                    <tr>
                        <td class="atribut">{{ trans('dielo.item_attr_topic') }}:</td>
                        <td>
                        @foreach ($item->topics as $topic)
                            <a href="{!! URL::to('katalog?topic=' . $topic) !!}">{!! $topic !!}</a><br>
                        @endforeach
                        </td>
                    </tr>
                    @if ($item->tagNames() || Auth::check())
                    <tr>
                        <td class="atribut">{{ trans('dielo.item_attr_tag') }}:</td>
                        <td>
                            @foreach ($item->tagNames() as $tag)
                                <a href="{!!URL::to('katalog?tag=' . $tag)!!}" class="btn btn-default btn-xs btn-outline">{!! $tag !!}</a>
                            @endforeach
                        </td>
                    </tr>
                    <tr>
                        <td class="atribut">farby:</td>
                        <td>
                            @php Debugbar::info($colors) @endphp
                            @include('components.color_list', ['colors' => $colors])
                        </td>
                    </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
    @endforeach

    <div class="row">
        <div class="col-xs-12 text-center">
            <a href="/autori" class="underline"><strong>Zobraziť katalóg →</strong></a>
        </div>
    </div>
</div>
<div class="mb-5"></div>
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
