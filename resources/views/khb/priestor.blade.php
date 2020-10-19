@extends('layouts.master')

@section('og')
<meta property="og:title" content="{!! $space->name !!}" />

<meta property="og:description" content="{!! $space->getDescription(false, false, true) !!}" />
<meta property="og:type" content="object" />
<meta property="og:url" content="{!! Request::url() !!}" />
<meta property="og:image" content="{!! URL::to( $space->getImagePath() ) !!}" />
<meta property="og:site_name" content="{{ trans('master.meta-title') }}" />
@stop

@section('title')
{!! $space->name !!} |
@parent
@stop

@section('description')
    <meta name="description" content="{!! $space->getDescription(false, false, true) !!}">
@stop

@section('content')

<section class="space detail">
    <div class="attributes">
        <div class="row">
            <div class="col-12 col-md-4 col-xl-2 py-0">
                <div class="row">
                    <div class="col border-right-0">
                        <h1 class="mt-2 mb-4">{!! $space->name !!}</h1>
                    </div>
                </div>
                <div class="row">
                    <div class="col p-0 border-right-0">
                        <img src="{!! $space->getImagePath() !!}" class="img-fluid" alt="{!! $space->name !!}"  itemprop="image">
                    </div>
                </div>
                <div class="row">
                    <div class="col border-right-0 border-bottom-0">
                        @if ($space->address)
                            <p>
                                {{ trans('priestor.address') }} <br>
                                {{ $space->address }}
                            </p>
                        @endif
                        @if ($space->opened_date)
                            <p>
                                {{ trans('priestor.opened_date') }} <br>
                                {{ $space->opened_date->format('d.m.Y') }}
                            </p>
                        @endif
                        @if ($space->opened_place)
                            <p>
                                {{ trans('priestor.opened_place') }} <br>
                                {{ $space->opened_place }}
                            </p>
                        @endif
                        @if ($space->closed_date)
                            <p>
                                {{ trans('priestor.closed_date') }} <br>
                                {{ $space->closed_date->format('d.m.Y') }}
                            </p>
                        @endif
                        @if (!empty($space->tagNames()))
                            <p>
                                {{ trans('priestor.tags') }} <br>
                                @foreach ($space->tags as $tag)
                                    <a href="{!!URL::to('klucove-slova#' . $tag->slug)!!}" class="mr-1">#{!! $tag->name !!}</a>
                                @endforeach
                            </p>
                        @endif
                        @if ( $space->linksForLocale->count() > 0)
                            <div class="links">
                                {{ utrans('priestor.links') }}<br>
                                @foreach ($space->linksForLocale as $i=>$link)
                                    <a href="{{ $link->url }}" target="_blank">{{ $link->label }}</a><br>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-8 col-xl-10 popis p-0">
                <div class="accordion" id="spaceAccordion">
                    @include('components.khb_accordion_card', [
                        'title' => utrans('priestor.description'),
                        'content' => $space->description,
                        'parrentId' => 'spaceAccordion',
                        'show' => true,
                    ])

                    @include('components.khb_accordion_card', [
                        'title' => utrans('priestor.exhibitions'),
                        'content' => $space->exhibitions,
                        'parrentId' => 'spaceAccordion',
                        'show' => false,
                    ])

                    @include('components.khb_accordion_card', [
                        'title' => utrans('priestor.bibliography'),
                        'content' => $space->bibliography,
                        'parrentId' => 'spaceAccordion',
                        'show' => false,
                    ])
                    {{-- @include('components.khb_accordion_card', [
                        'title' => utrans('priestor.archive'),
                        'content' => $space->archive,
                        'parrentId' => 'spaceAccordion',
                        'show' => false,
                    ]) --}}

                    {{-- archive --}}
                    @if (!empty($space->archive) || $archive->count() > 0)
                    <div class="card">
                      <div class="card-header" id="heading{{ studly_case('archive') }}">
                        <h5 class="mb-0">
                          <button class="btn btn-link font-weight-bold p-0" type="button" data-toggle="collapse" data-target="#collapse{{ studly_case('archive') }}" aria-expanded="true" aria-controls="collapse{{ studly_case('archive') }}">
                            {{ utrans('priestor.archive') }}
                          </button>
                        </h5>
                      </div>

                      <div id="collapse{{ studly_case('archive') }}" class="collapse {{-- show --}}" aria-labelledby="heading{{ studly_case('archive') }}" data-parent="#spaceAccordion">
                        <div class="card-body pt-0">

                            <div class="expandable">
                                {!! $space->archive !!}
                            </div>

                            <div id="isoArchive" class="grid-container">
                            @foreach ($archive as $i=>$document)
                                <div class="col-md-3 col-sm-4 col-xs-6 item border-0">
                                    <a href="{!! $document->getUrl() !!}" title="{!! $document->name !!}" target="_blank" class="text-center">
                                        @php
                                            list($width, $height) = getimagesize(public_path() . $document->getUrl('thumb_m'));
                                        @endphp
                                        <div class="ratio-box" style="padding-bottom: {{ round(($height / $width) * 100, 4) }}%;">
                                            <img
                                            data-sizes="auto"
                                            data-src="{!! $document->getUrl('thumb_m') !!}"
                                            data-srcset="
                                                    {!! $document->getUrl('thumb_s') !!} 300w,
                                                    {!! $document->getUrl('thumb_m') !!} 600w,
                                                    {!! $document->getUrl('thumb_l') !!} 800w"
                                            class="lazyload"
                                            alt="{!! $document->name !!} ">
                                        </div>
                                        {{ $document->name }}
                                    </a>
                                </div>
                            @endforeach
                            </div>
                            {{-- /isoArchive --}}
                        </div>
                      </div>
                    </div>
                    @endif
                    {{-- /archive --}}


                </div>

            </div>
        </div>{{-- row --}}
    </div> {{-- /attributes --}}
