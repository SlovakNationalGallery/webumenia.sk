@extends('layouts.master')

@section('title')
@parent
@if (!empty($search))
| výsledky vyhľadávania pre "{{$search}}"
@else
| všetky diela
@endif
@stop

@section('content')

<section class="top-section">
    <div class="catalog-body">
        <div class="container">
        </div>
    </div>
</section>

<section class="filters">
    <div class="container content-section"><div class="expandable">
            {{ Form::open(array('id'=>'filter', 'method' => 'get')) }}
            {{ Form::hidden('search', @$search) }}
            <div class="row">
                <!-- <h3>Filter: </h3> -->
                <div  class="col-md-4 col-xs-6">
                        {{ Form::select('author', array('' => '') + $authors, @$input['author'], array('class'=> 'chosen-select form-control', 'data-placeholder' => 'autor')) }}
                 </div>
                <div  class="col-md-4 col-xs-6">
                        {{ Form::select('work_type', array('' => '') + $work_types,  @$input['work_type'], array('class'=> 'chosen-select form-control', 'data-placeholder' => 'výtvarný druh')) }}
                </div>
                <div  class="col-md-4 col-xs-6">
                        {{ Form::select('tag', array('' => '') + $tags, @$input['tag'], array('class'=> 'chosen-select form-control', 'data-placeholder' => 'tagy')) }}
                </div>
                <div  class="col-md-4 col-xs-6">
                        {{ Form::select('gallery', array('' => '') + $galleries, @$input['gallery'], array('class'=> 'chosen-select form-control', 'data-placeholder' => 'galéria')) }}
                </div>
                <div  class="col-md-4 col-xs-6">
                        {{ Form::select('topic', array('' => '') + $topics, @$input['topic'], array('class'=> 'chosen-select form-control', 'data-placeholder' => 'žáner')) }}
                </div>
                <div  class="col-md-4 col-xs-6">
                        {{ Form::select('technique', array('' => '') + $techniques, @$input['technique'], array('class'=> 'chosen-select form-control', 'data-placeholder' => 'technika')) }}
                </div>
                <div class="col-md-4 col-xs-6">
                        <div class="checkbox">
                            <label>
                              {{ Form::checkbox('has_image', '1', @$input['has_image']) }} len diela s obrázkami
                            </label>
                        </div>
                </div>
                <div class="col-md-4 col-xs-6">
                        <div class="checkbox">
                            <label>
                              {{ Form::checkbox('has_iip', '1', @$input['has_iip']) }} len diela so zoom
                            </label>
                        </div>
                </div>
                <div class="col-md-4 col-xs-6">                        
                        <div class="checkbox">
                            <label>
                              {{ Form::checkbox('is_free', '1', @$input['is_free']) }} len voľné diela
                            </label>
                        </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-1 text-right year-range">
                        <span class="sans" id="from_year">{{ !empty($input['year-range']) ? reset((explode(',', $input['year-range']))) : Item::sliderMin() }}</span> 
                </div>
                <div class="col-sm-10 year-range">
                        <input id="year-range" name="year-range" type="text" class="span2" data-slider-min="{{ Item::sliderMin() }}" data-slider-max="{{ Item::sliderMax() }}" data-slider-step="5" data-slider-value="[{{ !empty($input['year-range']) ? $input['year-range'] : Item::sliderMin().','.Item::sliderMax() }}]"/> 
                </div>
                <div class="col-sm-1 text-left year-range">
                        <span class="sans" id="until_year">{{ !empty($input['year-range']) ? end((explode(',', $input['year-range']))) : Item::sliderMax() }}</span>
                </div>
            </div>
            {{ Form::hidden('sort_by', @$input['sort_by'], ['id'=>'sort_by']) }}
            {{ Form::close() }}
    </div></div>
