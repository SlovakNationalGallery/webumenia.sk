@extends('layouts.master')

@section('og')
<meta property="og:title" content="{{ $author->formatedName }}" />

<meta property="og:description" content="{{ $author->getDescription() }}" />
<meta property="og:type" content="object" />
<meta property="og:url" content="{{ Request::url() }}" />
<meta property="og:image" content="{{ URL::to( $author->getImagePath() ) }}" />
<meta property="og:site_name" content="Web umenia" />
@stop

@section('title')
@parent
| {{ $author->formatedName }}
@stop

@section('description')
    <meta name="description" content="{{ $author->getDescription() }}">
@stop

@section('content')

<section class="author content-section top-section">
    <div class="author-body">
        <div class="container">

            <div class="row">   
                <div class="col-sm-2">
                        <img src="{{ $author->getImagePath() }}" class="img-responsive img-circle" alt="{{ $author->name }}">                           
                </div>
                <div class="col-sm-10">
                    <div class="">
                            <h3>{{ $author->formatedName }}</h3>
                            @if ( $author->names->count() > 0)
                                <p>príp.  <em>{{ implode("</em>, <em>", $author->formatedNames) }}</em></p>
                            @endif
                            
                    </div>
                    <p>
                        {{ $author->getDescription(true, true) }}
                    </p>
                    <p>
                        @foreach ($author->roles as $i=>$role)
                            <a href="{{ url_to('autori', ['role' => $role->role]) }}">{{ $role->role }}</a>{{ ($i+1 < $author->roles->count()) ? ', ' : '' }}
                        @endforeach
                        {{-- {{ implode(", ", $author->roles->lists('role')) }} --}}
                    </p>
                    <p>
                        <a href="{{ url_to('katalog', ['author' => $author->name]) }}"><strong>{{ $author->items->count() }}</strong></a> diel
                        v <strong>{{ $author->collections_count }}</strong> kolekciách
                        &nbsp; <strong>{{ $author->view_count }}</strong> videní
                    </p>
                </div>

            </div>{{-- row --}}
            <div class="row">   
                <div class="col-md-12 text-left description bottom-space">
                    {{  $author->biography }}
                </div>
            </div>{{-- row --}}
            @if ( $author->relationships->count() > 0)
            <div class="row">   
                <div class="col-md-12 text-left relationships bottom-space">
                    Vzťahy: 
                    @foreach ($author->relationships as $i=>$relationship)
                        <a href="{{ $author::detailUrl($relationship->realted_authority_id) }}"><strong>{{ Authority::formatName($relationship->name) }}</strong></a> ({{ Authority::formatMultiAttribute($relationship->type) }}){{ ($i+1 < $author->relationships->count()) ? ', ' : '' }}
                    @endforeach
                </div>
            </div>{{-- row --}}
            @endif
            @if ( $author->events->count() > 0)
            <div class="row">
                <div class="col-md-12 text-left events bottom-space">
                    Pôsobenie: 
                    @foreach ($author->events as $i=>$event)
                        <strong><a href="{{ url_to('autori', ['place' => $event->place]) }}">{{ $event->place }}</a></strong> {{ add_brackets(Authority::formatMultiAttribute($event->event)) }}{{ ($i+1 < $author->events->count()) ? ', ' : '' }}
                    @endforeach
                </div>
            </div>{{-- row --}}
            @endif
            @if ( $author->links->count() > 0)
            <div class="row">
                <div class="col-md-12 text-left links bottom-space">
                    Externé odkazy: 
                    <?php foreach ($author->links as $i=>$link) $links[] = '<a href="'.$link->url .'">'.$link->label.'</a>'; ?>
                    {{ implode(", ", $links) }}
                </div>
            </div>{{-- row --}}
            @endif
            @if ( $author->tags)
            <div class="row">
                <div class="col-md-12 text-left tags bottom-space">
                    Tagy: 
                    <?php foreach ($author->tags as $i=>$tag) $tags[] = '<a href="'.url_to('katalog', ['tags' => $tag]) .'">'.$tag.'</a>'; ?>
                    {{ implode(", ", $tags) }}
                </div>
            </div>{{-- row --}}
            @endif
            <div class="row" >   
                    <div class="col-xs-12 ">
                    <h4>DIELA:</h4>
                            <div class="artworks-preview large">
                            @foreach ($author->items->slice(0,9) as $item)
                                <a href="{{ $item->getDetailUrl() }}"><img data-lazy="{{ $item->getImagePath() }}" class="img-responsive-width large" ></a>
                            @endforeach
                            </div>

                    </div>  
            </div>{{-- row --}}
            <div class="row">
                <div class="col-sm-12 text-center">
                    <a href="{{ url_to('katalog', ['author' => $author->name]) }}" class="btn btn-default btn-outline uppercase sans" >zobraziť všetkých {{ $author->items->count() }} diel <i class="fa fa-chevron-right "></i></a>
                </div>
            </div>

        </div>
    </div>
</section>


@stop


@section('javascript')
{{ HTML::script('js/readmore.min.js') }}
{{ HTML::script('js/slick.js') }}

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
