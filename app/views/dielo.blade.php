@extends('layouts.master')

@section('og')
<meta property="og:title" content="{{ implode(', ', $item->authors)}} - {{ $item->title }}" />

<meta property="og:description" content="{{ $item->work_type; }}, datovanie: {{ $item->dating }}, rozmer: {{  implode(' x ', $item->measurements[0]) }}" />
<meta property="og:type" content="object" />
<meta property="og:url" content="{{ Request::url() }}" />
<meta property="og:image" content="{{ URL::to( $item->getImagePath() ) }}" />
<meta property="og:site_name" content="Web umenia" />
@stop

@section('title')
@parent
| {{ implode(', ', $item->authors)}} - {{ $item->title }}
@stop

@section('description')
    <meta name="description" content="{{ $item->work_type; }}, datovanie: {{ $item->dating }}, rozmer: {{  implode(' x ', $item->measurements[0]) }}">
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
                                    <a href="{{ URL::to('dielo/' . $item->id . '/stiahnut')  }}" class="btn btn-default btn-outline  uppercase sans" id="download"><i class="fa fa-download"></i> stiahnuť </a>
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
                                    <td>
                                        <strong>
                                        @foreach ($item->authors as $author_unformated => $author)
                                            <a href="{{ URL::to('katalog?author=' . $author_unformated) }}">{{ $author }}</a><br>
                                        @endforeach
                                        </strong>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="atribut">datovanie:</td>
                                    <td>{{ $item->getDatingFormated(); }}</td>
                                </tr>
                            </thead>
                            <tbody>
                                @if (!empty($item->measurements))
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
                                    <td>
                                        @foreach ($item->work_types as $i => $work_type)
                                            @if ($i == 0)
                                                <a href="{{ URL::to('katalog?work_type=' . $work_type) }}">{{ $work_type }}</a>
                                            @else
                                                {{ $work_type }}
                                            @endif
                                            @if (count($item->work_types) > ($i+1))
                                                 &rsaquo; 
                                            @endif
                                        @endforeach
                                    </td>
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
                                    <td>
                                    @foreach ($item->subjects as $subject)
                                        <a href="{{URL::to('katalog?subject=' . $subject)}}" class="btn btn-default btn-xs btn-outline">{{ $subject }}</a>
                                    @endforeach
                                    </td>
                                </tr>
                                @endif
                                @if (!empty($collection))
                                <tr>
                                    <td class="atribut">kolekcie:</td>
                                    <td>
                                        <div class="expandable">
                                        @foreach ($item->collections as $collection)
                                            <a href="{{ $collection->getUrl() }}">{{ $collection->name }}</a><br>
                                        @endforeach
                                        </div>
                                    </td>
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
                                    <td><div class="expandable">{{ implode('<br> ', $item->makeArray($item->inscription));}}</div></td>
                                </tr>
                                @endif
                                @if (!empty($item->gallery))
                                <tr>
                                    <td class="atribut">inštitúcia /<br> majiteľ:</td>
                                    <td><a href="{{ URL::to('katalog?gallery=' . $item->gallery) }}">{{ $item->gallery; }}</a></td>
                                </tr>
                                @endif
                                @if (!empty($item->identifier))
                                <tr>
                                    <td class="atribut">inventárne číslo:</td>
                                    <td>{{ $item->identifier; }}</td>
                                </tr>
                                @endif
                                @if ($item->isFreeDownload())
                                <tr>
                                    <td class="atribut">licencia obrázku:</td>
                                    <td><a rel="license" href="http://creativecommons.org/licenses/by-nc-sa/4.0/deed.cs" target="_blank" class="no-border"><img alt="Creative Commons License" style="border-width:0; padding-top: 2px;"  src="/images/license/by-nc-sa.svg" title="Creative Commons BY-NC-SA 4.0" data-toggle="tooltip"></a></td>
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

                    <div class="share">
                        <div class="fb-like" data-href="{{ $item->getDetailUrl() }}" data-layout="button_count" data-action="like" data-show-faces="false" data-share="false" style="height:20px; vertical-align: top;"></div> &nbsp;
                        <a href="https://twitter.com/share" class="twitter-share-button" style="float: right; text-align: right" >Tweet</a>
                        <script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');
                        </script>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Modal -->
