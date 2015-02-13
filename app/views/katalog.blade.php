@extends('layouts.master')

@if (!empty($cc))
@section('og')
<meta property="og:title" content="voľné diela s licenciou Creative Commons" />
<meta property="og:description" content="Digitálne reprodukcie diel SNG na tejto stránke sú sprístupnené pod licenciou Creative Commons BY-NC-SA 4.0. Môžete si ich voľne stiahnuť vo vysokom rozlíšení. Reprodukcie sa môžu ľubovoľne využívať na nekomerčné účely - kopírovať, zdieľať či upravovať. Pri ďalšom šírení obrázkov je potrebné použiť rovnakú licenciu (CC BY-NC-SA) a uviesť odkaz na webstránku http://dvekrajiny.sng.sk s citáciou diela (autor, názov, rok vzniku, vlastník diela)." />
<meta property="og:type" content="website" />
<meta property="og:url" content="{{ Request::url() }}" />
<meta property="og:image" content="{{ URL::to('images/cc-og.jpg') }}" />
<meta property="og:site_name" content="DVE KRAJINY" />
@stop
@endif

@section('title')
@parent
@if (!empty($cc))
| voľné diela s licenciou Creative Commons
@else
| všetky diela
@endif
@stop

@section('content')

<section class="top-section">
    <div class="catalog-body">
        <div class="container">
        @if (!empty($cc))
            <div class="row">
                <div class="col-md-8 col-md-offset-2 text-center bottom-space">
                        <a href="http://creativecommons.org/licenses/by-nc-sa/4.0/deed.cs" target="_blank"><img src="{{ URL::asset('images/license/cc.svg') }}" alt="Creative Commons" ></a>
                        <h1>Voľné diela</h1>    
                        <p>Digitálne reprodukcie diel SNG na tejto stránke sú sprístupnené pod licenciou <a class="underline" href="http://creativecommons.org/licenses/by-nc-sa/4.0/deed.cs" target="_blank">Creative Commons BY-NC-SA 4.0</a>. Môžete si ich voľne stiahnuť vo vysokom rozlíšení. Reprodukcie sa môžu ľubovoľne využívať na nekomerčné účely - kopírovať, zdieľať či upravovať. Pri ďalšom šírení obrázkov je potrebné použiť rovnakú licenciu <em>(CC BY-NC-SA)</em> a uviesť odkaz na webstránku <a class="underline" href="http://dvekrajiny.sng.sk">http://dvekrajiny.sng.sk</a> s citáciou diela (autor, názov, rok vzniku, vlastník diela).</p>                    
                </div>
            </div>
        @endif
        </div>
    </div>
</section>

<section class="catalog">
    <div class="catalog-body">
        <div class="container">
            @if (empty($cc))
            {{ Form::open(array('id'=>'filter', 'method' => 'get')) }}
            {{ Form::hidden('search', @$search) }}
            <div class="row">
                <!-- <h3>Filter: </h3> -->
                <div  class="col-sm-3">
                        {{ Form::select('author', array('' => '') + $authors, @$input['author'], array('class'=> 'chosen-select form-control', 'data-placeholder' => 'Autor')) }}
                 </div>
                <div  class="col-sm-3">
                        {{ Form::select('work_type', array('' => '') + $work_types,  @$input['work_type'], array('class'=> 'chosen-select form-control', 'data-placeholder' => 'Výtvarný druh')) }}
                </div>
                <div  class="col-sm-3">
                        {{ Form::select('subject', array('' => '') + $tags, @$input['subject'], array('class'=> 'chosen-select form-control', 'data-placeholder' => 'Tagy')) }}
                </div>
                <div  class="col-sm-3">
                        {{ Form::select('gallery', array('' => '') + $galleries, @$input['gallery'], array('class'=> 'chosen-select form-control', 'data-placeholder' => 'Inštitúcia / majiteľ')) }}
                </div>
            </div>
            <div class="row bottom-space" style="padding-top: 20px;">
                <div  class="col-sm-3">
                        
                        <p><a class="btn btn-default btn-outline  uppercase sans" href="{{ URL::to('katalog')}}">zobraziť všetky diela</a></p>
                        <!-- {{ Form::hidden('search', @$search); }} -->
                </div>
                <div class="col-sm-1 text-right year-range">
                        <b class="sans" id="from_year">{{ !empty($input['year-range']) ? reset((explode(',', $input['year-range']))) : '1790' }}</b> 
                </div>
                <div class="col-sm-7 year-range">
                        <input id="year-range" name="year-range" type="text" class="span2" data-slider-min="1790" data-slider-max="2014" data-slider-step="5" data-slider-value="[{{ !empty($input['year-range']) ? $input['year-range'] : '1790,2014' }}]"/> 
                </div>
                <div class="col-sm-1 text-left year-range">
                        <b class="sans" id="until_year">{{ !empty($input['year-range']) ? end((explode(',', $input['year-range']))) : '2014' }}</b>
                </div>
            </div>
             {{ Form::close() }}
             @endif
            <div class="row">
            	<div class="col-sm-12 container-item">
                    @if (!empty($search))
                        <h3>Nájdené diela pre &bdquo;{{ $search }}&ldquo; (<span data-searchd-total-hits>{{ $items->total() }}</span>) </h3> 
                    @else
                		<h3>Nájdené diela ({{ $items->total() }}) </h3>
                    @endif
                    @if ($items->count() == 0)
                        <p class="text-center">Momentálne žiadne diela</p>
                    @endif
                    <?php $items = $items->paginate(18) ?>
                    <div id="iso">
                	@foreach ($items as $i=>$item)
    	                <div class="col-md-3 col-sm-4 col-xs-12 item">
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
        value: [1800, 1900],
        tooltip: 'hide'
    }).on('slideStop', function(event) {
        $(this).closest('form').submit();
    }).on('slide', function(event) {
        var rozsah = $("#year-range").val().split(',');
        $('#from_year').html(rozsah[0]);
        $('#until_year').html(rozsah[1]);
    });

    $(".chosen-select").chosen({allow_single_deselect: true})

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
