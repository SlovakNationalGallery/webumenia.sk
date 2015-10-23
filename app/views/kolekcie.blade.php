@extends('layouts.master')

@section('title')
kolekcie | 
@parent
@stop

@section('content')

{{-- <section class="filters">
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
                    <b class="sans" id="from_year">{{ !empty($input['year-range']) ? reset((explode(',', $input['year-range']))) : Collection::sliderMin() }}</b> 
            </div>
            <div class="col-sm-10 year-range">
                    <input id="year-range" name="year-range" type="text" class="span2" data-slider-min="{{ Collection::sliderMin() }}" data-slider-max="{{ Collection::sliderMax() }}" data-slider-step="5" data-slider-value="[{{ !empty($input['year-range']) ? $input['year-range'] : Collection::sliderMin().','.Collection::sliderMax() }}]"/> 
            </div>
            <div class="col-sm-1 text-left year-range">
                    <b class="sans" id="until_year">{{ !empty($input['year-range']) ? end((explode(',', $input['year-range']))) : Collection::sliderMax() }}</b>
            </div>
        </div>
        <div class="row" style="padding-top: 20px;">
            <div  class="col-sm-12 text-center alphabet sans">
                @foreach (range('A', 'Z') as $char)
                    <a href="{{ url_to('kolekcie', ['first-letter' => $char]) }}" class="{{ (Input::get('first-letter')==$char) ? 'active' : '' }}" rel="{{ $char }}">{{ $char }}</a> &nbsp;
                @endforeach
                {{ Form::hidden('first-letter', @$input['first-letter'], ['id'=>'first-letter']) }}
                {{ Form::hidden('sort_by', @$input['sort_by'], ['id'=>'sort_by']) }}
            </div>
        </div>
         {{ Form::close() }}
         @endif
    </div>
</section> --}}

{{ Form::open(array('id'=>'filter', 'method' => 'get')) }}
    {{ Form::hidden('sort_by', @$input['sort_by'], ['id'=>'sort_by']) }}
{{ Form::close() }}
<section class="collections">
    <div class="container">
        <div class="row content-section">
        	<div class="col-xs-6">
                @if (!empty($search))
                    <h4 class="inline">Nájdené kolekcie pre &bdquo;{{ $search }}&ldquo; (<span>{{ $collections->getTotal() }}</span>) </h4> 
                @else
            		<h4 class="inline">{{ $collections->getTotal() }} kolekcií</h4>
                @endif
                @if ($collections->count() == 0)
                    <p class="text-center">Momentálne žiadne kolekcie</p>
                @endif
                {{--  @if (count(Input::all()) > 0)
                    <a class="btn btn-sm btn-default btn-outline  sans" href="{{ URL::to('kolekcie')}}">zrušiť filtre <i class="icon-cross"></i></a>
                @endif --}}
            </div>
            <div class="col-xs-6 text-right">
                <div class="dropdown">
                  <a class="dropdown-toggle" type="button" id="dropdownSortBy" data-toggle="dropdown" aria-expanded="true">
                    podľa {{ Collection::$sortable[$sort_by]; }}
                    <span class="caret"></span>
                  </a>
                  <ul class="dropdown-menu dropdown-menu-right dropdown-menu-sort" role="menu" aria-labelledby="dropdownSortBy">
                    @foreach (Collection::$sortable as $sort=>$label)
                        <li role="presentation"><a role="menuitem" tabindex="-1" href="#" rel="{{ $sort }}">{{ $label }}</a></li>
                    @endforeach
                  </ul>
                </div>
            </div>
        </div>
        <div class="kolekcie">
    	@foreach ($collections as $i=>$collection)
         <div class="row collection">   
            {{-- <div class="col-sm-2 col-xs-4">
            	<a href="{{ $collection->getUrl() }}">
            		<img src="{{ $collection->getHeaderImage() }}" class="img-responsive pentagon" alt="{{ $collection->name }}">	                		
            	</a>
            </div> --}}
            <div class="col-sm-6 col-xs-12">
                <div class="collection-title">
                    <a href="{{ $collection->getUrl() }}" class="underline">
                        <strong>{{ $collection->name }}</strong>
                    </a>
                </div>
                <div class="collection-meta grey">
                   {{--  {{ $collection->author }} &#9679;  --}}
                    {{ $collection->created_at->format('d. m. Y') }} &#9679; 
                    {{ $collection->user->name }} &#9679; 
                    {{ $collection->items->count() }} diel
                </div>
                <div>
                    {{ $collection->getShortTextAttribute($collection->text, 350) }}
                </div>
            </div>
            <div class="clearfix visible-xs bottom-space"></div>
            <div class="col-sm-6" >
                <div class="artworks-preview">
                @foreach ($collection->getPreviewItems() as $item)
                    <a href="{{ $item->getUrl(['collection' => $collection->id]) }}"><img data-lazy="{{ $item->getImagePath() }}" class="img-responsive-width" alt="{{ $item->getTitleWithAuthors() }} " ></a>
                @endforeach
                </div>
            </div>
        </div>
    	@endforeach
        <div class="row">
            <div class="col-sm-12 text-center">
                {{ $collections->appends(@Input::except('page'))->links() }}
            </div>
        </div>
        </div>{{-- kolekcie --}}

        </div>
    </div>
</section>


@stop

@section('javascript')

{{ HTML::script('js/slick.js') }}

<script type="text/javascript">

$(document).ready(function(){

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

    $(".dropdown-menu-sort a").click(function(e) {
        e.preventDefault();
        $('#sort_by').val($(this).attr('rel'));
        $('#filter').submit();
    });


    // var $container = $('.kolekcie');

    // $container.infinitescroll({
    //     navSelector     : ".pagination",
    //     nextSelector    : ".pagination a:last",
    //     collectionSelector    : ".collection",
    //     debug           : true,
    //     dataType        : 'html',
    //     donetext        : 'boli načítaní všetci kolekcie',
    //     path            : undefined,
    //     bufferPx     : 200,
    //     loading: {
    //         msgText: "<em>Načítavam ďalších kolekcií...</em>",
    //         finishedMsg: 'A to je všetko'
    //     }
    // });

});

</script>
@stop