<div tabindex="-1" class="modal fade" id="license" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header text-center">
                <img src="{{ URL::asset('images/license/cc.svg') }}" alt="Creative Commons">
            </div>
            <div class="modal-body">
                <p><strong>Vami zvolené dielo by sa malo začať v krátkom čase automaticky sťahovať.</strong></p>
                <p>Digitálne reprodukcie diel SNG na tejto stránke sú sprístupnené pod licenciou <a class="underline" href="http://creativecommons.org/licenses/by-nc-sa/4.0/deed.cs" target="_blank">Creative Commons BY-NC-SA 4.0</a>. Môžete si ich voľne stiahnuť vo vysokom rozlíšení. Reprodukcie sa môžu ľubovoľne využívať na nekomerčné účely - kopírovať, zdieľať či upravovať. Pri ďalšom šírení obrázkov je potrebné použiť rovnakú licenciu <em>(CC BY-NC-SA)</em> a uviesť odkaz na webstránku <a class="underline" href="http://dvekrajiny.sng.sk">http://dvekrajiny.sng.sk</a> s citáciou diela (autor, názov, rok vzniku, vlastník diela).</p>
                <p>Príklady využitia reprodukcií:</p>
                <ul>
                    <li>tlač na nekomerčné účely (plagáty, pohľadnice alebo tričká)</li>
                    <li>vlastná tvorba (digitálna úprava reprodukcie, využitie jej časti pre animáciu alebo koláž)</li>
                    <li>vzdelávanie (vloženie obrázku na vlastnú webstránku, použitie na Wikipedii či ako súčasť prezentácie)</li>
                </ul>    
                <p><a class="underline" href="{{ URL::to('creative-commons') }}">Všetky voľne stiahnuteľné diela nájdete tu.</a></p>
            </div>
            <div class="modal-footer">
                <div class="text-center"><button type="button" data-dismiss="modal" class="btn btn-default btn-outline uppercase sans">Zavrieť</button></div>
            </div>
        </div>
    </div>
</div>
<!-- Modal -->
<div tabindex="-1" class="modal fade" id="downloadfail" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header text-center">
                Nastala chyba
            </div>
            <div class="modal-body">
                <p>Vami zvolené dielo nebolo možné v tomto momente stiahnuť. Skúste to prosím neskôr.</p>
                <p>Pokiaľ problém pretrváva, kontaktujte nás na <a href="mailto:lab@sng.sk">lab@sng.sk</a></p>
            </div>
            <div class="modal-footer">
                <div class="text-center"><button type="button" data-dismiss="modal" class="btn btn-default btn-outline uppercase sans">Zavrieť</button></div>
            </div>
        </div>
    </div>
</div>

<!-- <div id="map"></div> -->

@stop


@section('javascript')
{{ HTML::script('js/readmore.min.js') }}
{{ HTML::script('js/jquery.fileDownload.js') }}
<script type="text/javascript">
    $(document).ready(function(){

        $('.expandable').readmore({
            moreLink: '<a href="#"><i class="fa fa-chevron-down"></i> zobraziť viac</a>',
            lessLink: '<a href="#"><i class="fa fa-chevron-up"></i> skryť</a>',
            maxHeight: 40,
            afterToggle: function(trigger, element, expanded) {
              if(! expanded) { // The "Close" link was clicked
                $('html, body').animate( { scrollTop: element.offset().top }, {duration: 100 } );
              }
            }
        });

        $('#download').on('click', function(e){
     
            $('#license').modal({})
            $.fileDownload($(this).attr('href'), {
                successCallback: function(url) {     
                },
                failCallback: function(responseHtml, url) {
                    $('#license').modal('hide');
                    $('#downloadfail').modal('show');
                }
            });
            return false; //this is critical to stop the click event which will trigger a normal file download!
        });

// $(document).on("click", "#download", function() {
//         $.fileDownload($(this).attr('href'), {
//             preparingMessageHtml: "We are preparing your report, please wait...",
//             failMessageHtml: "There was a problem generating your report, please try again."
//         });
//         return false; //this is critical to stop the click event which will trigger a normal file download!
//     });

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
