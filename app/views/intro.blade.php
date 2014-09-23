@extends('layouts.master')

@section('title')
@parent
- obraz Slovenska - 19. storočie × súčasnosť
@stop

@section('content')

<section class="intro content-section ">
    <div class="intro-body">
        <div class="container">
            <div class="row">
                <div class="col-md-8 col-md-offset-2">
                    <div class="intro-text">
                    	<h1>DVE KRAJINY</h1>
                    	<h2>OBRAZ SLOVENSKA</h2>
                    	<h3>19. storočie <i class="icon-versus"></i> súčasnosť</h3>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="scroll-to hidden-xs">
        <a href="#sekcie" class="btn btn-circle btn-outline btn-default"><i class="fa fa-chevron-down" ></i></a>
    </div>
    <!-- HOMEPAGE - share -->
    <div class="shareon-container">
    <div class="container text-right">
        <div class="fb-like" data-href="http://dvekrajiny.sng.sk/" data-layout="button_count" data-action="like" data-show-faces="false" data-share="false"></div>&nbsp;
        <a href="https://twitter.com/share" class="twitter-share-button" data-count="true">Tweet</a>
<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');</script>
    </div>
    </div>
</section>

<section class="collections content-section" id="sekcie">
    <div class="collections-body">
        <div class="container">
            <div class="row">
            	<div class="col-xs-12">
            		<h3>Výstavné sekcie: </h3>
            	</div>
            	@foreach ($collections as $collection)
	                <div class="col-md-4 col-sm-6 col-xs-12">
	                	<a href="{{ URL::to('sekcia/' . $collection->id) }}" class="featured-collection">
	                		<img src="{{ URL::to('images/sekcie/' . $collection->id . '.jpeg') }}" class="img-responsive" alt="{{ $collection->name }}">
	                		<h4 class="title">{{ $collection->name }}</h4>
	                	</a>
	                    
	                </div>	
            	@endforeach
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
  <script type="text/javascript" src="http://google-maps-utility-library-v3.googlecode.com/svn/tags/markerclusterer/1.0/src/markerclusterer.js"></script>

<script type="text/javascript">
    var map;
    $(document).ready(function(){

        $('.scroll-to a').click(function(){
            $('html, body').animate({
                scrollTop: $( $.attr(this, 'href') ).offset().top - 50
            }, 500);
            return false;
        });

        map = new GMaps({
            el: '#big-map',
            lat: 48.705862, 
            lng: 19.855629,
            zoom: 7, 
            zoomControl : true,
            zoomControlOpt: {
                style : "DEFAULT",
                position: "TOP_LEFT"
            },
            panControl : false,
            streetViewControl : false,
            mapTypeControl: false,
            overviewMapControl: false,
            scrollwheel: false,
            scaleControl: false,
            // markerClusterer: function(map) {
            //   return new MarkerClusterer(map);
            // }
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
