@extends('layouts.master')

@section('title')
@parent
| autori
@stop

@section('content')

<section class="top-section">
    <div class="catalog-body">
        <div class="container">
        </div>
    </div>
</section>

<section class="filters">
    <div class="container content-section">
        @if (empty($cc))
        {{ Form::open(array('id'=>'filter', 'method' => 'get')) }}
        {{ Form::hidden('search', @$search) }}
        <div class="row">
            <!-- <h3>Filter: </h3> -->
            <div  class="col-md-4 col-xs-6">
                    {{ Form::select('role', array('' => '') + $roles,  @$input['role'], array('class'=> 'chosen-select form-control', 'data-placeholder' => 'Rola')) }}
            </div>
            <div  class="col-md-4 col-xs-6">
                    {{ Form::select('nationality', array('' => '') + $nationalities, @$input['nationality'], array('class'=> 'chosen-select form-control', 'data-placeholder' => 'Príslušnosť')) }}
            </div>
            <div  class="col-md-4 col-xs-6">
                    {{ Form::select('place', array('' => '') + $places,  @$input['place'], array('class'=> 'chosen-select form-control', 'data-placeholder' => 'Miesto')) }}
            </div>
        </div>
        <div class="row">
            <div class="col-sm-1 text-right year-range">
                    <b class="sans" id="from_year">{{ !empty($input['year-range']) ? reset((explode(',', $input['year-range']))) : Authority::sliderMin() }}</b> 
            </div>
            <div class="col-sm-10 year-range">
                    <input id="year-range" name="year-range" type="text" class="span2" data-slider-min="{{ Authority::sliderMin() }}" data-slider-max="{{ Authority::sliderMax() }}" data-slider-step="5" data-slider-value="[{{ !empty($input['year-range']) ? $input['year-range'] : Authority::sliderMin().','.Authority::sliderMax() }}]"/> 
            </div>
            <div class="col-sm-1 text-left year-range">
                    <b class="sans" id="until_year">{{ !empty($input['year-range']) ? end((explode(',', $input['year-range']))) : Authority::sliderMax() }}</b>
            </div>
        </div>
        <div class="row" style="padding-top: 20px;">
            <div  class="col-sm-12 text-center alphabet sans">
                @foreach (range('A', 'Z') as $char)
                    <a href="{{ url_to('autori', ['first-letter' => $char]) }}" class="{{ (Input::get('first-letter')==$char) ? 'active' : '' }}" rel="{{ $char }}">{{ $char }}</a> &nbsp;
                @endforeach
                {{ Form::hidden('first-letter', @$input['first-letter'], ['id'=>'first-letter']) }}
            </div>
        </div>
         {{ Form::close() }}
         @endif
    </div>
</section>
<section class="authors">
    <div class="container content-section">
        <div class="row">
        	<div class="col-xs-6">
                @if (!empty($search))
                    <h4 class="inline">Nájdení autori pre &bdquo;{{ $search }}&ldquo; (<span data-searchd-total-hits>{{ $authors->total() }}</span>) </h4> 
                @else
            		<h4 class="inline">{{ $authors->total() }} autorov</h4>
                @endif
                @if ($authors->count() == 0)
                    <p class="text-center">Momentálne žiadni autori</p>
                @endif
                @if (count(Input::all()) > 0)
                    <a class="btn btn-default btn-outline  uppercase sans" href="{{ URL::to('autori')}}">zrušiť filtre</a>
                @endif
            </div>
            <div class="col-xs-6 text-right">
                <div class="dropdown">
                  <a class="dropdown-toggle" type="button" id="dropdownSortBy" data-toggle="dropdown" aria-expanded="true">
                    podľa dátumu pridania
                    <span class="caret"></span>
                  </a>
                  <ul class="dropdown-menu dropdown-menu-right" role="menu" aria-labelledby="dropdownSortBy">
                    <li role="presentation"><a role="menuitem" tabindex="-1" href="#">dátumu pridania</a></li>
                    <li role="presentation"><a role="menuitem" tabindex="-1" href="#">autora</a></li>
                    <li role="presentation"><a role="menuitem" tabindex="-1" href="#">diela</a></li>
                    <li role="presentation"><a role="menuitem" tabindex="-1" href="#">počet videní</a></li>
                  </ul>
                </div>
            </div>
        </div>
        <div class="autori">
    	@foreach ($authors as $i=>$author)
         <div class="row author">   
            <div class="col-sm-2">
            	<a href="{{ $author->getDetailUrl() }}">
            		<img src="{{ $author->getImagePath() }}" class="img-responsive img-circle" alt="{{ $author->name }}">	                		
            	</a>
            </div>
            <div class="col-sm-4">
                <div class="author-title">
                    <a href="{{ $author->getDetailUrl() }}" {{ (!empty($search))  ? 
                        'data-searchd-result="title/'.$author->id.'" data-searchd-title="'. $author->formatedName.'"' 
                        : '' }}>
                        <strong>{{ $author->formatedName }}</strong>
                    </a>
                </div>
                <div>
                    {{ $author->birth_year }} {{ $author->birth_place }} 
                    @if ($author->death_year)
                        &ndash; {{ $author->death_year }} {{ $author->death_place }} 
                    @endif
                </div>
                <div>
                    {{ implode(", ", $author->roles->lists('role')) }}
                </div>
                <div>
                    <a href="{{ url_to('katalog', ['author' => $author->name]) }}"><strong>{{ $author->items_count }}</strong></a> diel
                </div>

            </div>
            <div class="col-sm-6" >
                <div class="artworks-preview">
                @foreach ($author->getPreviewItems() as $item)
                    <a href="{{ $item->getDetailUrl() }}"><img data-lazy="{{ $item->getImagePath() }}" class="img-responsive-width" ></a>
                @endforeach
                </div>
            </div>
        </div>
    	@endforeach
        <div class="row">
            <div class="col-sm-12 text-center">
                {{ $paginator->appends(@Input::except('page'))->links() }}
            </div>
        </div>
        </div>{{-- autori --}}

        </div>
    </div>
</section>


@stop

@section('javascript')

{{ HTML::script('js/bootstrap-slider.min.js') }}
{{ HTML::script('js/chosen.jquery.min.js') }}
{{ HTML::script('js/slick.js') }}

<script type="text/javascript">

$(document).ready(function(){

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

    $(".chosen-select").chosen({allow_single_deselect: true})

    $(".chosen-select").change(function() {
        $(this).closest('form').submit();
    });

    $(".alphabet a").click(function(e) {
        e.preventDefault();
        $('#first-letter').val($(this).attr('rel'));
        $(this).closest('form').submit();
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

    var $container = $('.autori');

    $container.infinitescroll({
        navSelector     : ".pagination",
        nextSelector    : ".pagination a:last",
        authorSelector    : ".author",
        debug           : true,
        dataType        : 'html',
        donetext        : 'boli načítaní všetci autori',
        path            : undefined,
        bufferPx     : 200,
        loading: {
            msgText: "<em>Načítavam ďalších autorov...</em>",
            finishedMsg: 'A to je všetko'
        }
    });

});

</script>
@stop
