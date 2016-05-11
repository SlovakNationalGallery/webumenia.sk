@extends('layouts.master')

@section('og')
<meta property="og:title" content="{!! $item->getTitleWithAuthors() !!}" />
<meta property="og:description" content="{!! $item->work_type; !!}, datovanie: {!! $item->dating !!}, rozmer: {!!  implode(' x ', $item->measurements) !!}" />
<meta property="og:type" content="object" />
<meta property="og:url" content="{!! Request::url() !!}" />
<meta property="og:image" content="{!! URL::to( $item->getImagePath() ) !!}" />
<meta property="og:site_name" content="Web umenia" />
@stop

@section('title')
{!! $item->getTitleWithAuthors() !!} | 
@parent
@stop

@section('description')
    <meta name="description" content="{!! $item->work_type; !!}, datovanie: {!! $item->dating !!}, rozmer: {!!  implode(' x ', $item->measurements) !!}">
@stop

@section('link')
    <link rel="canonical" href="{!! $item->getUrl() !!}">
@stop


@section('content')

<section class="item top-section" itemscope itemtype="http://schema.org/VisualArtwork">
    <div class="item-body">
        <div class="container">
            <div class="row">
                @if (Session::has('message'))
                    <div class="alert alert-info alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>{!! Session::get('message') !!}</div>
                @endif
                <div class="col-md-10 col-md-offset-1 text-center content-section">
                    <h1 class="nadpis-dielo" itemprop="name">{!! $item->title !!}</h1>
                    <h2 class="inline">
                    {!! implode(', ', $item->getAuthorsWithLinks()) !!}
                    </h2>
                </div>
            </div>
            <div class="row">
                <div class="col-md-8 text-center">
                        @if (!empty($item->iipimg_url))
                            <a href="{!! URL::to('dielo/' . $item->id . '/zoom') !!}" data-toggle="tooltip" data-placement="top" title="Zoom obrázku">
                        @endif    
                        <img src="{!! $item->getImagePath() !!}" class="img-responsive img-dielo" alt="{!! $item->getTitleWithAuthors() !!}" itemprop="image">
                        @if (!empty($item->iipimg_url))
                            </a>
                        @endif
                        <div class="row">
                            <div class="col-sm-12">
                                @if ($previous)
                                    <a href="{!! $previous !!}" id="left" class="nav-arrow left">&larr;<span class="sr-only">predchádzajúce dielo</span></a>
                                @endif
                                @if ($next)
                                    <a href="{!! $next !!}" id="right" class="nav-arrow right">&rarr;<span class="sr-only">nasledujúce dielo</span></a>             
                                @endif
                            </div>

                            <div class="col-md-12 text-center">
                                @if (!empty($item->iipimg_url))
                                   <a href="{!! URL::to('dielo/' . $item->id . '/zoom') !!}" class="btn btn-default btn-outline  sans"><i class="fa fa-search-plus"></i> zoom obrázku</a>
                                @endif
                                @if ($item->isForReproduction())
                                    <a href="{!! URL::to('dielo/' . $item->id . '/objednat')  !!}" class="btn btn-default btn-outline  sans"><i class="fa fa-shopping-cart"></i> objednať reprodukciu </a>
                                @endif
                                @if ($item->isFreeDownload())                                
                                    <a href="{!! URL::to('dielo/' . $item->id . '/stiahnut')  !!}" class="btn btn-default btn-outline  sans" id="download"><i class="fa fa-download"></i> stiahnuť </a>
                                @endif
                            </div>
                            @if (!empty($item->description))
                            <div class="col-md-12 text-left medium description bottom-space underline" itemprop="description">
                                {!!  $item->description !!}

                                @if ($item->description_source)
                                    <p>
                                    @if ($item->description_user_id)
                                        {{-- Autor popisu: --}} {!! $item->descriptionUser->name !!} &#9679; 
                                    @endif
                                    @if ($item->description_source_link)
                                        {{-- Zdroj: --}}
                                        <a href="{!! $item->description_source_link !!}" target="_blank">{!! $item->description_source !!}</a>
                                    @else
                                        {!! $item->description_source !!}
                                    @endif
                                    </p>
                                @endif
                            </div>
                            @endif
                        </div>
                </div>
                <div class="col-md-4 text-left">

                        <table class="table">
                            <tbody>
                                <tr>
                                    <td class="atribut">datovanie:</td>
                                    <td><time itemprop="dateCreated" datetime="{!! $item->date_earliest !!}">{!! $item->getDatingFormated(); !!}</time></td>
                                </tr>
                                @if (!empty($item->measurements))
                                <tr>
                                    <td class="atribut">rozmer:</td>
                                    <td>
                                        @foreach ($item->measurements as $measurement)
                                        {{--     {!!  implode(' &times; ', $measurement) !!}<br> --}}
                                         {!! $measurement !!}<br> 
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
                                @endif
                                @if (!empty($item->topics))
                                <tr>
                                    <td class="atribut">žáner:</td>
                                    <td>
                                    @foreach ($item->topics as $topic)
                                        <a href="{!! URL::to('katalog?topic=' . $topic) !!}">{!! $topic !!}</a><br>
                                    @endforeach
                                    </td>
                                </tr>
                                @endif
                                @if ($item->tagNames() )
                                <tr>
                                    <td class="atribut">tagy:</td>
                                    <td>
                                    @foreach ($item->tagNames() as $tag)
                                        <a href="{!!URL::to('katalog?tag=' . $tag)!!}" class="btn btn-default btn-xs btn-outline">{!! $tag !!}</a>
                                    @endforeach
                                    </td>
                                </tr>
                                @endif
                                @if ($item->collections->count())
                                <tr>
                                    <td class="atribut">kolekcie:</td>
                                    <td>
                                        <div class="expandable">
                                        @foreach ($item->collections as $collection)
                                            <a href="{!! $collection->getUrl() !!}">{!! $collection->name !!}</a><br>
                                        @endforeach
                                        </div>
                                    </td>
                                </tr>
                                @endif
                                @if (!empty($item->mediums))
                                <tr>
                                    <td class="atribut">materiál:</td>
                                    <td>
                                    @foreach ($item->mediums as $medium)
                                        {!! addMicrodata($medium, "artMedium") !!}<br>
                                    @endforeach
                                    </td>
                                </tr>
                                @endif
                                @if (!empty($item->techniques))
                                <tr>
                                    <td class="atribut">technika:</td>
                                    <td>
                                    @foreach ($item->techniques as $technique)
                                        <a href="{!! URL::to('katalog?technique=' . $technique) !!}">{!! $technique !!}</a><br>
                                    @endforeach
                                    </td>
                                </tr>
                                @endif
                                @if (!empty($item->integrity))
                                <tr>
                                    <td class="atribut">stupeň spracovania:</td>
                                    <td>{!! $item->state_edition; !!}</td>
                                </tr>
                                @endif
                                @if (!empty($item->integrity))
                                <tr>
                                    <td class="atribut">stupeň integrity:</td>
                                    <td>{!! $item->integrity; !!}</td>
                                </tr>
                                @endif
                                @if (!empty($item->integrity_work))
                                <tr>
                                    <td class="atribut">integrita s dielami:</td>
                                    <td>{!! $item->integrity_work; !!}</td>
                                </tr>
                                @endif
                                @if (!empty($item->inscription))
                                <tr>
                                    <td class="atribut">značenie:</td>
                                    <td><div class="expandable">{!! implode('<br> ', $item->makeArray($item->inscription));!!}</div></td>
                                </tr>
                                @endif
                                @if (!empty($item->gallery))
                                <tr>
                                    <td class="atribut">galéria:</td>
                                    <td><a href="{!! URL::to('katalog?gallery=' . $item->gallery) !!}">{!! $item->gallery; !!}</a></td>
                                </tr>
                                @endif
                                @if (!empty($item->identifier))
                                <tr>
                                    <td class="atribut">inventárne číslo:</td>
                                    <td>{!! $item->identifier; !!}</td>
                                </tr>
                                @endif
                                @if ($item->isFreeDownload())
                                <tr>
                                    <td class="atribut">licencia:</td>
                                    {{-- <td><a rel="license" href="http://creativecommons.org/licenses/by-nc-sa/4.0/deed.cs" target="_blank" class="no-border"><img alt="Creative Commons License" style="border-width:0; padding-top: 2px;"  src="/images/license/by-nc-sa.svg" title="Creative Commons BY-NC-SA 4.0" data-toggle="tooltip"></a></td> --}}
                                    <td><a rel="license" href="{!!URL::to('katalog?is_free=' . '1')!!}" target="_blank" class="no-border license" title="Public Domain" data-toggle="tooltip"><img alt="Creative Commons License" style="height: 20px; width: auto"  src="/images/license/zero.svg" > voľné dielo</a></td>
                                </tr>                                    
                                @endif
                                @if (!empty($item->place))
                                <tr>
                                    <td class="atribut">geografická oblasť:</td>
                                    <td>{!! $item->place; !!}</td>
                                </tr>
                                @endif
                                @if (!empty($item->related_work))
                                <tr>
                                    <td class="atribut">{!! $item->relationship_type !!}:</td>

                                    <td>
                                        <a href="{!! URL::to('katalog?related_work=' . $item->related_work . '&amp;author=' .  $item->first_author) !!}" itemprop="isPartOf">{!! $item->related_work !!}</a> 
                                        @if ($item->related_work_order)
                                            ({!! $item->related_work_order !!}/{!! $item->related_work_total !!})
                                        @endif                                        
                                    </td>
                                </tr>
                                @endif
                            </tbody>
                        </table>
                        
                    <div>
                    @if (!empty($item->related_work))
                        <div style="position: relative; padding: 0 10px;">
                        <?php $related_tems = Item::where('related_work', '=', $item->related_work)->where('author', '=', $item->author)->orderBy('related_work_order')->get() ?>
                        <div class="artworks-preview small">
                        @foreach ($related_tems as $item)
                            <a href="{!! $item->getUrl() !!}"><img data-lazy="{!! $item->getImagePath() !!}" class="img-responsive-width " alt="{!! $item->getTitleWithAuthors() !!} "></a>
                        @endforeach
                        </div>
                        </div>
                    @endif
                    </div>

                    @if (!empty($item->lat) && ($item->lat > 0)) 
                        <div id="small-map"></div>
                    @endif

                    <div class="share">
                        <div class="fb-like" data-href="{!! $item->getUrl() !!}" data-layout="button_count" data-action="like" data-show-faces="false" data-share="false" style="height:20px; vertical-align: top;"></div> &nbsp;
                        <a href="https://twitter.com/share" class="twitter-share-button" style="float: right; text-align: right" >Tweet</a>
                        <script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');
                        </script>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<section class="more-items content-section">
    <div class="container">
        <div class="row">
            <div class="col-xs-12">
                <h4>súvisiace diela</h4>
                <div class="artworks-preview ">
                @foreach ($more_items as $item)
                    <a href="{!! $item->getUrl() !!}"><img data-lazy="{!! $item->getImagePath() !!}" class="img-responsive-width " alt="{!! $item->getTitleWithAuthors() !!} "></a>
                @endforeach
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
                <img src="{!! URL::asset('images/license/cc.svg') !!}" alt="Creative Commons">
            </div>
            <div class="modal-body">
                <p><strong>Vami zvolené dielo by sa malo začať v krátkom čase automaticky sťahovať.</strong></p>
                <p>Digitálne reprodukcie diel SNG na tejto stránke sú sprístupnené ako <a href="https://creativecommons.org/publicdomain/zero/1.0/" target="_blank" class="underline">verejné vlastníctvo (public domain)</a>. Môžete si ich voľne stiahnuť vo vysokom rozlíšení a využívať na súkromné aj komerné účely &ndash; kopírovať, zdieľať i upravovať.</p>
                <p>Pri ďalšom šírení prosíme uviesť meno autora, názov, majiteľa diela a zdroj <code>{!! $item->getUrl() !!}</code></p>
                <p>Ak plánujete využiť reprodukcie na komerčné účely, prosím informujte o vašich plánoch vopred, naši odborníci vám vedia poradiť.</p>  
                <p><a class="underline" href="{!! URL::to('katalog?is_free=' . '1') !!}">Všetky voľne stiahnuteľné diela nájdete tu.</a></p>
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
{!! HTML::script('js/slick.js') !!}
{!! HTML::script('js/readmore.min.js') !!}
{!! HTML::script('js/jquery.fileDownload.js') !!}

