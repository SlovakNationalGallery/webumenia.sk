@extends('layouts.master')

@section('title')
{!! getTitleWithFilters('App\Authority', $input, ' | ') !!}
autori |  
@parent
@stop

@section('link')
    @include('includes.pagination_links', ['paginator' => $paginator])
    <link rel="canonical" href="{!! getCanonicalUrl() !!}">
@stop

@section('content')

<section class="filters">
    <div class="container content-section">
        @if (empty($cc))
        {!! Form::open(array('id'=>'filter', 'method' => 'get')) !!}
        {!! Form::hidden('search', @$search) !!}
        <div class="row">
            <!-- <h3>Filter: </h3> -->
            <div  class="col-md-4 col-xs-6 bottom-space">
                    {!! Form::select('role', array('' => '') + $roles,  @$input['role'], array('class'=> 'custom-select form-control', 'data-placeholder' => 'Rola')) !!}
            </div>
            <div  class="col-md-4 col-xs-6 bottom-space">
                    {!! Form::select('nationality', array('' => '') + $nationalities, @$input['nationality'], array('class'=> 'custom-select form-control', 'data-placeholder' => 'Príslušnosť')) !!}
            </div>
            <div  class="col-md-4 col-xs-6 bottom-space">
                    {!! Form::select('place', array('' => '') + $places,  @$input['place'], array('class'=> 'custom-select form-control', 'data-placeholder' => 'Miesto')) !!}
            </div>
        </div>
        <div class="row">
            <div class="col-xs-6 col-sm-1 text-left text-sm-right year-range">
                    <b class="sans" id="from_year">{!! !empty($input['year-range']) ? reset((explode(',', $input['year-range']))) : App\Authority::sliderMin() !!}</b> 
            </div>
            <div class="col-xs-6 col-sm-1 col-sm-push-10 text-right text-sm-left year-range">
                    <b class="sans" id="until_year">{!! !empty($input['year-range']) ? end((explode(',', $input['year-range']))) : App\Authority::sliderMax() !!}</b>
            </div>
            <div class="col-sm-10 col-sm-pull-1 year-range">
                    <input id="year-range" name="year-range" type="text" class="span2" data-slider-min="{!! App\Authority::sliderMin() !!}" data-slider-max="{!! App\Authority::sliderMax() !!}" data-slider-step="5" data-slider-value="[{!! !empty($input['year-range']) ? $input['year-range'] : App\Authority::sliderMin().','.App\Authority::sliderMax() !!}]"/> 
            </div>
        </div>
        <div class="row" style="padding-top: 20px;">
            <div  class="col-sm-12 text-center alphabet sans">
                @foreach (range('A', 'Z') as $char)
                    <a href="{!! url_to('autori', ['first-letter' => $char]) !!}" class="{!! (Input::get('first-letter')==$char) ? 'active' : '' !!}" rel="{!! $char !!}">{!! $char !!}</a> &nbsp;
                @endforeach
                {!! Form::hidden('first-letter', @$input['first-letter'], ['id'=>'first-letter']) !!}
                {!! Form::hidden('sort_by', @$input['sort_by'], ['id'=>'sort_by']) !!}
            </div>
        </div>
         {!! Form::close() !!}
         @endif
    </div>
