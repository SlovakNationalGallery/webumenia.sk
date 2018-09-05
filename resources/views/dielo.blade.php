@extends('layouts.master')

@section('og')
<meta property="og:title" content="{!! $item->getTitleWithAuthors() !!}" />
<meta property="og:description" content="{!! $item->work_type; !!}, {{ trans('dielo.item_attr_dating') }}: {!! $item->dating !!}, {{ trans('dielo.item_attr_measurements') }}: {!!  implode(' x ', $item->measurements) !!}" />
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
    <meta name="description" content="{!! $item->work_type; !!}, {{ trans('dielo.item_attr_dating') }}: {!! $item->dating !!}, {{ trans('dielo.item_attr_measurements') }}: {!!  implode(' x ', $item->measurements) !!}">
@stop

@section('link')
    <link rel="canonical" href="{!! $item->getUrl() !!}">
@stop

@section('content')

@if ( ! $item->hasTranslation(App::getLocale()) )
    <section>
        <div class="container top-section">
            <div class="row">
                @include('includes.message_untranslated')
            </div>
        </div>
    </section>
@endif

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
                        @if ($item->has_iip)
                            <a href="{{ route('item.zoom', ['id' => $item->id]) }}" data-toggle="tooltip" data-placement="top" title="{{ utrans('general.item_zoom') }}">
                        @endif
                        <img src="{!! $item->getImagePath() !!}" class="img-responsive img-dielo" alt="{!! $item->getTitleWithAuthors() !!}" itemprop="image">
                        @if ($item->has_iip)
                            </a>
                        @endif
                        <div class="row">
                            <div class="col-sm-12">
                                @if ($previous)
                                    <a href="{!! $previous !!}" id="left" class="nav-arrow left">&larr;<span class="sr-only">{{ trans('dielo.item_previous-work') }}</span></a>
                                @endif
                                @if ($next)
                                    <a href="{!! $next !!}" id="right" class="nav-arrow right">&rarr;<span class="sr-only">{{ trans('dielo.item_next-work') }}</span></a>
                                @endif
                            </div>

                            <div class="col-md-12 text-center">
                                @if ($item->has_iip)
                                   <a href="{{ route('item.zoom', ['id' => $item->id]) }}" class="btn btn-default btn-outline  sans"><i class="fa fa-search-plus"></i> {{ trans('general.item_zoom') }}</a>
                                @endif
                                @if ($item->isForReproduction())
                                    <a href="{!! URL::to('dielo/' . $item->id . '/objednat')  !!}" class="btn btn-default btn-outline  sans"><i class="fa fa-shopping-cart"></i> {{ trans('dielo.item_order') }} </a>
                                @endif
                                @if ($item->isFreeDownload())
                                    <a href="#" class="btn btn-default btn-outline sans"  data-toggle="modal" data-target="#downloadForm"><i class="fa fa-download"></i> {{ trans('dielo.item_download') }} </a>
                                @endif
                            </div>
                            @if (!empty($item->description))
                            <div class="col-md-12 text-left medium description top-space bottom-space underline expandable-long" itemprop="description">
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
                                    <td class="atribut">{{ trans('dielo.item_attr_dating') }}:</td>
                                    <td><time itemprop="dateCreated" datetime="{!! $item->date_earliest !!}">{!! $item->dating; !!}</time></td>
                                </tr>
                                @if (!empty($item->measurements))
                                <tr>
                                    <td class="atribut">{{ trans('dielo.item_attr_measurements') }}:</td>
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
                                @endif
                                @if (!empty($item->topics))
                                <tr>
                                    <td class="atribut">{{ trans('dielo.item_attr_topic') }}:</td>
                                    <td>
                                    @foreach ($item->topics as $topic)
                                        <a href="{!! URL::to('katalog?topic=' . $topic) !!}">{!! $topic !!}</a><br>
                                    @endforeach
                                    </td>
                                </tr>
                                @endif
                                @if ($item->tagNames() )
                                <tr>
                                    <td class="atribut">{{ trans('dielo.item_attr_tag') }}:</td>
                                    <td>
                                    @foreach ($item->tagNames() as $tag)
                                        <a href="{!!URL::to('katalog?tag=' . $tag)!!}" class="btn btn-default btn-xs btn-outline">{!! $tag !!}</a>
                                    @endforeach
                                    </td>
                                </tr>
                                @endif
                                @if ($item->collections->count())
                                <tr>
                                    <td class="atribut">{{ trans('dielo.item_attr_collections') }}:</td>
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
                                    <td class="atribut">{{ trans('dielo.item_attr_medium') }}:</td>
                                    <td>
                                    @foreach ($item->mediums as $medium)
                                        <a href="{!! URL::to('katalog?medium=' . $medium) !!}">{!! addMicrodata($medium, "artMedium") !!}</a><br>
                                    @endforeach
                                    </td>
                                </tr>
                                @endif
                                @if (!empty($item->techniques))
                                <tr>
                                    <td class="atribut">{{ trans('dielo.item_attr_technique') }}:</td>
                                    <td>
                                    @foreach ($item->techniques as $technique)
                                        <a href="{!! URL::to('katalog?technique=' . $technique) !!}">{!! $technique !!}</a><br>
                                    @endforeach
                                    </td>
                                </tr>
                                @endif
                                @if (!empty($item->integrity))
                                <tr>
                                    <td class="atribut">{{ trans('dielo.item_attr_state_edition') }}:</td>
                                    <td>{!! $item->state_edition; !!}</td>
                                </tr>
                                @endif
                                @if (!empty($item->integrity))
                                <tr>
                                    <td class="atribut">{{ trans('dielo.item_attr_integrity') }}:</td>
                                    <td>{!! $item->integrity; !!}</td>
                                </tr>
                                @endif
                                @if (!empty($item->integrity_work))
                                <tr>
                                    <td class="atribut">{{ trans('dielo.item_attr_integrity_work') }}:</td>
                                    <td>{!! $item->integrity_work; !!}</td>
                                </tr>
                                @endif
                                @if (!empty($item->inscription))
                                <tr>
                                    <td class="atribut">{{ trans('dielo.item_attr_inscription') }}:</td>
                                    <td><div class="expandable">{!! implode('<br> ', $item->makeArray($item->inscription));!!}</div></td>
                                </tr>
                                @endif
                                @if (!empty($item->gallery))
                                <tr>
                                    <td class="atribut">{{ trans('dielo.item_attr_gallery') }}:</td>
                                    <td><a href="{!! URL::to('katalog?gallery=' . $item->gallery) !!}">{!! $item->gallery; !!}</a></td>
                                </tr>
                                @endif
                                @if (!empty($item->identifier))
                                <tr>
                                    <td class="atribut">{{ trans('dielo.item_attr_identifier') }}:</td>
                                    <td>{!! $item->identifier; !!}</td>
                                </tr>
                                @endif
                                @if (!empty($item->gallery_collection))
                                <tr>
                                    <td class="atribut">{{ trans('katalog.filters_gallery_collection') }}:</td>
                                    <td>
                                        <a href="{!! URL::to('katalog?gallery_collection=' . $item->gallery_collection) !!}">{!! $item->gallery_collection; !!}</a>
                                    </td>
                                </tr>
                                @endif
                                @if ($item->isFree() || !$item->has_rights)
                                <tr>
                                    <td class="atribut">{{ trans('dielo.item_attr_licence') }}:</td>
                                    <td>
                                        @if (!$item->has_rights)
                                            {{ trans('general.copyrighted_work') }}
                                            <p class="small"><i>{{ trans('general.copyrighted_work_explanation') }}</i></p>
                                        @else
                                            <a rel="license" href="{!!URL::to('katalog?is_free=' . '1')!!}" target="_blank" class="no-border license" title="Public Domain" data-toggle="tooltip"><img alt="Creative Commons License" style="height: 20px; width: auto"  src="/images/license/zero.svg" > {{ trans('general.public_domain') }}</a></td>
                                        @endif
                                </tr>
                                @endif
                                @if (!empty($item->place))
                                <tr>
                                    <td class="atribut">{{ trans('dielo.item_attr_place') }}:</td>
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
                        <?php $related_items = App\Item::where('related_work', '=', $item->related_work)->where('author', '=', $item->author)->orderBy('related_work_order')->get(); ?>
                        @if ($related_items->count() > 1)
                        <div style="position: relative; padding: 0 10px;">
                            @include('components.artwork_carousel', [
                                'slick_target' => "artworks-preview",
                                'slick_variant' => "small",
                                'items' => $related_items,
                            ])
                        </div>
                        @endif
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

