@extends('layouts.mg')



@section('link')
    @include('includes.pagination_links', ['paginator' => $paginator])
    <link rel="canonical" href="{!! getCanonicalUrl() !!}">
@stop

@section('content')

@if (!count(Input::all()))
    <div class="mgCarousel">
        @foreach ($slides as $slide)
            <div class="gallery-cell" style="background-image: url({!! $slide->image_path !!})">
                <a href="{!! $slide->url !!}" class="outer-box" data-id="{!! $slide->id !!}" >
                    <div class="inner-box">
                        <h1 class="circle">{!! $slide->title !!}</h1>
                        @if ($slide->subtitle)
                            <h2>{!! $slide->subtitle !!}</h2>
                        @endif
                    </div>
                </a>
            </div>
        @endforeach
    </div>
@endif

<section class="filters">
    <div class="container content-section"><div class="expandable">
            {!! Form::open(array('id'=>'filter', 'method' => 'get')) !!}
            {!! Form::hidden('search', @$search) !!}
            <div class="row">
                <!-- <h3>Filter: </h3> -->
                <div  class="col-md-4 col-xs-6 bottom-space">
                        {!! Form::select('author', array('' => '') + $authors, @$input['author'], array('class'=> 'custom-select form-control', 'data-placeholder' => trans('katalog.filters_author'))) !!}
                 </div>
                <div  class="col-md-4 col-xs-6 bottom-space">
                        {!! Form::select('work_type', array('' => '') + $work_types,  @$input['work_type'], array('class'=> 'custom-select form-control', 'data-placeholder' => trans('katalog.filters_work_type'))) !!}
                </div>
                <div  class="col-md-4 col-xs-6 bottom-space">
                        {!! Form::select('topic', array('' => '') + $topics, @$input['topic'], array('class'=> 'custom-select form-control', 'data-placeholder' => trans('katalog.filters_topic'))) !!}
                </div>
                <div  class="col-md-4 col-xs-6 bottom-space">
                        {!! Form::select('technique', array('' => '') + $techniques, @$input['technique'], array('class'=> 'custom-select form-control', 'data-placeholder' => trans('katalog.filters_technique'))) !!}
                </div>
                <div  class="col-md-4 col-xs-6 bottom-space">
                        {!! Form::select('medium', array('' => '') + $mediums, @$input['medium'], array('class'=> 'custom-select form-control', 'data-placeholder' => trans('katalog.filters_medium'))) !!}
                </div>
                <div  class="col-md-4 col-xs-6 bottom-space">
                        {!! Form::select('place', array('' => '') + $places, @$input['place'], array('class'=> 'custom-select form-control', 'data-placeholder' => trans('katalog.filters_place'))) !!}
                </div>
                <div class="col-md-2 col-md-offset-4 col-xs-4 col-xs-offset-2 ">
                        <div class="checkbox">
                            {!! Form::checkbox('has_image', '1', @$input['has_image'], ['id'=>'has_image']) !!}
                            <label for="has_image">
                              {{ trans('katalog.filters_has_image') }}
                            </label>
                        </div>
                </div>
                <div class="col-md-2 col-xs-4 ">
                        <div class="checkbox">
                            {!! Form::checkbox('has_iip', '1', @$input['has_iip'], ['id'=>'has_iip']) !!}
                            <label for="has_iip">
                              {{ trans('katalog.filters_has_iip') }}
                            </label>
                        </div>
                </div>

            </div>
            @php ($year_range = explode(',', Input::get('year-range','')))
            <div class="row">
                <div class="col-xs-6 col-sm-1 text-left text-sm-right year-range">
                        <span class="sans" id="from_year">{!! !empty($input['year-range']) ? $year_range[0] : App\Item::sliderMin() !!}</span> 
                </div>
                <div class="col-xs-6 col-sm-1 col-sm-push-10 text-right text-sm-left year-range">
                        <span class="sans" id="until_year">{!! !empty($input['year-range']) ? $year_range[1] : App\Item::sliderMax() !!}</span>
                </div>
                <div class="col-sm-10 col-sm-pull-1 year-range">
                        <input id="year-range" name="year-range" type="text" class="span2" data-slider-min="{!! App\Item::sliderMin() !!}" data-slider-max="{!! App\Item::sliderMax() !!}" data-slider-step="5" data-slider-value="[{!! !empty($input['year-range']) ? $input['year-range'] : App\Item::sliderMin().','.App\Item::sliderMax() !!}]"/> 
                </div>
            </div>
            {!! Form::hidden('sort_by', @$input['sort_by'], ['id'=>'sort_by']) !!}
            {!! Form::close() !!}
    </div></div>