</section>
<section class="authors">
    <div class="container">
        <div class="row content-section">
        	<div class="col-xs-6">
                @if (!empty($search))
                    <h4 class="inline">Nájdení autori pre &bdquo;{!! $search !!}&ldquo; (<span data-searchd-total-hits>{!! $authors->total() !!}</span>) </h4> 
                @else
            		<h4 class="inline">{!! $authors->total() !!} autorov</h4>
                @endif
                @if ($authors->count() == 0)
                    <p class="text-center">Momentálne žiadni autori</p>
                @endif
                @if (count(Input::all()) > 0)
                    <a class="btn btn-sm btn-default btn-outline  sans" href="{!! URL::to('autori')!!}">zrušiť filtre <i class="icon-cross"></i></a>
                @endif
            </div>
            <div class="col-xs-6 text-right">
                <div class="dropdown">
                  <a class="dropdown-toggle" type="button" id="dropdownSortBy" data-toggle="dropdown" aria-expanded="true">
                    podľa {!! App\Authority::$sortable[$sort_by]; !!}
                    <span class="caret"></span>
                  </a>
                  <ul class="dropdown-menu dropdown-menu-right dropdown-menu-sort" role="menu" aria-labelledby="dropdownSortBy">
                    @foreach (App\Authority::$sortable as $sort=>$label)
                        <li role="presentation"><a role="menuitem" tabindex="-1" href="#" rel="{!! $sort !!}">{!! $label !!}</a></li>
                    @endforeach
                  </ul>
                </div>
            </div>
        </div>
        <div class="autori">
    	@foreach ($authors as $i=>$author)
         <div class="row author">   
            <div class="col-sm-2 col-xs-4">
            	<a href="{!! $author->getUrl() !!}">
            		<img src="{!! $author->getImagePath() !!}" class="img-responsive img-circle" alt="{!! $author->name !!}">	                		
            	</a>
            </div>
            <div class="col-sm-4 col-xs-8">
                <div class="author-title">
                    <a href="{!! $author->getUrl() !!}" {!! (!empty($search))  ? 
                        'data-searchd-result="title/'.$author->id.'" data-searchd-title="'. $author->formatedName.'"' 
                        : '' !!}>
                        <strong>{!! $author->formatedName !!}</strong>
                    </a>
                </div>
                <div>
                    {!! $author->birth_year !!} {!! $author->birth_place !!} 
                    @if ($author->death_year)
                        &ndash; {!! $author->death_year !!} {!! $author->death_place !!} 
                    @endif
                </div>
                <div>
                    @foreach ($author->roles as $i=>$role)
                        <a href="{!! url_to('autori', ['role' => $role->role]) !!}"><strong>{!! $role->role !!}</strong></a>{!! ($i+1 < $author->roles->count()) ? ', ' : '' !!}
                    @endforeach

                </div>
                <div>
                    <a href="{!! url_to('katalog', ['author' => $author->name]) !!}"><strong>{!! $author->items_count !!}</strong></a> diel
                </div>

            </div>
            <div class="clearfix visible-xs bottom-space"></div>
            <div class="col-sm-6" >
                <div class="artworks-preview">
                @foreach ($author->getPreviewItems() as $item)
                    <a href="{!! $item->getUrl() !!}"><img data-lazy="{!! $item->getImagePath() !!}" class="img-responsive-width" alt="{!! $item->getTitleWithAuthors() !!} " title="{!! $item->getTitleWithAuthors() !!} "></a>
                @endforeach
                </div>
            </div>
        </div>
    	@endforeach
        <div class="row">
            <div class="col-sm-12 text-center">
                {!! $paginator->appends(@Input::except('page'))->render() !!}
            </div>
        </div>
        </div>{{-- autori --}}

        </div>
    </div>
</section>


@stop

@section('javascript')

{!! Html::script('js/bootstrap-slider.min.js') !!}
{!! Html::script('js/selectize.min.js') !!}
{!! Html::script('js/slick.js') !!}

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
        if ( $('#year-range').val()=='{!!App\Authority::sliderMin()!!},{!!App\Authority::sliderMax()!!}' ) {
            $('#year-range').attr("disabled", true);
        }
    });

    $("#year-range").slider({
        // value: [1500, 2014],
        tooltip: 'hide'
    }).on('slideStop', function(event) {
        $(this).closest('form').submit();
    }).on('slide', function(event) {
        var rozsah = $("#year-range").val().split(',');
        $('#from_year').html(rozsah[0]);
        $('#until_year').html(rozsah[1]);
    });

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

    $(".custom-select").change(function() {
        $(this).closest('form').submit();
    });

    $(".alphabet a").click(function(e) {
        e.preventDefault();
        $('#first-letter').val($(this).attr('rel'));
        $(this).closest('form').submit();
    });

    $(".dropdown-menu-sort a").click(function(e) {
        e.preventDefault();
        $('#sort_by').val($(this).attr('rel'));
        $('#filter').submit();
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

    // var $container = $('.autori');

    // $container.infinitescroll({
    //     navSelector     : ".pagination",
    //     nextSelector    : ".pagination a:last",
    //     authorSelector    : ".author",
    //     debug           : true,
    //     dataType        : 'html',
    //     donetext        : 'boli načítaní všetci autori',
    //     path            : undefined,
    //     bufferPx     : 200,
    //     loading: {
    //         msgText: "<em>Načítavam ďalších autorov...</em>",
    //         finishedMsg: 'A to je všetko'
    //     }
    // });

});

</script>
@stop