@if ($colors_used || $similar_by_color)
<section class="content-section">
    <div class="container">
        <div class="row">
            <div class="col-xs-12">
                <h4>{{ trans('dielo.more-items_similar-colors') }}</h4>
                @if ($colors_used)
                @include('components.color_list', ['colors' => $colors_used])
                @endif
                @if ($similar_by_color)
                    @include('components.artwork_carousel', [
                        'slick_target' => "artworks-preview",
                        'items' => $similar_by_color,
                    ])
                @endif
            </div>
        </div>
    </div>
</section>
@endif

@if (!empty($more_items))
<section class="more-items content-section">
    <div class="container">
        <div class="row">
            <div class="col-xs-12">
                <h4>{{ trans('dielo.more-items_related-artworks') }}</h4>
                @include('components.artwork_carousel', [
                    'slick_target' => "artworks-preview",
                    'items' => $more_items,
                ])
            </div>
        </div>
    </div>
</section>
@endif


<!-- Modal -->
<div tabindex="-1" class="modal fade" id="downloadForm" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            {!!
              Former::open()->route('objednavka.download')->class('form-bordered form-horizontal')->id('order')->rules(App\Download::$rules);
            !!}

            <div class="modal-header text-center">
                <h2>{{ trans('dielo.item_download') }}</h2>
            </div>
            <div class="modal-body">

                <label for="download-type">{{ trans('download.choose_type') }}</label>
                <div class="btn-group btn-group-justified bottom-space" role="group" aria-label="chooseDownloadType" id="download-type">
                  <a href="#" role="button" class="btn btn-default" data-type="private">{{ utrans('download.private') }}</a>
                  <a href="#" role="button" class="btn btn-default" data-type="publication">{{ utrans('download.publication') }}</a>
                  <a href="#" role="button" class="btn btn-default" data-type="commercial">{{ utrans('download.commercial') }}</a>
                </div>

                {!! Former::hidden('item_id')->value($item->id); !!}
                {!! Former::hidden('type'); !!}

                {{-- comercial --}}
                <div class="publication commercial form-hide">
                {!! Former::text('company')->label(trans('download.form_company'))->required(); !!}
                {!! Former::text('address')->label(trans('download.form_address'))->required(); !!}
                {!! Former::text('country')->label(trans('download.form_country'))->required(); !!}
                {!! Former::text('contact_person')->label(trans('download.form_contact_person'))->required(); !!}
                {!! Former::text('email')->label(trans('download.form_email'))->required(); !!}
                {!! Former::text('phone')->label(trans('download.form_phone')); !!}
                </div>

                <div class="publication form-hide">
                {!! Former::text('publication_name')->label(trans('download.form_publication_name'))->required(); !!}
                {!! Former::text('publication_author')->label(trans('download.form_publication_author')); !!}
                {!! Former::text('publication_year')->label(trans('download.form_publication_year'))->required(); !!}
                {!! Former::text('publication_print_run')->label(trans('download.form_publication_print_run')); !!}

                <div class="form-group">
                    <div class="col-lg-2 col-sm-4">&nbsp;</div>
                    <div class="col-lg-10 col-sm-8">
                        <div class="checkbox">
                            <input id="confirm_sending_prints" name="confirm_sending_prints" type="checkbox" value="1" required>
                            <label for="confirm_sending_prints">
                              {!! trans('download.form_confirm_sending_prints') !!}
                              <address class="dib">{!! trans('download.form_confirm_sending_prints_address') !!}</address>
                              <sup>*</sup>
                            </label>
                        </div>
                    </div>
                </div>

                </div>

                <div class="publication commercial form-hide">
                {!! Former::textarea('note')->label(trans('download.form_note')); !!}

                <div class="form-group">
                    <div class="col-lg-2 col-sm-4">&nbsp;</div>
                    <div class="col-lg-10 col-sm-8">
                        <div class="checkbox">
                            <input id="terms_and_conditions" name="terms_and_conditions" type="checkbox" value="1" required>
                            <label for="terms_and_conditions">
                              {!! trans('download.form_terms_and_conditions') !!}
                              <sup>*</sup>
                            </label>
                        </div>
                    </div>
                </div>

                </div>



            </div>
            <div class="modal-footer">
                <div class="text-center">
                    {!! Form::submit(trans('dielo.item_download'), [
                        'class'=>'btn btn-primary uppercase sans',
                        'disabled'=>'disabled',
                    ]) !!}
                    <button type="button" data-dismiss="modal" class="btn btn-default btn-outline uppercase sans">{{ trans('general.close') }}</button>
                    {{--
                    {!! Former::actions(
                        Form::submit(trans('dielo.item_download'), [
                            'class'=>'btn btn-primary uppercase sans',
                            'disabled'=>'disabled',
                        ]),
                        '<button type="button" data-dismiss="modal" class="btn btn-default btn-outline uppercase sans">'.trans('general.close') .'</button>'
                    ) !!}
                     --}}
                </div>
            </div>
            {!!Former::close();!!}
        </div>
    </div>
