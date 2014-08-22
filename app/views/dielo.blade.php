@extends('layouts.master')

@section('og')
<meta property="og:title" content="{{ implode(', ', $item->authors)}} - {{ $item->title }}" />

<meta property="og:description" content="{{ $item->work_type; }}, datovanie: {{ $item->dating }}, rozmer: {{  implode(' x ', $item->measurements[0]) }}" />
<meta property="og:type" content="object" />
<meta property="og:url" content="{{ Request::url() }}" />
<meta property="og:image" content="{{ URL::to( $item->getImagePath() ) }}" />
<meta property="og:site_name" content="DVE KRAJINY" />
@stop

@section('title')
@parent
| {{ implode(', ', $item->authors)}} - {{ $item->title }}
@stop

@section('content')

<section class="item content-section top-section">
    <div class="item-body">
        <div class="container">
            <div class="row">
                @if (Session::has('message'))
                    <div class="alert alert-info alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>{{ Session::get('message') }}</div>
                @endif
                <div class="col-md-10 col-md-offset-1 text-center">
                    <h2 class="uppercase bottom-space nadpis-dielo">{{ $item->title }}</h2>
                </div>
            </div>
            <div class="row">
                <div class="col-md-8 text-center">
                        @if (!empty($item->iipimg_url))
                            <a href="{{ URL::to('dielo/' . $item->id . '/zoom') }}" data-toggle="tooltip" data-placement="top" title="Zoom obrázku">
                        @endif    
                        <img src="{{ $item->getImagePath() }}" class="img-responsive img-dielo">
                        @if (!empty($item->iipimg_url))
                            </a>
                        @endif
                        <div class="row">
                            <div class="col-md-12 text-center">
                                @if (!empty($item->iipimg_url))
                                   <a href="{{ URL::to('dielo/' . $item->id . '/zoom') }}" class="btn btn-default btn-outline  uppercase sans"><i class="fa fa-search-plus"></i> zoom obrázku</a>
                                @endif
                                @if ($item->isForReproduction())
                                    <a href="{{ URL::to('dielo/' . $item->id . '/objednat')  }}" class="btn btn-default btn-outline  uppercase sans"><i class="fa fa-shopping-cart"></i> objednať reprodukciu </a>
                                @endif
                                @if ($item->isFreeDownload())
                                <!-- <a rel="license" href="http://creativecommons.org/licenses/by-nc-sa/4.0/"><img alt="Creative Commons License" style="border-width:0" src="https://i.creativecommons.org/l/by-nc-sa/4.0/88x31.png" /></a><br />This work is licensed under a <a rel="license" href="http://creativecommons.org/licenses/by-nc-sa/4.0/">Creative Commons Attribution-NonCommercial-ShareAlike 4.0 International License</a>.<br> -->
                                <a href="{{ URL::to('dielo/' . $item->id . '/stiahnut')  }}" class="btn btn-default btn-outline  uppercase sans"><i class="fa fa-download"></i> stiahnuť </a>
                                @endif
                            </div>
                            @if (!empty($item->description))
                            <div class="col-md-12 text-left medium description bottom-space">
                                {{  $item->description }}
                            </div>
                            @endif
                        </div>
                </div>
                <div class="col-md-4 text-left">

                        <table class="table">
                            <thead>
                                <tr>
                                    <td class="atribut">autor:</td>
                                    <td><strong>{{ implode('<br> ', $item->authors);}}</strong></td>
                                </tr>
                                <tr>
                                    <td class="atribut">datovanie:</td>
                                    <td>{{ $item->getDatingFormated(); }}</td>
                                </tr>
                            </thead>
                            <tbody>
                                @if (!empty($item->measurement))
                                <tr>
                                    <td class="atribut">rozmer:</td>
                                    <td>
                                        @foreach ($item->measurements as $measurement)
                                            {{  implode(' &times; ', $measurement) }}<br>
                                        @endforeach
                                    </td>
                                </tr>
                                @endif
                                @if (!empty($item->work_type))
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
                                    <td class="atribut">tagy:</td>
                                    <td>{{ implode(', ', $item->subjects);}}</td>
                                </tr>
                                @endif
                                @if (!empty($collection))
                                <tr>
                                    <td class="atribut">sekcia:</td>
                                    <td><a href="{{ $collection->getUrl() }}">{{ $collection->name }}</a></td>
                                </tr>
                                @endif
                                @if (!empty($item->medium))
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
                                @if (!empty($item->inscription))
                                <tr>
                                    <td class="atribut">značenie:</td>
                                    <td><div class="znacenie">{{ implode('<br> ', $item->makeArray($item->inscription));}}</div></td>
                                </tr>
                                @endif
                                @if (!empty($item->gallery))
                                <tr>
                                    <td class="atribut">inštitúcia /<br> majiteľ:</td>
                                    <td>{{ $item->gallery; }}</td>
                                </tr>
                                @endif
                                @if (!empty($item->identifier))
                                <tr>
                                    <td class="atribut">inventárne číslo:</td>
                                    <td>{{ $item->identifier; }}</td>
                                </tr>
                                @endif
                                @if (!empty($item->place))
                                <tr>
                                    <td class="atribut">geografická oblasť:</td>
                                    <td>{{ $item->place; }}</td>
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
</section>


<!-- <div id="map"></div> -->

@stop


@section('javascript')
{{ HTML::script('js/readmore.min.js') }}
<script type="text/javascript">
    $(document).ready(function(){

        $('.znacenie').readmore({
            moreLink: '<a href="#"><i class="fa fa-chevron-down"></i> zobraziť viac</a>',
            lessLink: '<a href="#"><i class="fa fa-chevron-up"></i> skryť</a>',
            maxHeight: 40,
            afterToggle: function(trigger, element, expanded) {
              if(! expanded) { // The "Close" link was clicked
                $('html, body').animate( { scrollTop: element.offset().top }, {duration: 100 } );
              }
            }
        });

        $("[data-toggle='tooltip']").tooltip();

    });
</script>

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
            // icon: "/images/x.map.svg",            
            title: 'Značka pre dielo {{ $item->title }}',
            infoWindow: {
              content: '<p>{{ $item->place }}</p>'
            }
        });

    });
</script>
@endif
@stop
