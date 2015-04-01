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
                        <strong>{{ Authority::formatName($relationship->name) }}</strong> ({{ Authority::formatMultiAttribute($relationship->type) }}){{ ($i+1 < $author->relationships->count()) ? ', ' : '' }}
                    @endforeach
                </div>
            </div>{{-- row --}}
            @endif
            @if ( $author->events->count() > 0)
            <div class="row">   
                <div class="col-md-12 text-left events bottom-space">
                    Pôsobenie: 
                    @foreach ($author->events as $i=>$event)
                        <strong><a href="{{ url_to('autori', ['place' => $event->place]) }}">{{ $event->place }}</a></strong> ({{ Authority::formatMultiAttribute($event->event) }}){{ ($i+1 < $author->events->count()) ? ', ' : '' }}
                    @endforeach
                </div>
            </div>{{-- row --}}
            @endif
            <div class="row" id="iso">   
                @foreach ($author->items->slice(0,9) as $i=>$item)
                    <div class="col-md-4 col-sm-6 col-xs-12 item">
                        <a href="{{ $item->getDetailUrl() }}">
                            <img src="{{ $item->getImagePath() }}" class="img-responsive" alt="{{implode(', ', $item->authors)}} - {{ $item->title }}">                         
                        </a>
                        <div class="item-title">
                            @if (!empty($item->iipimg_url))
                                <div class="pull-right"><a href="{{ URL::to('dielo/' . $item->id . '/zoom') }}" data-toggle="tooltip" data-placement="left" title="Zoom obrázku"><i class="fa fa-search-plus"></i></a></div>
                            @endif    
                            <a href="{{ $item->getDetailUrl() }}" {{ (!empty($search))  ? 
                                'data-searchd-result="title/'.$item->id.'" data-searchd-title="'.implode(', ', $item->authors).' - '. $item->title.'"' 
                                : '' }}>
                                <em>{{ implode(', ', $item->authors) }}</em><br>
                                <strong>{{ $item->title }}</strong>, <em>{{ $item->getDatingFormated() }}</em><br>
                                <span class="">{{ $item->gallery }}</span>
                            </a>
                        </div>
                    </div>  
                @endforeach    
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

        var $container = $('#iso');
           
        // az ked su obrazky nacitane aplikuj isotope
        $container.imagesLoaded(function () {
            spravGrid($container);
        });

    });
</script>
@stop
