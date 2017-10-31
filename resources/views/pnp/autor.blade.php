@extends('layouts.pnp')

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

<section class="author detail content-section" itemscope itemtype="http://schema.org/Person">
    <div class="container">
        <div class="attributes">
            <div class="row">
                <div class="col-sm-4 text-center extra-padding top-space">
                        <img src="{!! $author->getImagePath() !!}" class="img-responsive img-circle" alt="{!! $author->name !!}"  itemprop="image">
                </div>
                <div class="col-sm-8 popis">
                    <h1 itemprop="name">{!! $author->formatedName !!}</h1>
                    @if ( $author->names->count() > 0)
                        <p class="lead">příp.  <em>{!! implode("</em>, <em>", $author->formatedNames) !!}</em></p>
                    @endif
                    <p class="lead">
                        {!! $author->getDescription(true, true) !!}
                    </p>

                    {{-- @if ( $author->biography) --}}
                    <div class="text-left biography">
                        {!!  $author->biography !!}
                    </div>
                    {{-- @endif --}}

                    @if ( $author->events->count() > 0)
                        <div class="events">
                            <h4 class="top-space">{{ utrans('autor.places') }}</h4>
                            @foreach ($author->events as $i=>$event)
                                {!! $event->place !!} {!! add_brackets(App\Authority::formatMultiAttribute($event->event)) !!}{{ ($i+1 < $author->events->count()) ? ', ' : '' }}
                            @endforeach
                        </div>
                    @endif
                    @if ( $author->links->count() > 0)
                        <div class="links">
                            <h4 class="top-space">{{ utrans('autor.links') }}</h4>
                            <?php foreach ($author->links as $i=>$link) $links[] = '<a href="'.$link->url .'" target="_blank">'.$link->label.'</a>'; ?>
                            {!! implode(", ", $links) !!}
                        </div>
                    @endif

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
    </div>
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
                <div class="artworks-preview large">
                    @foreach ($author->getPreviewItems() as $item)
                        <a href="{!! $item->getUrl() !!}"><img data-lazy="{!! $item->getImagePath() !!}" class="img-responsive-width large" alt="{!! $item->getTitleWithAuthors() !!} " title="{!! $item->getTitleWithAuthors() !!} "></a>
                    @endforeach
                </div>
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


    });
</script>
@stop
