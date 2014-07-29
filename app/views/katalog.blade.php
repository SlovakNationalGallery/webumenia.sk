@extends('layouts.master')

@section('title')
@parent
| katalóg diel
@stop

@section('content')

<section class="collection content-section top-section">
    <div class="collection-body">
        <div class="container">
            <div class="row">
                <div class="col-md-8 col-md-offset-2 text-center">
                        <img src="/images/x.svg" alt="x" class="xko">
                    	<h2 class="uppercase bottom-space">katalóg diel</h2>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="collections content-section">
    <div class="collections-body">
        <div class="container">
            <div class="row">
                <div class="col-sm-3">
                    <h3>Filter: </h3>
                </div>
            	<div class="col-sm-9">
            		<h3>Diela: </h3>
                    @if ($items->count() == 0)
                        <p class="text-center">Momentálne žiadne diela</p>
                    @endif

                	@foreach ($items as $i=>$item)
                        @if ($i%2==0)
                            <br style="clear: both">
                        @endif
    	                <div class="col-md-6 col-sm-6 col-xs-12">
    	                	<a href="{{ $item->getDetailUrl() }}">
    	                		<img src="{{ $item->getImagePath() }}" class="img-responsive">	                		
    	                	</a>
                            <div class="item-title">
                                @if (!empty($item->iipimg_url))
                                    <div class="pull-right"><a href="{{ URL::to('dielo/' . $item->id . '/zoom') }}" data-toggle="tooltip" data-placement="left" title="Zoom obrázku"><i class="fa fa-search-plus"></i></a></div>
                                @endif    
                                <a href="{{ $item->getDetailUrl() }}">
                                    <em>{{ implode(', ', $item->authors) }}</em><br>
                                <strong>{{ $item->title }}</strong>, <em>{{ $item->getDatingFormated() }}</em><br>
                                
                                <span class="">{{ $item->gallery }}</span>
                                </a>
                            </div>
    	                </div>	
                	@endforeach
                </div>

            </div>
        </div>
    </div>
</section>

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

@stop

@section('javascript')

<script type="text/javascript">
    var map;
    $(document).ready(function(){
        $("[data-toggle='tooltip']").tooltip();

        map = new GMaps({
            el: '#big-map',
            lat: 48.705862, 
            lng: 19.855629,
            zoom: 7, 
            zoomControl : true,
            zoomControlOpt: {
                style : "SMALL",
                position: "TOP_LEFT"
            },
            panControl : false,
            streetViewControl : false,
            mapTypeControl: false,
            overviewMapControl: false,
            scrollwheel: false,
            scaleControl: false
            });
        var styles = [
            {
              stylers: [
                { hue: "#484224" },
                { saturation: -20 }
              ]
            }, {
                featureType: "road",
                elementType: "geometry",
                stylers: [
                    { lightness: 100 },
                    { visibility: "off" }
              ]
            }, {
                featureType: "road",
                elementType: "labels",
                stylers: [
                    { visibility: "off" }
              ]
            }
        ];
        
        map.addStyle({
            styledMapName:"Styled Map",
            styles: styles,
            mapTypeId: "map_style"  
        });
        
        map.setStyle("map_style");

        @foreach ($items as $item)
            @if (!empty($item->lat) && ($item->lat > 0))
                map.addMarker({
                    lat: {{ $item->lat }},
                    lng: {{ $item->lng }},
                    icon: "/images/x.map.svg",
                    title: 'Značka pre dielo {{ $item->title }}',
                    infoWindow: {
                      content: '<p class="text-center"><a href="{{ $item->getDetailUrl() }}"><img src="{{ $item->getImagePath() }}" /><br><em>{{ implode(', ', $item->authors) }}</em><br><strong>{{ $item->title }}</strong>, <em>{{ $item->getDatingFormated() }}</em></a></p>'
                    }
                });
            @endif
        @endforeach

    });
</script>
@stop