@if (!empty($item->lat) && ($item->lat > 0)) 
    <!-- Google Maps API Key - You will need to use your own API key to use the map feature -->
    <script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCRngKslUGJTlibkQ3FkfTxj3Xss1UlZDA&sensor=false"></script>
    {!! HTML::script('js/gmaps.js') !!}
@endif

<script type="text/javascript">

    function leftArrowPressed() {
        var left=document.getElementById("left");
        if (left) location.href = left.href;
    }

    function rightArrowPressed() {
        var right=document.getElementById("right");
        if (right) location.href = right.href;
    }

    document.onkeydown = function(evt) {
        evt = evt || window.event;
        switch (evt.keyCode) {
            case 37:
                leftArrowPressed();
                break;
            case 39:
                rightArrowPressed();
                break;
        }
    };

    $(document).ready(function(){

        $('.expandable').readmore({
            moreLink: '<a href="#"><i class="fa fa-chevron-down"></i> zobraziť viac</a>',
            lessLink: '<a href="#"><i class="fa fa-chevron-up"></i> skryť</a>',
            maxHeight: 40
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
            lat: {!! $item->lat !!},
            lng: {!! $item->lng !!},
            // icon: "/images/x.map.svg",            
            title: 'Značka pre dielo {!! $item->title !!}',
            infoWindow: {
              content: '<p>{!! $item->place !!}</p>'
            }
        });

    });
</script>
@endif
@stop
