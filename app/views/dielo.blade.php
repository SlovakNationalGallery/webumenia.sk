@extends('layouts.master')

@section('title')
@parent
- {{ $item->name }}
@stop

@section('content')

<section class="item content-section top-section">
    <div class="item-body">
        <div class="container">
            <div class="row">
                <div class="col-md-8 col-md-offset-2 text-center">
                    <h2 class="uppercase">{{ $item->title }}</h2>
                </div>
            </div>
            <div class="row">
                <div class="col-md-8 text-center">
                        @if (!empty($item->iipimg_url))
                            <a href="{{ URL::to('dielo/' . $item->id . '/zoom') }}">
                        @endif    
                        <img src="{{ $item->getImagePath() }}" class="img-responsive img-dielo">
                        @if (!empty($item->iipimg_url))
                            </a>
                        @endif
                        <a href="{{ $item->getImagePath() }}" class="btn btn-default btn-outline pull-left uppercase sans"><i class="fa fa-download"></i> stiahnuť obrázok</a>
                        @if (!empty($item->iipimg_url))
                           <a href="{{ URL::to('dielo/' . $item->id . '/zoom') }}" class="btn btn-default btn-outline pull-right uppercase sans"><i class="fa fa-search-plus"></i> deep zoom obrázku</a>
                        @endif    
                        
                </div>
                <div class="col-md-4 text-left">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <td class="atribut">autor:</td>
                                    <td>{{ $item->author; }}</td>
                                </tr>
                                <tr>
                                    <td class="atribut">datovanie:</td>
                                    <td>{{ $item->dating; }}</td>
                                </tr>
                            </thead>
                            <tbody>
                                @if (!empty($item->description))
                                <tr>
                                    <td class="atribut">popis:</td>
                                    <td>{{  nl2br($item->description) }}</td>
                                </tr>
                                @endif
                                @if (!empty($item->t))
                                <tr>
                                    <td class="atribut">výtvarný druh:</td>
                                    <td>{{ $item->work_type; }}</td>
                                </tr>
                                @endif
                                @if (!empty($item->topic))
                                <tr>
                                    <td class="atribut">žáner:</td>
                                    <td>{{ $item->topic; }}</td>
                                </tr>
                                @endif
                                @if (!empty($item->subject))
                                <tr>
                                    <td class="atribut">motív:</td>
                                    <td>{{ $item->subject; }}</td>
                                </tr>
                                @endif
                                @if (!empty($item->technique))
                                <tr>
                                    <td class="atribut">materiál:</td>
                                    <td>{{ $item->medium; }}</td>
                                </tr>
                                @endif
                                @if (!empty($item->technique))
                                <tr>
                                    <td class="atribut">technika:</td>
                                    <td>{{ $item->technique; }}</td>
                                </tr>
                                @endif
                                @if (!empty($item->inscription))
                                <tr>
                                    <td class="atribut">značenie:</td>
                                    <td>{{ $item->inscription; }}</td>
                                </tr>
                                @endif
                                @if (!empty($item->place))
                                <tr>
                                    <td class="atribut">geografická oblasť:</td>
                                    <td>{{ $item->place; }}</td>
                                </tr>
                                @endif
                                @if (!empty($item->integrity))
                                <tr>
                                    <td class="atribut">stupeň spracovania:</td>
                                    <td>{{ $item->state_edition; }}</td>
                                </tr>
                                @endif
                                @if (!empty($item->integrity))
                                <tr>
                                    <td class="atribut">stupeň integrity:</td>
                                    <td>{{ $item->integrity; }}</td>
                                </tr>
                                @endif
                                @if (!empty($item->integrity_work))
                                <tr>
                                    <td class="atribut">integrita s dielami:</td>
                                    <td>{{ $item->integrity_work; }}</td>
                                </tr>
                                @endif
                                @if (!empty($item->gallery))
                                <tr>
                                    <td class="atribut">inštitúcia / majiteľ:</td>
                                    <td>{{ $item->gallery; }}</td>
                                </tr>
                                @endif
                            </tbody>
                        </table>
                        @if (!empty($item->lat) && ($item->lat > 0)) 
                            <div id="small-map"></div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>


<!-- <div id="map"></div> -->

@stop


@section('javascript')

@if (!empty($item->lat) && ($item->lat > 0)) 
<script type="text/javascript">
    var map;
    $(document).ready(function(){
        map = new GMaps({
            el: '#small-map',
            lat: 48.705862, 
            lng: 19.855629,
            zoom: 6, 
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
            styles: light_style,
            mapTypeId: "map_style"  
        });
        
        map.setStyle("map_style");   

        map.addMarker({
            lat: {{ $item->lat }},
            lng: {{ $item->lng }},
            title: 'Značka pre dielo {{ $item->title }}',
            infoWindow: {
              content: '<p>{{ $item->place }}</p>'
            }
        });

    });
</script>
@endif
@stop