</div>
<!-- Modal -->
<div tabindex="-1" class="modal fade" id="downloadfail" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header text-center">
                {{ trans('dielo.modal_downloadfail_header-content') }}
            </div>
            <div class="modal-body">
                {{ trans('dielo.modal_downloadfail_body-content') }}
            </div>
            <div class="modal-footer">
                <div class="text-center"><button type="button" data-dismiss="modal" class="btn btn-default btn-outline uppercase sans">{{ trans('general.close') }}</button></div>
            </div>
        </div>
    </div>
</div>

<!-- <div id="map"></div> -->

@stop


@section('javascript')
{!! Html::script('js/readmore.min.js') !!}
{!! Html::script('js/jquery.fileDownload.js') !!}

@include('components.artwork_carousel_js', ['slick_query' => '.artworks-preview'])

@if (!empty($item->lat) && ($item->lat > 0))
    <!-- Google Maps API Key - You will need to use your own API key to use the map feature -->
    <script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCRngKslUGJTlibkQ3FkfTxj3Xss1UlZDA&sensor=false"></script>
    {!! Html::script('js/gmaps.js') !!}
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

        var moreLinkText = '<a href="#"><i class="fa fa-chevron-down"></i> {{ trans("general.show_more") }}</a>';
        var lessLinkText = '<a href="#"><i class="fa fa-chevron-up"></i> {{ trans("general.show_less") }}</a>';

        $('.expandable').readmore({
            moreLink: moreLinkText,
            lessLink: lessLinkText,
            maxHeight: 40
        });

        $('.expandable-long').readmore({
            moreLink: moreLinkText,
            lessLink: lessLinkText,
            maxHeight: 160
        });

        $('div.form-hide').hide();

        $("#download-type .btn").click(function(e){
            e.preventDefault();
            $('div.form-hide').fadeOut();
            var type = $(this).data('type');
            console.log(type);
            // set type by button
            $('input[name=type]').val(type);
            // show parts according the type
            $('div.'+type).fadeIn();

            // enable download if validation pass
            $(this).closest('form').find(':submit').prop("disabled", false);


        });

        $('#download').on('click', function(e){

            // $('#license').modal({})
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