</section>
<section class="catalog" data-searchd-engine="{!! Config::get('app.searchd_id') !!}">
    <div class="container">
            <div class="row content-section">
            	<div class="col-xs-6">
                    @if (!empty($search))
                        <h4 class="inline">{{ utrans('katalog.catalog_found_artworks') }} &bdquo;{!! $search !!}&ldquo; (<span data-searchd-total-hits>{!! $items->total() !!}</span>) </h4> 
                    @else
                        <h4 class="inline">{!! $items->total() !!} {{ trans('katalog.catalog_artworks') }} </h4>
                    @endif
                    @if ($items->count() == 0)
                        <p class="text-center">{{ utrans('katalog.catalog_no_artworks') }}</p>
                    @endif

                    @if (count(Input::all()) > 0)
                        <a class="btn btn-sm btn-default btn-outline  sans" href="{!! URL::to('/')!!}">{{ trans('general.clear_filters') }}  <i class="icon-cross"></i></a>
                    @endif
                </div>
                <div class="col-xs-6 text-right">
                    <div class="dropdown">
                      <a class="dropdown-toggle" type="button" id="dropdownSortBy" data-toggle="dropdown" aria-expanded="true">
                        {{ trans('general.sort_by') }} {!! trans(App\Item::getSortedLabelKey()) !!}
                        <span class="caret"></span>
                      </a>
                      <ul class="dropdown-menu dropdown-menu-right dropdown-menu-sort" role="menu" aria-labelledby="dropdownSortBy">
                        @foreach (App\Item::$sortable as $sort=>$labelKey)
                            <li role="presentation"><a role="menuitem" tabindex="-1" href="#" rel="{!! $sort !!}">{!! trans($labelKey) !!}</a></li>
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
    	                	<a href="{!! $item->getUrl() !!}">
    	                		<img src="{!! $item->getImagePath() !!}" class="img-responsive" alt="{!! $item->getTitleWithAuthors() !!} ">	                		
    	                	</a>
                            <div class="item-title">
                                @if ($item->has_iip)
                                    <div class="pull-right"><a href="{!! URL::to('dielo/' . $item->id . '/zoom') !!}" data-toggle="tooltip" data-placement="left" title="Zoom obrázku"><i class="fa fa-search-plus"></i></a></div>
                                @endif    
                                <a href="{!! $item->getUrl() !!}" {!! (!empty($search))  ? 
                                    'data-searchd-result="title/'.$item->id.'" data-searchd-title="'.implode(', ', $item->authors).' - '. $item->title.'"' 
                                    : '' !!}>
                                    <em>{!! implode(', ', $item->authors) !!}</em><br>
                                    <strong>{!! $item->title !!}</strong><br>
                                    <em>{!! $item->getDatingFormated() !!}</em>
                                </a>
                            </div>
    	                </div>	
                	@endforeach

                    </div>
                    <div class="col-sm-12 text-center">
                        {!! $paginator->appends(@Input::except('page'))->render() !!}
                        @if ($paginator->hasMorePages() )
                            <a id="next" href="{!! URL::to('katalog')!!}"><svg xmlns="http://www.w3.org/2000/svg" width="100px" height="100px" viewBox="0 0 100 100" enable-background="new 0 0 100 100" xml:space="preserve"> <path d="M0.5,0.2v99.5h99V0.2H0.5z"/>
<text text-anchor="middle" alignment-baseline="middle" x="50" y="50">
    {{ trans('katalog.catalog_show_more') }}
  </text>
   </svg></a>
                        @endif
                    </div>
                </div>

            </div>
    </div>
</section>


@stop

@section('javascript')

{!! Html::script('js/bootstrap-slider.min.js') !!}
{{-- {!! Html::script('js/bootstrap-checkbox.js') !!} --}}
{!! Html::script('js/selectize.min.js') !!}
{!! Html::script('js/readmore.min.js') !!}
{!! Html::script('js/scroll-frame.js') !!}

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
    
    // $('.checkbox').checkbox();

    $("form").submit(function()
    {
        $(this).find('input[name], select[name]').each(function(){
            if (!$(this).val()){
                $(this).data('name', $(this).attr('name'));
                $(this).removeAttr('name');
            }
        });
        if ( $('#year-range').val()=='{!!App\Item::sliderMin()!!},{!!App\Item::sliderMax()!!}' ) {
            $('#year-range').attr("disabled", true);
        }
    });

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

    // $(".custom-select").chosen({allow_single_deselect: true})
    $(".custom-select").selectize({
        plugins: ['remove_button'],
         // maxItems: 2,
        maxItems: 1,
        placeholder: $(this).attr('data-placeholder'),
        mode: 'multi',
        render: {
                 // option: function(data, escape) {
                 //     return '<div class="option">' +
                 //             '<span class="title">' + escape(data.value) + '</span>' +
                 //             '<span class="url">' + escape(data.value) + '</span>' +
                 //         '</div>';
                 // },
                 item: function(data, escape) {
                     return '<div class="item">'  + '<span class="color">'+this.settings.placeholder+': </span>' +  data.text.replace(/\(.*?\)/g, "") + '</div>';
            }
        }
    });

    $(".custom-select, input[type='checkbox']").change(function() {
        var form = $(this).closest('form');
        form.submit();
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
        path            : undefined,
        bufferPx     : 200,
        loading: {
            msgText: '<i class="fa fa-refresh fa-spin fa-lg"></i>',
            img: '/images/transparent.gif',
            finishedMsg: 'A to je vše'
        }
    }, function(newElements, data, url){
        history.replaceState({infiniteScroll:true}, null, url);
        var $newElems = jQuery( newElements ).hide(); 
        $newElems.imagesLoaded(function(){
            $newElems.fadeIn();
            $container.isotope( 'appended', $newElems );
        });
    });

    $(window).unbind('.infscr'); //kill scroll binding

    scrollFrame('.item');


    $('a#next').click(function(){
        $(this).fadeOut();
        $container.infinitescroll('bind');
        $container.infinitescroll('retrieve');
        return false;
    });

    
    var $carousel = $('.mgCarousel').flickity({
      wrapAround: true,
      percentPosition: false
    });
    $carousel.children('.flickity-page-dots').css('left',  parseInt($('.flickity-slider').css('transform').split(',')[4]) );

    $carousel.on( 'staticClick', function( event, pointer, cellElement, cellIndex ) {
        event.preventDefault();
        var $link = $( cellElement ).find('a');
        var url = $link.attr('href');
        var id = $link.data('id');
        $.get('/slideClicked', {'id': id});

    });


});

</script>
@stop
