@extends('layouts.master')

@section('title')
@parent
| všetky diela
@stop

@section('content')

<section class="content-section top-section">
    <div class="catalog-body">
        <div class="container">
            <div class="row">
                <div class="col-md-8 col-md-offset-2 text-center">
                    @if (!empty($search))
                        <!-- <h1 class="uppercase">&bdquo;{{ $search }}&ldquo;</h1> -->
                        <!-- <h2 class="uppercase bottom-space">{{ $items->getTotal() }} nájdených diel</h2>                         -->
                    @else
                        <!-- <img src="/images/x.svg" alt="x" class="xko"> -->
                        <!-- <h2 class="uppercase bottom-space">vystavené diela</h2> -->
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>

<section class="catalog content-section">
    <div class="catalog-body">
        <div class="container">
                {{ Form::open() }}
            <div class="row">
                <!-- <h3>Filter: </h3> -->
                <div  class="col-sm-3">
                        <h4>Autor: </h4>                        
                        {{ Form::select('author', array('' => '') + $authors, @$input['author'], array('class'=> 'chosen-select form-control', 'data-placeholder' => 'Vyber autora...')) }}
                 </div>
                <div  class="col-sm-3">
                        <h4>Výtvarný druh: </h4>
                        {{ Form::select('work_type', array('' => '') + $work_types,  @$input['work_type'], array('class'=> 'chosen-select form-control', 'data-placeholder' => 'Vyber výtvarný druh...')) }}
                </div>
                <div  class="col-sm-3">
                        <h4>Tagy: </h4>
                        {{ Form::select('subject', array('' => '') + $tags, @$input['subject'], array('class'=> 'chosen-select form-control', 'data-placeholder' => 'Vyber tagy...')) }}
                </div>
                <div  class="col-sm-3">
                        <h4>Inštitúcia / majiteľ: </h4>
                        {{ Form::select('gallery', array('' => '') + $galleries, @$input['gallery'], array('class'=> 'chosen-select form-control', 'data-placeholder' => 'Vyber inštitúciu / majiteľa...')) }}
                </div>
            </div>
            <div class="row bottom-space" style="padding-top: 20px;">
                <div  class="col-sm-3">
                        <p><a class="btn btn-primary" href="{{ URL::to('katalog')}}">zobraziť všetky diela</a></p>
                        <!-- {{ Form::hidden('search', @$search); }} -->
                 </div>
                <div class="col-sm-9">
                        <h4>Rok:</h4> 
                        <b>1790</b> 
                        <input id="year-range" name="year-range" type="text" class="span2" data-slider-min="1790" data-slider-max="2014" data-slider-step="5" data-slider-value="[{{ !empty($input['year-range']) ? $input['year-range'] : '1790,2014' }}]"/> 
                        <b>2014</b>
                </div>
            </div>
                 {{ Form::close() }}
            <div class="row">
            	<div class="col-sm-12 container-item">
                    @if (!empty($search))
                        <h3>Nájdené diela pre &bdquo;{{ $search }}&ldquo; ({{ $items->getTotal() }}) </h3> 
                    @else
                		<h3>Nájdené diela ({{ $items->getTotal() }}) </h3>
                    @endif
                    @if ($items->count() == 0)
                        <p class="text-center">Momentálne žiadne diela</p>
                    @endif

                    <div id="iso">
                	@foreach ($items as $i=>$item)
    	                <div class="col-md-6 col-sm-6 col-xs-12 item">
    	                	<a href="{{ $item->getDetailUrl() }}">
    	                		<img src="{{ $item->getImagePath() }}" class="img-responsive">	                		
    	                	</a>
                            <div class="item-title">
                                @if (!empty($item->iipimg_url))
                                    <div class="pull-right"><a href="{{ URL::to('dielo/' . $item->id . '/zoom') }}" data-toggle="tooltip" data-placement="left" title="Zoom obrázku"><i class="fa fa-search-plus"></i></a></div>
                                @endif    
                                <a href="{{ $item->getDetailUrl() }}">
                                    <em>{{ implode(', ', $item->authors) }}</em><br>
                                <strong>{{ $item->title }}</strong>, <em>{{ $item->getDatingFormated() }}</em><br>
                                
                                <span class="">{{ $item->gallery }}</span>
                                </a>
                            </div>
    	                </div>	
                	@endforeach

                    </div>
                    <div class="col-sm-12 text-center">
                        {{ $items->appends(@Input::except('page'))->links() }}
                    </div>
                </div>

            </div>
        </div>
    </div>
</section>


@stop

@section('javascript')

{{ HTML::script('js/bootstrap-slider.min.js') }}
{{ HTML::script('js/chosen.jquery.min.js') }}

<script type="text/javascript">

$(document).ready(function(){

    $("#year-range").slider({
        value: [1800, 1900]
    }).on('slideStop', function(event) {
        $(this).closest('form').submit();
    });

    $(".chosen-select").chosen({})

    $(".chosen-select").change(function() {
        $(this).closest('form').submit();
    });

    var $container = $('#iso');
       
    // az ked su obrazky nacitane aplikuj isotope
    $container.imagesLoaded(function () {
        spravGrid($container);
    });

    $( window ).resize(function() {
        spravGrid($container);
    });

    $container.infinitescroll({
        navSelector     : ".pagination",
        nextSelector    : ".pagination a:last",
        itemSelector    : ".item",
        debug           : false,
        dataType        : 'html',
        donetext        : 'boli načítané všetky diela',
        path            : undefined,
        bufferPx     : 200,
        loading: {
            msgText: "<em>Načítavam ďalšie diela...</em>",
            finishedMsg: 'A to je všetko'
        }
    }, function(newElements, data, url){
        var $newElems = jQuery( newElements ).hide(); 
        $newElems.imagesLoaded(function(){
            $newElems.fadeIn();
            $container.isotope( 'appended', $newElems );
        });
    });

});

</script>
@stop
