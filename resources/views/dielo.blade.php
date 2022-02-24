@extends('layouts.master')

@section('og')
<meta property="og:title" content="{{ $item->getTitleWithAuthors() }}" />
<meta property="og:description"
      content="{{ $item->work_type }}, {{ trans('dielo.item_attr_dating') }}: {{ $item->dating }}, {{ trans('dielo.item_attr_measurements') }}: {{  implode(' x ', $item->measurements) }}" />
<meta property="og:type" content="object" />
<meta property="og:url" content="{{ Request::url() }}" />
<meta property="og:image" content="{{ asset_timed( $item->getImagePath() ) }}" />
@stop

@section('title')
{{ $item->getTitleWithAuthors() }} |
@parent
@stop

@section('description')
<meta name="description"
      content="{{ $item->work_type }}, {{ trans('dielo.item_attr_dating') }}: {{ $item->dating }}, {{ trans('dielo.item_attr_measurements') }}: {{  implode(' x ', $item->measurements) }}">
@stop

@section('link')
<link rel="canonical" href="{{ $item->getUrl() }}">
@stop

@section('head-javascript')
{{-- For WEBUMENIA-1462 --}}
{{ Html::script('js/soundcloud.api.js') }}
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
                <div class="alert alert-info alert-dismissable"><button type="button" class="close" data-dismiss="alert"
                            aria-hidden="true">&times;</button>{!! Session::get('message') !!}</div>
                @endif
                <div class="col-md-10 col-md-offset-1 text-center content-section mb-3">
                    <h1 class="nadpis-dielo" itemprop="name">{{ $item->title }}</h1>
                    <h2 class="inline">
                        @foreach($item->authors_with_authorities as $author)
                            @include('components.item_author')@if (!$loop->last), @endif
                        @endforeach
                    </h2>
                </div>
            </div>
            <div class="row img-dielo">
                <div class="col-md-8 text-center">
                    @if ($item->has_iip)
                    @php
                    list($width, $height) = getimagesize(public_path() . $item->getImagePath());
                    @endphp

                    <a class="zoom" href="{{ route('item.zoom', ['id' => $item->id]) }}">
                        <i class="fa fa-search-plus"></i>
                    </a>

                    {{-- @TODO: remove this after IIP is fast enoght to handle it --}}
                    @if ($item->images->count() == 1)
                    <a href="{{ route('item.zoom', ['id' => $item->id]) }}" data-toggle="tooltip" data-placement="top"
                       title="{{ utrans('general.item_zoom') }}">
                        @include('components.item_image_responsive', [
                        'item' => $item,
                        'limitHeight' => '90vh'
                        ])
                    </a>
                    @else
                    @include('components.image_carousel', [
                    'slick_target' => "multiple-views",
                    'slick_variant' => "artwork-detail-thumbnail",
                    'img_urls' => $item->images->map(function ($image) {
                    return $image->getPreviewUrl();
                    }),
                    'img_title' => $item->getTitleWithAuthors(),
                    'anchor_href' => route('item.zoom', ['id' => $item->id]),
                    'anchor_title' => utrans('general.item_zoom'),
                    ])
                    @endif

                    @else
                    @php
                    list($width, $height) = getimagesize(public_path() . $item->getImagePath());
                    @endphp
                    @include('components.item_image_responsive', [
                    'item' => $item ,
                    'limitHeight' => '80vh'
                    ])
                    @endif
                    <div class="row mt-5">
                        <div class="col-sm-12">
                            @if ($previous)
                            <a href="{{ $previous }}" id="left" class="nav-arrow left">&larr;<span
                                      class="sr-only">{{ trans('dielo.item_previous-work') }}</span></a>
                            @endif

                            @if ($next)
                            <a href="{{ $next }}" id="right" class="nav-arrow right">&rarr;<span
                                      class="sr-only">{{ trans('dielo.item_next-work') }}</span></a>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="col-md-4 text-left">
                    <table class="table attributes">
                        <tbody>
                            <tr>
                                <td class="atribut">{{ trans('dielo.item_attr_dating') }}:</td>
                                <td>
                                    <time itemprop="dateCreated" datetime="{{ $item->date_earliest }}">
                                        {{ $item->getDatingFormated() }}
                                    </time>
                                </td>
                            </tr>
                            @if (!empty($item->measurements))
                            <tr>
                                <td class="atribut">{{ trans('dielo.item_attr_measurements') }}:</td>
                                <td>
                                    @foreach ($item->measurements as $measurement)
                                        {{ $measurement }}<br>
                                    @endforeach
                                </td>
                            </tr>
                            @endif
                            @if (!empty($item->work_type))
                                <tr>
                                    <td class="atribut">{{ trans('dielo.item_attr_work_type') }}:</td>
                                    <td>
                                        @foreach ($item->work_types as $stack)
                                            @foreach ($stack as $work_type)
                                                <a href="{{ route('frontend.catalog.index', ['work_type' => $work_type['path']]) }}"><span itemprop="artform">{{ $work_type['name'] }}</span></a>
                                                @if (!$loop->last)
                                                    &rsaquo;
                                                @endif
                                            @endforeach
                                            @if (!$loop->last)
                                                <br>
                                            @endif
                                        @endforeach
                                    </td>
                                </tr>
                            @endif
                            @if (!empty($item->object_type))
                                <tr>
                                    <td class="atribut">{{ trans('dielo.item_attr_object_type') }}:</td>
                                    <td>
                                        @foreach ($item->object_types as $stack)
                                            @foreach ($stack as $object_type)
                                                <a href="{{ route('frontend.catalog.index', ['object_type' => $object_type['path']]) }}">{{ $object_type['name'] }}</a>
                                                @if (!$loop->last)
                                                    &rsaquo;
                                                @endif
                                            @endforeach
                                            @if (!$loop->last)
                                                <br>
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
                                    <a href="{{ URL::to('katalog?topic=' . $topic) }}">{{ $topic }}</a><br>
                                    @endforeach
                                </td>
                            </tr>
                            @endif
                            @if (!empty($item->mediums))
                            <tr>
                                <td class="atribut">{{ trans('dielo.item_attr_medium') }}:</td>
                                <td>
                                    @foreach ($item->mediums as $medium)
                                    <a href="{{ URL::to('katalog?medium=' . $medium) }}">
                                        {!! addMicrodata($medium, "artMedium") !!}
                                    </a><br>
                                    @endforeach
                                </td>
                            </tr>
                            @endif
                            @if (!empty($item->techniques))
                            <tr>
                                <td class="atribut">{{ trans('dielo.item_attr_technique') }}:</td>
                                <td>
                                    @foreach ($item->techniques as $technique)
                                    <a href="{{ URL::to('katalog?technique=' . $technique) }}">{{ $technique
                                        }}</a><br>
                                    @endforeach
                                </td>
                            </tr>
                            @endif
                            @if (!empty($item->integrity))
                            <tr>
                                <td class="atribut">{{ trans('dielo.item_attr_state_edition') }}:</td>
                                <td>{{ $item->state_edition }}</td>
                            </tr>
                            @endif
                            @if (!empty($item->integrity))
                            <tr>
                                <td class="atribut">{{ trans('dielo.item_attr_integrity') }}:</td>
                                <td>{{ $item->integrity }}</td>
                            </tr>
                            @endif
                            @if (!empty($item->integrity_work))
                            <tr>
                                <td class="atribut">{{ trans('dielo.item_attr_integrity_work') }}:</td>
                                <td>{{ $item->integrity_work }}</td>
                            </tr>
                            @endif
                            @if (!empty($item->inscription))
                            <tr>
                                <td class="atribut">{{ trans('dielo.item_attr_inscription') }}:</td>
                                <td>
                                    <div class="expandable">{!! implode('<br> ',
                                        $item->makeArray($item->inscription)); !!}</div>
                                </td>
                            </tr>
                            @endif
                            @if (!empty($item->gallery))
                            <tr>
                                <td class="atribut">{{ trans('dielo.item_attr_gallery') }}:</td>
                                <td><a href="{{ URL::to('katalog?gallery=' . $item->gallery) }}">{{ $item->gallery }}</a></td>
                            </tr>
                            @endif
                            @if (!empty($item->contributor))
                            <tr>
                                <td class="atribut">{{ trans('dielo.item_attr_contributor') }}:</td>
                                <td><a href="{{ route('frontend.catalog.index', ['contributor' => $item->contributor]) }}">{{ formatName($item->contributor) }}</a></td>
                            </tr>
                            @endif
                            @if (!empty($item->identifier))
                            <tr>
                                <td class="atribut">{{ trans('dielo.item_attr_identifier') }}:</td>
                                <td>{{ $item->identifier }}</td>
                            </tr>
                            @endif
                            @if ($item->tagNames() || Auth::check())
                            <tr>
                                <td class="atribut">{{ trans('dielo.item_attr_tag') }}:</td>
                                <td class="multiline">

                                    <!-- list of existing tags -->
                                    @foreach ($item->tagNames() as $tag)
                                    <a href="{{URL::to('katalog?tag=' . $tag)}}"
                                       class="btn btn-default btn-xs btn-outline">{{ $tag }}</a>
                                    @endforeach

                                    @if (Auth::check())
                                    @include('includes.add_tags_form')
                                    @endif

                                </td>
                            </tr>
                            @endif
                            @if ($item->collections->count())
                            <tr>
                                <td class="atribut">{{ trans('dielo.item_attr_collections') }}:</td>
                                <td>
                                    <div class="expandable multiline">
                                        @foreach ($item->collections as $collection)
                                        <a href="{{ $collection->getUrl() }}">{{ $collection->name }}</a><br/>
                                        @endforeach
                                    </div>
                                </td>
                            </tr>
                            @endif
                            @if ($item->isFree() && !$item->images->isEmpty())
                            <tr>
                                <td class="atribut">{{ trans('dielo.item_attr_licence') }}:</td>
                                {{-- <td><a rel="license" href="http://creativecommons.org/licenses/by-nc-sa/4.0/deed.cs" target="_blank" class="no-border"><img alt="Creative Commons License" style="border-width:0; padding-top: 2px;"  src="/images/license/by-nc-sa.svg" title="Creative Commons BY-NC-SA 4.0" data-toggle="tooltip"></a></td> --}}
                                <td><a rel="license" href="{{URL::to('katalog?is_free=' . '1')}}" target="_blank"
                                       class="no-border license" title="Public Domain" data-toggle="tooltip"><img
                                             alt="Creative Commons License" style="height: 20px; width: auto"
                                             src="{{ asset('/images/license/zero.svg') }}">
                                        {{ trans('general.public_domain') }}</a>
                                </td>
                            </tr>
                            @endif
                            @if (!empty($item->place))
                            <tr>
                                <td class="atribut">{{ trans('dielo.item_attr_place') }}:</td>
                                <td>{{ $item->place }}</td>
                            </tr>
                            @endif
                            @if (!empty($item->related_work))
                            <tr>
                                <td class="atribut">{{ $item->relationship_type ?: trans('dielo.default_relationship_type') }}:</td>
                                <td>
                                    <a href="{{ route('frontend.catalog.index', ['related_work' => $item->related_work, 'author' => $item->first_author]) }}"
                                       itemprop="isPartOf">{{ $item->related_work }}</a>
                                    @if ($item->related_work_order)
                                    ({{ $item->related_work_order }}/{{ $item->related_work_total }})
                                    @endif
                                </td>
                            </tr>
                            @endif
                            @if (!empty($item->credit))
                            <tr>
                                <td class="atribut">{{ trans('dielo.item_attr_credit') }}:</td>
                                <td>
                                    <a href="{{ URL::to('katalog?credit=' . $item->credit) }}">{{ $item->credit }}</a>
                                </td>
                            </tr>
                            @endif
                            @if (!empty($item->acquisition_date))
                            <tr>
                                <td class="atribut">{{ trans('dielo.item_attr_acquisition_date') }}:</td>
                                <td>{{ $item->acquisition_date }}</td>
                            </tr>
                            @endif
                        </tbody>
                    </table>
                    @if (!empty($item->lat) && ($item->lat > 0))
                    <div id="small-map"></div>
                    @endif

                    <div class="col-md-12 text-center">
                        <user-collections-favourite-button
                            label-add="{{ utrans('general.item_add_to_favourites') }}"
                            label-remove="{{ utrans('general.item_remove_from_favourites') }}"
                            id="{{ $item->id }}"
                            is-detail=true
                        ></user-collections-favourite-button>
                        @if ($item->isForReproduction())
                        <a href="{{ URL::to('dielo/' . $item->id . '/objednat')  }}"
                           class="btn btn-cta btn-default btn-outline sans w-100"><i class="fa fa-shopping-cart"></i>
                            {{ trans('dielo.item_order') }} </a>
                        @endif
                        @if ($item->isFree() && !$item->images->isEmpty())
                        <a href="{{ URL::to('dielo/' . $item->id . '/stiahnut')  }}"
                           class="btn btn-cta btn-default btn-outline sans w-100" id="download"><i
                               class="fa fa-download"></i>
                            {{ trans('dielo.item_download') }} </a>
                        @endif

                        @include('components.share_buttons', [
                            'title' => $item->getTitleWithAuthors(),
                            'url' => $item->getUrl(),
                            'img' => URL::to($item->getImagePath()),
                            'citation' => collect([$item->getTitleWithAuthors(), $item->getDatingFormated(), $item->gallery, $item->identifier, URL::current()])->filter()->join(', '),
                        ])
                    </div>
                </div>
            </div>
            <div class="row">
                @if (!empty($item->description))
                <div class="col-lg-8 col-md-10 long-text medium description bottom-space  col-md-push-1 col-lg-push-2"
                     itemprop="description">
                    <div class="long_expandable">
                        {!! $item->description !!}

                        @if ($item->description_source)
                        <p>
                            @if ($item->description_user_id)
                            {{-- Autor popisu: --}} {{ $item->descriptionUser->name }} &#9679;
                            @endif
                            @if ($item->description_source_link)
                            {{-- Zdroj: --}}
                            <a href="{{ $item->description_source_link }}" target="_blank">{{
                                $item->description_source }}</a>
                            @else
                            {{ $item->description_source }}
                            @endif
                        </p>
                    </div>
                    @endif
                </div>
                @endif
            </div>
        </div>
    </div>
