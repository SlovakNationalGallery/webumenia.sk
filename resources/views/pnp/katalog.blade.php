@extends('layouts.pnp')



@section('link')
    @include('includes.pagination_links', ['paginator' => $paginator])
    <link rel="canonical" href="{!! getCanonicalUrl() !!}">
@stop

@section('content')

<section class="filters">
    <div class="container content-section"><div class="expandable">
            {!! Form::open(array('id'=>'filter', 'method' => 'get')) !!}
            {!! Form::hidden('search', @$search) !!}
            <div class="row">
                <!-- <h3>Filter: </h3> -->
                <div class="col-md-4">
                    {!! Form::select('topic', array('' => '') + $topics, @$input['topic'], array('class'=> 'custom-select form-control', 'data-placeholder' => trans('katalog.filters_topic'))) !!}
                </div>
                <div class="col-md-8">
                    <div class="col-xs-6 col-sm-1 text-left text-sm-right year-range">
                            <span class="sans" id="from_year">{!! !empty($input['year-range']) ? reset((explode(',', $input['year-range']))) : $slider_min !!}</span>
                    </div>
                    <div class="col-xs-6 col-sm-1 col-sm-push-10 text-right text-sm-left year-range">
                            <span class="sans" id="until_year">{!! !empty($input['year-range']) ? end((explode(',', $input['year-range']))) : $slider_max !!}</span>
                    </div>
                    <div class="col-sm-10 col-sm-pull-1 year-range">
                            <input id="year-range" name="year-range" type="text" class="span2" data-slider-min="{!! $slider_min !!}" data-slider-max="{!! $slider_max !!}" data-slider-step="5" data-slider-value="[{!! !empty($input['year-range']) ? $input['year-range'] : $slider_min.','.$slider_max !!}]"/>
                    </div>
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

    $("form").submit(function()
    {
        $(this).find('input[name], select[name]').each(function(){
            if (!$(this).val()){
                $(this).data('name', $(this).attr('name'));
                $(this).removeAttr('name');
            }
        });
        if ( $('#year-range').val()=='{!!$slider_min!!},{!!$slider_max!!}' ) {
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


});

</script>
@stop
