@extends('layouts.master')

@section('og')
<meta property="og:title" content="{!! $author->formatedName !!}" />

<meta property="og:description" content="{!! $author->getDescription(false, false, true) !!}" />
<meta property="og:type" content="object" />
<meta property="og:url" content="{!! Request::url() !!}" />
<meta property="og:image" content="{!! URL::to( $author->getImagePath() ) !!}" />
<meta property="og:site_name" content="Web umenia" />
@stop

@section('title')
{!! $author->formatedName !!} |
@parent
@stop

@section('description')
    <meta name="description" content="{!! $author->getDescription(false, false, true) !!}">
@stop

@section('content')

@if ( ! $author->hasTranslation(App::getLocale()) )
    <section>
        <div class="container top-section">
            <div class="row">
                @include('includes.message_untranslated')
            </div>
        </div>
    </section>
@endif

<section class="author detail" itemscope itemtype="http://schema.org/Person">
    <div class="attributes">
        <div class="row">
            <div class="col-2dot4">
                <h1 itemprop="name" class="mt-2 mb-4">{!! $author->formatedName !!}</h1>
                {{--
                @if ( $author->names->count() > 0)
                    <p class="lead">{{ trans('autor.alternative_names') }} <em>{!! implode("</em>, <em>", $author->formatedNames) !!}</em></p>
                @endif
                 --}}
                <div class="row"><div class="col p-0 border-left-0 border-right-0">
                    <img src="{!! $author->getImagePath() !!}" class="img-fluid" alt="{!! $author->name !!}"  itemprop="image">
                </div></div>
                <p class="my-4">
                    Dátum narodenia <br>
                    {{ $author->birth_date }}
                </p>
                <p class="my-4">
                    Miesto narodenia  <br>
                    {{ $author->birth_place }}
                </p>
                @if ( $author->events->count() > 0)
                    <div class="events">
                        {{ utrans('autor.places') }}<br>
                        @foreach ($author->events as $i=>$event)
                            <strong><a href="{!! url_to('autori', ['place' => $event->place]) !!}">{!! $event->place !!}</a></strong> {!! add_brackets(App\Authority::formatMultiAttribute($event->event)) !!}{{ ($i+1 < $author->events->count()) ? ', ' : '' }}
                        @endforeach
                    </div>
                @endif
                @if ( $author->links->count() > 0)
                    <div class="links">
                        {{ utrans('autor.links') }}<br>
                        @foreach ($author->links as $i=>$link)
                            <a href="{{ $link->url }}" target="_blank">{{ $link->label }}</a><br>
                        @endforeach
                    </div>
                @endif

                @if ( $author->tags->count() > 0)
                    <div class="tags">
                        <h4>{{ utrans('autor.tags') }}: </h4>
                        @foreach ($author->tags as $tag)
                            <a href="{!!URL::to('katalog?tag=' . $tag)!!}" class="btn btn-default btn-xs btn-outline">{!! $tag !!}</a>
                        @endforeach
                    </div>
                @endif
            </div>
            <div class="col popis p-0 border-0">

                <div class="accordion" id="authorAccordion">
                    @include('components.khb_accordion_card', [
                        'title' => 'biography',
                        'content' => $author->biography,
                        'parrentId' => 'authorAccordion',
                        'show' => true,
                    ])

                    {{-- gallery --}}
                    <div class="card">
                      <div class="card-header" id="heading{{ studly_case('gallery') }}">
                        <h5 class="mb-0">
                          <button class="btn btn-link font-weight-bold p-0" type="button" data-toggle="collapse" data-target="#collapse{{ studly_case('gallery') }}" aria-expanded="true" aria-controls="collapse{{ studly_case('gallery') }}">
                            {{ trans('autor.'.'gallery') }}
                          </button>
                        </h5>
                      </div>

                      <div id="collapse{{ studly_case('gallery') }}" class="collapse show" aria-labelledby="heading{{ studly_case('gallery') }}" data-parent="#authorAccordion">
                        <div class="card-body pt-0">

                            <div id="iso">
                            @foreach ($author->items as $i=>$item)
                                <div class="col-md-3 col-sm-4 col-xs-6 item border-0">
                                <a href="{!! $item->getImagePath() !!}" title="{!! $item->getTitleWithAuthors() !!}" data-photo-credit="{{ $item->photo_credit or 'Unknown'}}">
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
                    {{-- /gallery --}}

                    @include('components.khb_accordion_card', [
                        'title' => 'exhibitions',
                        'content' => $author->exhibitions,
                        'parrentId' => 'authorAccordion',
                        'show' => false,
                    ])
                    @include('components.khb_accordion_card', [
                        'title' => 'bibliography',
                        'content' => $author->bibliography,
                        'parrentId' => 'authorAccordion',
                        'show' => false,
                    ])
                    @include('components.khb_accordion_card', [
                        'title' => 'archive',
                        'content' => $author->archive,
                        'parrentId' => 'authorAccordion',
                        'show' => false,
                    ])
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

    $('#iso').isotope({
        itemSelector: '.item',
        layoutMode: 'masonry'
    });

    $(document).ready(function(){

        $('.expandable').readmore({
            moreLink: '<a href="#" class="no-underline text-right"><i class="fa fa-chevron-down"></i> {{ trans("general.show_more") }}</a>',
            lessLink: '<a href="#" class="no-underline text-right"><i class="fa fa-chevron-up"></i> {{ trans("general.show_less") }}</a>',
            maxHeight: 253,
            afterToggle: function(trigger, element, expanded) {
              if(! expanded) { // The "Close" link was clicked
                $('html, body').animate( { scrollTop: element.offset().top }, {duration: 100 } );
              }
            }
        });

        $('#iso').magnificPopup({
            delegate: '.item a',
            type: 'image',
            tLoading: 'Loading image #%curr%...',
            mainClass: 'mfp-img-mobile',
            gallery: {
                enabled: true,
                navigateByImgClick: true,
                tCounter: '<span class="mfp-counter">%curr% {{ trans('autor.of') }} %total%</span>',
                preload: [1,1] // Will preload 0 - before current, and 1 after the current image
            },
            image: {
                tError: '<a href="%url%">The image #%curr%</a> could not be loaded.',
                titleSrc: function(item) {
                    return item.el.attr('title') + '<small>{{ trans('autor.by') }} '+ item.el.attr('data-photo-credit') +'</small>';
                }
            }
        });


    });
</script>
@stop
