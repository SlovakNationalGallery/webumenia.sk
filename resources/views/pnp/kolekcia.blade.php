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


<section class="collection content-section">
    <div class="collection-body">
        <div class="container">
            <div class="row">
                <div class="col-md-8 col-md-offset-2 bottom-space description">
                </div>
            </div>
        </div>
    </div>
</section>

<section class="collections content-section">
    <div class="collections-body">
        <div class="container">
            <div class="row">
            	<div class="col-xs-12 isotope-wrapper">
                    @if ($collection->items->count() == 0)
                        <p class="text-center">Momentálne žiadne diela</p>
                    @endif
                    <div id="iso">
                    @foreach ($collection->items as $i=>$item)
                        <div class="col-md-3 col-sm-4 col-xs-12 item">
                            <a href="{!! $item->getUrl(['collection' => $collection->id]) !!}">
                                @php
                                    list($width, $height) = getimagesize(public_path() . $item->getImagePath());
                                @endphp
                                <div class="ratio-box" style="padding-bottom: {{ round(($height / $width) * 100, 4) }}%;">

                                <img
                                    data-sizes="auto"
                                    data-src="{!! route('dielo.nahlad', ['id' => $item->id, 'width'=>'600']) !!}"
                                    data-srcset="{!! route('dielo.nahlad', ['id' => $item->id, 'width'=>'600']) !!} 600w,
                                            {!! route('dielo.nahlad', ['id' => $item->id, 'width'=>'220']) !!} 220w,
                                            {!! route('dielo.nahlad', ['id' => $item->id, 'width'=>'300']) !!} 300w,
                                            {!! route('dielo.nahlad', ['id' => $item->id, 'width'=>'600']) !!} 600w,
                                            {!! route('dielo.nahlad', ['id' => $item->id, 'width'=>'800']) !!} 800w"
                                    class="lazyload"
                                    alt="{!! $item->getTitleWithAuthors() !!} ">
                                </div>
                            </a>
                            <div class="item-title">
                                @if ($item->hasZoomableImages())
                                    <div class="pull-right"><a href="{{ route('item.zoom', ['id' => $item->id])  }}" data-toggle="tooltip" data-placement="left" title="Zoom obrázku"><i class="fa fa-search-plus"></i></a></div>
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
    // start with isotype even before document is ready
    $('.isotope-wrapper').each(function(){
        var $container = $('#iso', this);
        spravGrid($container);
    });

    $(document).ready(function(){

        $( window ).resize(function() {
            var $container = $('#iso');
            spravGrid($container);
        });
    });
</script>
@stop
