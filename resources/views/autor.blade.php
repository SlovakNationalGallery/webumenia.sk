@extends('layouts.master')

@section('og')
<meta property="og:title" content="{!! $author->formatedName !!}" />

<meta property="og:description" content="{!! $author->getDescription(false, false, true) !!}" />
<meta property="og:type" content="object" />
<meta property="og:url" content="{!! Request::url() !!}" />
<meta property="og:image" content="{!! URL::to( $author->getImagePath() ) !!}" />
<meta property="og:site_name" content="{{ trans('master.meta-title') }}" />
@stop

@section('title')
{!! $author->formatedName !!} |
@parent
@stop

@section('description')
    <meta name="description" content="{!! $author->getDescription(false, false, true) !!}">
@stop

@section('content')

<section class="author detail" itemscope itemtype="http://schema.org/Person">
    <div class="attributes">
        <div class="row">
            <div class="col-12 col-md-4 col-xl-2 py-0">
                <div class="row">
                    <div class="col border-right-0">
                        <h1 itemprop="name" class="mt-2 mb-4">{!! $author->formatedName !!}</h1>
                    </div>
                </div>
                {{--
                @if ( $author->names->count() > 0)
                    <p class="lead">{{ trans('autor.alternative_names') }} <em>{!! implode("</em>, <em>", $author->formatedNames) !!}</em></p>
                @endif
                 --}}
                <div class="row">
                    <div class="col p-0 border-right-0">
                        <img src="{!! $author->getImagePath() !!}" class="img-fluid" alt="{!! $author->name !!}"  itemprop="image">
                    </div>
                </div>
                <div class="row">
                    <div class="col border-right-0 border-bottom-0">
                        @if ($author->birth_year)
                            <p>
                                {{ trans('autor.birth_year') }} <br>
                                {{ $author->birth_year }}
                            </p>
                        @endif
                        @if ($author->birth_place)
                            <p>
                                {{ trans('autor.birth_place') }} <br>
                                {{ $author->birth_place }}
                            </p>
                        @endif
                        @if ($author->active_in)
                            <p>
                                {{ trans('autor.active_in') }} <br>
                                {{ $author->active_in }}
                            </p>
                        @endif
                        @if ($author->studied_at)
                            <p>
                                {{ trans('autor.studied_at') }} <br>
                                {{ $author->studied_at }}
                            </p>
                        @endif
                        @if (!empty($author->tagNames()))
                            <p>
                                {{ trans('autor.tags') }} <br>
                                @foreach ($author->tags as $tag)
                                    <a href="{!!URL::to('klucove-slova#' . $tag->slug)!!}" class="mr-1">#{!! $tag->name !!}</a>
                                @endforeach
                            </p>
                        @endif
                        {{--
                        @if ( $author->events->count() > 0)
                            <div class="events">
                                {{ utrans('autor.places') }}<br>
                                @foreach ($author->events as $i=>$event)
                                    <strong><a href="{!! url_to('autori', ['place' => $event->place]) !!}">{!! $event->place !!}</a></strong> {!! add_brackets(App\Authority::formatMultiAttribute($event->event)) !!}{{ ($i+1 < $author->events->count()) ? ', ' : '' }}
                                @endforeach
                            </div>
                        @endif
                         --}}
                        @if ( $author->linksForLocale->count() > 0)
                            <p class="links">
                                {{ utrans('autor.links') }}<br>
                                @foreach ($author->linksForLocale as $i=>$link)
                                    <a href="{{ $link->url }}" target="_blank">{{ $link->label }}</a><br>
                                @endforeach
                            </p>
                        @endif
                        <p>
                            {{ trans('autor.updated_at') }} <br>
                            {{ $author->updated_at->format('d.m.Y') }}
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-8 col-xl-10 popis p-0">
                <div class="accordion" id="authorAccordion">
                    @include('components.khb_accordion_card', [
                        'title' => utrans('autor.biography'),
                        'content' => $author->biography,
                        'parrentId' => 'authorAccordion',
                        'show' => true,
                    ])
                    {{-- gallery --}}
                    @if ($items->count() > 0)
                    <div class="card">
                      <div class="card-header" id="heading{{ studly_case('gallery') }}">
                        <h5 class="mb-0">
                          <button class="btn btn-link font-weight-bold p-0" type="button" data-toggle="collapse" data-target="#collapse{{ studly_case('gallery') }}" aria-expanded="true" aria-controls="collapse{{ studly_case('gallery') }}">
                            {{ utrans('autor.gallery') }}
                          </button>
                        </h5>
                      </div>

                      <div id="collapse{{ studly_case('gallery') }}" class="collapse show" aria-labelledby="heading{{ studly_case('gallery') }}" data-parent="#authorAccordion">
                        <div class="card-body pt-0">
                            <div id="iso" class="grid-container">
                            @foreach ($items as $i=>$item)
                                <div class="col-md-3 col-sm-4 col-xs-6 item border-0">
                                    <a href="{!! $item->getImagePath() !!}"
                                       title="{!! $item->getTitleWithAuthors() !!}"
                                       data-sub-title="{{ $item->getSubTitle() }}"
                                       data-photo-credit="{{ $item->photo_credit }}"
                                       data-id="{{ $item->id }}"
                                       class="{{ $item->hasZoomableImages() ? 'zoom-viewer' : 'img-viewer' }}">
                                        @php
                                            list($width, $height) = getimagesize(public_path() . $item->getImagePath());
                                        @endphp
                                        <div class="ratio-box" style="padding-bottom: {{ round(($height / $width) * 100, 4) }}%;">
                                            @include('components.item_image_responsive', ['item' => $item])
                                        </div>
                                    </a>
                                </div>
                            @endforeach
                            </div>
                            {{-- /iso --}}
                        </div>
                      </div>
                    </div>
                    @endif
                    {{-- /gallery --}}

                    @if (!empty($video))
                    @include('components.khb_accordion_card', [
                        'title' => 'Video',
                        'content' => $video,
                        'parrentId' => 'authorAccordion',
                        'show' => true,
                    ])
                    @endif
                    @include('components.khb_accordion_card', [
                        'title' => ($author->type=='theoretician') ? utrans('autor.curatorial_projects') : utrans('autor.exhibitions'),
                        'content' => $author->exhibitions,
                        'parrentId' => 'authorAccordion',
                        'show' => false,
                    ])
                    @include('components.khb_accordion_card', [
                        'title' => utrans('autor.bibliography'),
                        'content' => $author->bibliography,
                        'parrentId' => 'authorAccordion',
                        'show' => false,
                    ])
                    {{-- @include('components.khb_accordion_card', [
                        'title' => utrans('autor.archive'),
                        'content' => $author->archive,
                        'parrentId' => 'authorAccordion',
                        'show' => false,
                    ]) --}}

                    {{-- archive --}}
                    @if ($items->count() > 0)
                    <div class="card">
                      <div class="card-header" id="heading{{ studly_case('archive') }}">
                        <h5 class="mb-0">
                          <button class="btn btn-link font-weight-bold p-0" type="button" data-toggle="collapse" data-target="#collapse{{ studly_case('archive') }}" aria-expanded="true" aria-controls="collapse{{ studly_case('archive') }}">
                            {{ utrans('autor.archive') }}
                          </button>
                        </h5>
                      </div>

                      <div id="collapse{{ studly_case('archive') }}" class="collapse {{-- show --}}" aria-labelledby="heading{{ studly_case('archive') }}" data-parent="#authorAccordion">
                        <div class="card-body pt-0">

                            <div class="expandable">
                                {!! $author->archive !!}
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


                @if ( $author->relationships->count() > 0)
                <h4 class="top-space">{{ utrans('autor.relationships') }}</h4>
                <table class="table table-condensed relationships">
                    <thead>
                        <tr>
                        @foreach ($author->getAssociativeRelationships() as $type=>$relationships)
                            <th>{!! $type !!}</th>
                        @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                        @foreach ($author->getAssociativeRelationships() as $type=>$relationships)
                            <td>
                            @foreach ($relationships as $relationship)
                                <a href="{!! $relationship['id'] !!}" class="no-border"><strong itemprop="knows">{!! $relationship['name'] !!}</strong> <i class="icon-arrow-right"></i></a> <br>
                            @endforeach
                            </td>
                        @endforeach
                        </tr>
                    </tbody>
                </table>
                @endif
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
                tCounter: '<span class="mfp-counter">%curr% {{ trans('autor.of') }} %total%</span>',
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
                        title = title + '<small>{{ trans('autor.by') }} '+ item.el.attr('data-photo-credit') +'</small>';
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
                        title = title + '<small>{{ trans('autor.by') }} '+ item.el.attr('data-photo-credit') +'</small>';
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
