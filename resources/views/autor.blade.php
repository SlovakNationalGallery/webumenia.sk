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
                <div class="row"><div class="col p-0">
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
            <div class="col popis p-0">

                <div class="accordion" id="authorAccordion">
                    @include('components.khb_accordion_card', [
                        'name' => 'biography',
                        'content' => $author->biography,
                        'parrentId' => 'authorAccordion',
                        'show' => true,
                    ])
                    @include('components.khb_accordion_card', [
                        'name' => 'gallery',
                        'content' => '',
                        'parrentId' => 'authorAccordion',
                        'show' => true,
                    ])
                    @include('components.khb_accordion_card', [
                        'name' => 'exhibitions',
                        'content' => $author->exhibitions,
                        'parrentId' => 'authorAccordion',
                        'show' => false,
                    ])
                    @include('components.khb_accordion_card', [
                        'name' => 'bibliography',
                        'content' => $author->bibliography,
                        'parrentId' => 'authorAccordion',
                        'show' => false,
                    ])
                    @include('components.khb_accordion_card', [
                        'name' => 'archive',
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

@if ($author->items->count() > 0)
<section class="author preview detail">
    <div class="container">
        <div class="row content-section">
            <div class="col-xs-12 text-center">
                <h3>{{ utrans('autor.artworks_by_artist') }}</h3>
            </div>
        </div>{{-- row --}}
        <div class="row">
            <div class="col-xs-12">
                @include('components.artwork_carousel', [
                    'slick_target' => "artworks-preview",
                    'slick_variant' => "large",
                    'items' => $author->getPreviewItems(),
                ])
            </div>
        </div>{{-- row --}}
        <div class="row content-section">
            <div class="col-sm-12 text-center">
                <a href="{!! url_to('katalog', ['author' => $author->name]) !!}" class="btn btn-default btn-outline sans" >{!! trans_choice('autor.button_show-all-artworks', $author->items->count(), ['artworks_count' => $author->items->count()])!!} <i class="fa fa-chevron-right "></i></a>
            </div>
        </div>

    </div>
</section>
@endif



@stop


@section('javascript')
{!! Html::script('js/readmore.min.js') !!}

{!! Html::script('js/slick.js') !!}
{!! Html::script('js/components/artwork_carousel.js') !!}

<script type="text/javascript">
    $(document).ready(function(){

        $('.expandable').readmore({
            moreLink: '<a href="#"><i class="fa fa-chevron-down"></i> {{ trans("general.show_more") }}</a>',
            lessLink: '<a href="#"><i class="fa fa-chevron-up"></i> {{ trans("general.show_less") }}</a>',
            maxHeight: 40,
            afterToggle: function(trigger, element, expanded) {
              if(! expanded) { // The "Close" link was clicked
                $('html, body').animate( { scrollTop: element.offset().top }, {duration: 100 } );
              }
            }
        });

    });
</script>
@stop