</section>

<section class="more-items content-section">
    @if ($related_items && $related_items->count() > 1)
    <div class="container-fluid related-works">
        <div class="container">
            <div class="row">
                <div class="col-sm-12">
                    <h3 class="underlined-links mb-4 mt-5">
                        <span class="grey">{{ $item->relationship_type }}: </span>
                        <a href="{{ route('frontend.catalog.index', ['related_work' => $item->related_work, 'author' => $item->first_author]) }}"
                           itemprop="isPartOf">{{ $item->related_work }}</a>
                        @if ($item->related_work_order)
                        ({{ $item->related_work_order }}/{{ $item->related_work_total }})
                        @endif
                    </h3>

                    @include('components.artwork_carousel', [
                    'slick_target' => "artworks-preview",
                    'slick_variant' => "large",
                    'items' => $related_items,
                    'class_names' => 'mb-5'
                    ])

                </div>
            </div>
        </div>
    </div>
    @endif
    <div class="container">
        <div class="row">
            <div class="{{ $item->has_colors ? 'col-sm-6 pr-sm-5' : 'col-xs-12'}}" id="related-by-metadata">
                <div class="tailwind-rules">
                    <div class="tw-h-24 tw-text-xl">
                        <h3 class="tw-mt-6">
                            {{ utrans('dielo.more-items_related-artworks') }}
                        </h3>
                        <span class="tw-text-gray-500 tw-font-semibold tw-inline-block tw-mt-2">
                            {{ trans('dielo.more-items_related-artworks_by-data') }}
                        </span>
                    </div>
                </div>
                <div class="isotope-container">
                    @foreach ($similar_items as $similar_item)
                        @include('components.artwork_grid_item', [
                            'item' => $similar_item,
                            'isotope_item_selector_class' => 'item',
                            'class_names' => ($item->has_colors) ? 'col-xs-6' : 'col-xs-3',
                            'hide_zoom' => true,
                            'hide_dating' => true
                        ])
                    @endforeach
                </div>
            </div>
            @if ($item->has_colors)
            <div class="col-sm-6 pl-sm-5" id="related-by-color">
                <div class="tailwind-rules">
                    <div class="tw-h-24 tw-text-xl">
                        <h3 class="tw-mt-6 tw-mb-1">{{ utrans('dielo.more-items_similar-colors') }}</h3>
                        @include('components.color_list', ['colors' => $item->getColors()])
                    </div>
                </div>
                <div class="isotope-container" data-fetch-url="{{ route('dielo.colorrelated', ['id' => $item->id]) }}">
                </div>
            </div>
            @endif
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
                {!! trans('dielo.modal_license_body-content', ['item_url' => $item->getUrl(), 'free_url' =>
                URL::to('katalog?is_free=1')] ) !!}
            </div>
            <div class="modal-footer">
                <div class="text-center"><button type="button" data-dismiss="modal"
                            class="btn btn-default btn-outline uppercase sans">{{ trans('general.close') }}</button>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Modal -->
