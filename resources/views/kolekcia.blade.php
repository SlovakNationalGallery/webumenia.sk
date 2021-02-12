@extends('layouts.master')

@section('og')
@if (!$collection->publish)
    <meta name="robots" content="noindex, nofollow">
@endif
<meta property="og:title" content="{!! $collection->name !!}" />
<meta property="og:description" content="{!! $collection->getShortTextAttribute($collection->text, 500) !!}" />
<meta property="og:type" content="website" />
<meta property="og:url" content="{!! Request::url() !!}" />
<meta property="og:image" content="{!! asset_timed($collection->getHeaderImage()) !!}" />
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

@section('head-javascript')
{{-- For WEBUMENIA-1462 --}}
{!! Html::script('js/soundcloud.api.js') !!}
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
                {!! Carbon::parse($collection->published_at)->format('d. m. Y') !!}
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

<section class="collection content-section pb-0">
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
                @if ($collection->items->count() == 0)
                    <p class="text-center">Momentálne žiadne diela</p>
                @endif
                <div class="isotope">
                    @foreach ($collection->items as $item)
                        @include('components.artwork_grid_item', [
                            'item' => $item,
                            'class_names' => 'col-md-3 col-sm-4 col-xs-12',
                        ])
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>
@stop

@section('javascript')
{!! Html::script('js/slick.js') !!}
{!! Html::script('js/components/share_buttons.js') !!}

<script type="text/javascript">
    $('.isotope').isotope({
        itemSelector: '.item',
        layoutMode: 'masonry'
    });
</script>
@stop