</section>
<section class="catalog">
    <div class="container content-section">
            <div class="row content-section">
            	<div class="col-xs-6">
                    @if (!empty($search))
                        <h4 class="inline">Nájdené diela pre &bdquo;{{ $search }}&ldquo; (<span data-searchd-total-hits>{{ $items->total() }}</span>) </h4> 
                    @else
                		<h4 class="inline">{{ $items->total() }} diel </h4>
                    @endif
                    @if ($items->count() == 0)
                        <p class="text-center">Momentálne žiadne diela</p>
                    @endif

                    @if (count(Input::all()) > 0)
                        <a class="btn btn-default btn-outline  uppercase sans" href="{{ URL::to('katalog')}}">zrušiť filtre</a>
                    @endif
                </div>
                <div class="col-xs-6 text-right">
                    <div class="dropdown">
                      <a class="dropdown-toggle" type="button" id="dropdownSortBy" data-toggle="dropdown" aria-expanded="true">
                        podľa {{ Item::getSortedLabel(); }}
                        <span class="caret"></span>
                      </a>
                      <ul class="dropdown-menu dropdown-menu-right dropdown-menu-sort" role="menu" aria-labelledby="dropdownSortBy">
                        @foreach (Item::$sortable as $sort=>$label)
                            <li role="presentation"><a role="menuitem" tabindex="-1" href="#" rel="{{ $sort }}">{{ $label }}</a></li>
                        @endforeach
                      </ul>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <?php // $items = $items->paginate(18) ?>
                    <div id="iso">
                	@foreach ($items as $i=>$item)
    	                <div class="col-md-3 col-sm-4 col-xs-6 item">
    	                	<a href="{{ $item->getDetailUrl() }}">
    	                		<img src="{{ $item->getImagePath() }}" class="img-responsive" alt="{{implode(', ', $item->authors)}} - {{ $item->title }}">	                		
    	                	</a>
                            <div class="item-title">
                                @if ($item->has_iip)
                                    <div class="pull-right"><a href="{{ URL::to('dielo/' . $item->id . '/zoom') }}" data-toggle="tooltip" data-placement="left" title="Zoom obrázku"><i class="fa fa-search-plus"></i></a></div>
                                @endif    
                                <a href="{{ $item->getDetailUrl() }}" {{ (!empty($search))  ? 
                                    'data-searchd-result="title/'.$item->id.'" data-searchd-title="'.implode(', ', $item->authors).' - '. $item->title.'"' 
                                    : '' }}>
                                    <em>{{ implode(', ', $item->authors) }}</em><br>
                                    <strong>{{ $item->title }}</strong><br>
                                    <em>{{ $item->getDatingFormated() }}</em>
                                    {{-- <br><span class="">{{ $item->gallery }}</span> --}}
                                </a>
                            </div>
    	                </div>	
                	@endforeach

                    </div>
                    <div class="col-sm-12 text-center">
                        {{ $paginator->appends(@Input::except('page'))->links() }}
                        @if ($paginator->getLastPage() > 1)
                            <a class="btn btn-default btn-outline  sans" id="next" href="{{ URL::to('katalog')}}">zobraziť viac</a>
                        @endif
                    </div>
                </div>

            </div>
    </div>
</section>


@stop

@section('javascript')

{{ HTML::script('js/bootstrap-slider.min.js') }}
{{ HTML::script('js/chosen.jquery.min.js') }}
{{ HTML::script('js/readmore.min.js') }}

<script type="text/javascript">

$(document).ready(function(){
    // $('.expandable').readmore({
    //     moreLink: '<a href="#" class="text-center">viac možností <i class="icon-arrow-down"></i></a>',
    //     lessLink: '<a href="#" class="text-center">menej možností <i class="icon-arrow-up"></i></a>',
    //     maxHeight: 40,
    //     // blockCSS: 'display: block;',
    //     // embedCSS: false,
    //     afterToggle: function(trigger, element, expanded) {
    //       // if(! expanded) { // The "Close" link was clicked
    //         // $('html, body').animate( { scrollTop: element.offset().top }, {duration: 100 } );
    //       // }
    //     }
    // });

    $("#year-range").slider({
        // value: [1800, 1900],
        tooltip: 'hide'
    }).on('slideStop', function(event) {
        $(this).closest('form').submit();
    }).on('slide', function(event) {
        var rozsah = $("#year-range").val().split(',');
        $('#from_year').html(rozsah[0]);
        $('#until_year').html(rozsah[1]);
    });

    $(".chosen-select").chosen({allow_single_deselect: true})

    $(".chosen-select, input[type='checkbox']").change(function() {
        $(this).closest('form').submit();
    });

    $(".dropdown-menu-sort a").click(function(e) {
        e.preventDefault();
        $('#sort_by').val($(this).attr('rel'));
        $('#filter').submit();
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
            // msgText: "<em>Načítavam ďalšie diela...</em>",
            msgText: '<i class="fa fa-refresh fa-spin fa-lg"></i> načítavam ďalšie diela...',
            img: '/images/transparent.gif',
            finishedMsg: 'A to je všetko'
        }
    }, function(newElements, data, url){
        var $newElems = jQuery( newElements ).hide(); 
        $newElems.imagesLoaded(function(){
            $newElems.fadeIn();
            $container.isotope( 'appended', $newElems );
        });
    });

    $(window).unbind('.infscr'); //kill scroll binding

    $('a#next').click(function(){
        $container.infinitescroll('retrieve');
     return false;
    });



});

</script>
@stop