<div tabindex="-1" class="modal fade" id="downloadfail" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header text-center">
                {!! trans('dielo.modal_downloadfail_header-content') !!}
            </div>
            <div class="modal-body">
                {!! trans('dielo.modal_downloadfail_body-content') !!}
            </div>
            <div class="modal-footer">
                <div class="text-center"><button type="button" data-dismiss="modal"
                            class="btn btn-default btn-outline uppercase sans">{{ trans('general.close') }}</button>
                </div>
            </div>
        </div>
    </div>
</div>

@stop


@section('javascript')
{{ Html::script('js/readmore.min.js') }}
{{ Html::script('js/jquery.fileDownload.js') }}
{{ Html::script('js/components/artwork_carousel.js') }}
{{ Html::script('js/components/share_buttons.js') }}
{{ HTML::script('js/slick.js') }}
{{-- @TODO bring this back when opened to public --}}
{{-- {{ HTML::script('https://www.google.com/recaptcha/api.js') }} --}}

{{-- Report item details to Google Tag Manager --}}
<script defer>
    dataLayer.push(@json($gtm_data_layer));
</script>

@if (!empty($item->lat) && ($item->lat > 0))
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCVG26BxGY9yhjCFbviWRgZsvxSlikOnIM&callback=initMap" async
        defer></script>
{{ Html::script('js/gmaps.js') }}
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

    // start with isotype even before document is ready
    $('.isotope-container').isotope({
        itemSelector: '.item',
        layoutMode: 'masonry'
    });

    $(document).ready(function(){
        var relatedByColorIsotope = $('#related-by-color > .isotope-container').first();

        if (relatedByColorIsotope) {
            var fetchUrl = relatedByColorIsotope.data('fetch-url')
            $.get(fetchUrl, function (data) {
                relatedByColorIsotope.isotope('insert', $(data))
            });
        }

        $('.expandable').readmore({
            moreLink: '<a href="#"><i class="fa fa-chevron-down"></i> {{ trans("general.show_more") }}</a>',
            lessLink: '<a href="#"><i class="fa fa-chevron-up"></i> {{ trans("general.show_less") }}</a>',
            maxHeight: 40
        });

        $('.long_expandable').readmore({
            moreLink: '<a href="#"><i class="fa fa-chevron-down"></i> {{ trans("general.show_more") }}</a>',
            lessLink: '<a href="#"><i class="fa fa-chevron-up"></i> {{ trans("general.show_less") }}</a>',
            maxHeight: 235
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
    });
</script>

@if (!empty($item->lat) && ($item->lat > 0))
<script type="text/javascript">
    var map;
    function initMap(){

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

    };
</script>
@endif

@if (Auth::check())
@include('includes.add_tags_form_js')
@endif

@stop