</section>
@stop

@section('javascript')
<script src="https://unpkg.com/isotope-layout@3/dist/isotope.pkgd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/magnific-popup.js/1.1.0/jquery.magnific-popup.min.js"></script>
{!! Html::script('js/plugins/readmore.min.js') !!}

<script type="text/javascript">
    function init_isotype(containerSelector, itemSelector) {
        $(containerSelector).isotope({
            itemSelector: itemSelector,
            layoutMode: 'masonry'
        });
    }

    $(document).ready(function(){
        init_isotype('#iso', '.item');
        init_isotype('#isoArchive', '.item');
        // re-init isotype when gallery collapsed accordion card has been shown
        $('#headingGallery').parent().on('shown.bs.collapse', function () {
            init_isotype('#iso', '.item');
        })

        $('#headingArchive').parent().on('shown.bs.collapse', function () {
            init_isotype('#isoArchive', '.item');
        })

        $('.expandable').readmore({
            moreLink: '<a href="#" class="no-underline text-right">{{ trans("general.show_more") }} <i class="fas fa-chevron-right"></i></a>',
            lessLink: '<a href="#" class="no-underline text-right">{{ trans("general.show_less") }} <i class="fas fa-chevron-up"></i></a>',
            maxHeight: 400,
            afterToggle: function(trigger, element, expanded) {
                if(! expanded) { // The "Close" link was clicked
                    $('html, body').animate( { scrollTop: element.offset().top }, {duration: 100 } );
                }
            }
        });

        $('#iso').magnificPopup({
            delegate: '.item a',
            type: 'iframe',
            mainClass: 'mfp-with-zoom',
            gallery: {
                enabled: true,
                navigateByImgClick: true,
                tCounter: '<span class="mfp-counter">%curr% {{ trans('priestor.of') }} %total%</span>',
                preload: [1,1] // Will preload 0 - before current, and 1 after the current image
            },
            iframe: {
                markup:
                    '<div class="mfp-iframe-scaler mfp-iframe-big">'+
                        '<div class="mfp-close"></div>'+
                        '<iframe class="mfp-iframe" frameborder="0" allowfullscreen></iframe>'+
                        '<div class="mfp-title"></div>'+
                        '<div class="mfp-counter"><span class="mfp-counter"></span></div>'+
                    '</div>' // HTML markup of popup, `mfp-close` will be replaced by the close button
            },
            image: {
                tError: '<a href="%url%">The image #%curr%</a> could not be loaded.',
                titleSrc: function(item) {
                    var title = item.el.attr('title');
                    if (item.el.attr('data-sub-title')) {
                        title = title + '<small>'+ item.el.attr('data-sub-title') +'</small>';
                    }
                    if (item.el.attr('data-photo-credit')) {
                        title = title + '<small>{{ trans('priestor.by') }} '+ item.el.attr('data-photo-credit') +'</small>';
                    }
                    return title;
                }
            },
            zoom: {
                enabled: true,
                duration: 300,
                easing: 'ease-in-out',
                opener: function(openerElement) {
                    return openerElement.is('img') ? openerElement : openerElement.find('img');
                }
            },
            callbacks: {
                markupParse: function(template, values, item) {
                    var title = item.el.attr('title');
                    if (item.el.attr('data-sub-title')) {
                        title = title + '<small>'+ item.el.attr('data-sub-title') +'</small>';
                    }
                    if (item.el.attr('data-photo-credit')) {
                        title = title + '<small>{{ trans('priestor.by') }} '+ item.el.attr('data-photo-credit') +'</small>';
                    }
                    values.title = title;
                },
                elementParse: function(item) {
                    var className = item.el[0].className;
                    var id = item.el[0].dataset.id;
                    // switch image iframe / zoom viewer here
                    if (className == 'zoom-viewer') {
                        item.type = 'iframe';
                        item.src = '{{ url('/') }}/dielo/'+id+'/zoom?noreturn=1';
                    } else if (className == 'img-viewer') {
                        item.type = 'image';
                    }
                }
            }
        });
    });
</script>
@stop
