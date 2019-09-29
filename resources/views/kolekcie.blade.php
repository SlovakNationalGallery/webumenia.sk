@extends('layouts.master')

@section('title')
{{ trans('kolekcie.title') }} |
@parent
@stop

@section('link')
    @include('includes.pagination_links', ['paginator' => $collections])
@stop

@section('content')

@foreach ($collections as $i=>$collection)
    @if ( ! $collection->hasTranslation(App::getLocale()) )
        <section>
            <div class="container top-section">
                <div class="row">
                    @include('includes.message_untranslated')
                    @break
                </div>
            </div>
        </section>
    @endif
@endforeach

{{-- <section class="filters">
    <div class="container content-section">
        @if (empty($cc))
        {!! Form::open(array('id'=>'filter', 'method' => 'get')) !!}
        {!! Form::hidden('search', @$search) !!}
        <div class="row">
            <!-- <h3>Filter: </h3> -->
            <div  class="col-md-4 col-xs-6">
                    {!! Form::select('role', array('' => '') + $roles,  @$input['role'], array('class'=> 'chosen-select form-control', 'data-placeholder' => 'Rola')) !!}
            </div>
            <div  class="col-md-4 col-xs-6">
                    {!! Form::select('nationality', array('' => '') + $nationalities, @$input['nationality'], array('class'=> 'chosen-select form-control', 'data-placeholder' => 'Príslušnosť')) !!}
            </div>
            <div  class="col-md-4 col-xs-6">
                    {!! Form::select('place', array('' => '') + $places,  @$input['place'], array('class'=> 'chosen-select form-control', 'data-placeholder' => 'Miesto')) !!}
            </div>
        </div>
        <div class="row">
            <div class="col-sm-1 text-right year-range">
                    <b class="sans" id="from_year">{!! !empty($input['year-range']) ? reset((explode(',', $input['year-range']))) : App\Collection::sliderMin() !!}</b>
            </div>
            <div class="col-sm-10 year-range">
                    <input id="year-range" name="year-range" type="text" class="span2" data-slider-min="{!! App\Collection::sliderMin() !!}" data-slider-max="{!! App\Collection::sliderMax() !!}" data-slider-step="5" data-slider-value="[{!! !empty($input['year-range']) ? $input['year-range'] : App\Collection::sliderMin().','.App\Collection::sliderMax() !!}]"/>
            </div>
            <div class="col-sm-1 text-left year-range">
                    <b class="sans" id="until_year">{!! !empty($input['year-range']) ? end((explode(',', $input['year-range']))) : App\Collection::sliderMax() !!}</b>
            </div>
        </div>
        <div class="row" style="padding-top: 20px;">
            <div  class="col-sm-12 text-center alphabet sans">
                @foreach (range('A', 'Z') as $char)
                    <a href="{!! url_to('kolekcie', ['first-letter' => $char]) !!}" class="{!! (Input::get('first-letter')==$char) ? 'active' : '' !!}" rel="{!! $char !!}">{!! $char !!}</a> &nbsp;
                @endforeach
                {!! Form::hidden('first-letter', @$input['first-letter'], ['id'=>'first-letter']) !!}
                {!! Form::hidden('sort_by', @$input['sort_by'], ['id'=>'sort_by']) !!}
            </div>
        </div>
         {!! Form::close() !!}
         @endif
    </div>
</section> --}}

{!! Form::open(array('id'=>'filter', 'method' => 'get')) !!}
    {!! Form::hidden('sort_by', @$input['sort_by'], ['id'=>'sort_by']) !!}
{!! Form::close() !!}
<section class="collections">
    <div class="container">
        <div class="row content-section">
        	<div class="col-xs-6">
                @if (!empty($search))
                    <h4 class="inline">{{ utrans('kolekcie.collections_found_collections') }} &bdquo;{!! $search !!}&ldquo; (<span>{!! $collections->total() !!}</span>) </h4>
                @else
            		<h4 class="inline">{!! $collections->total() !!} {{ trans('kolekcie.collections_collections') }}</h4>
                @endif
                @if ($collections->count() == 0)
                    <p class="text-center">{{ utrans('kolekcie.collections_no_results') }}</p>
                @endif
                {{--  @if (count(Input::all()) > 0)
                    <a class="btn btn-sm btn-default btn-outline  sans" href="{!! URL::to('kolekcie')!!}">zrušiť filtre <i class="icon-cross"></i></a>
                @endif --}}
            </div>
            <div class="col-xs-6 text-right">
                <div class="dropdown">
                  <a class="dropdown-toggle" type="button" id="dropdownSortBy" data-toggle="dropdown" aria-expanded="true">
                    {{ trans('general.sort_by') }} {!! trans(App\Collection::$sortable[$sort_by]) !!}
                    <span class="caret"></span>
                  </a>
                  <ul class="dropdown-menu dropdown-menu-right dropdown-menu-sort" role="menu" aria-labelledby="dropdownSortBy">
                    @foreach (App\Collection::$sortable as $sort=>$labelKey)
                        @if ($sort != $sort_by)
                            <li role="presentation"><a role="menuitem" tabindex="-1" href="#" rel="{!! $sort !!}">{!! trans($labelKey) !!}</a></li>
                        @endif
                    @endforeach
                  </ul>
                </div>
            </div>
        </div>
        <div class="kolekcie">
    	@foreach ($collections as $i=>$collection)
         <div class="row collection">
            {{-- <div class="col-sm-2 col-xs-4">
            	<a href="{!! $collection->getUrl() !!}">
            		<img src="{!! $collection->getHeaderImage() !!}" class="img-responsive pentagon" alt="{!! $collection->name !!}">
            	</a>
            </div> --}}
            <div class="col-sm-6 col-xs-12">
                <div class="collection-title">
                    <a href="{!! $collection->getUrl() !!}" class="underline">
                        <strong>{!! $collection->name !!}</strong>
                    </a>
                </div>
                <div class="collection-meta grey">
                   {{--  {!! $collection->author !!} &nbsp;&middot;&nbsp; --}}
                    {!! $collection->created_at->format('d. m. Y') !!} &nbsp;&middot;&nbsp;
                    {!! $collection->user->name !!} &nbsp;&middot;&nbsp;
                    {!! $collection->items->count() !!} {{ trans('kolekcie.collections_artworks') }}
                </div>
                <div>
                    {!! $collection->getShortTextAttribute($collection->text, 350) !!}
                </div>
            </div>
            <div class="clearfix visible-xs bottom-space"></div>
            <div class="col-sm-6" >
                @include('components.artwork_carousel', [
                    'slick_target' => "artworks-preview",
                    'slick_variant' => "small",
                    'items' => $collection->getPreviewItems(),
                ])
            </div>
        </div>
    	@endforeach
        <div class="row">
            <div class="col-sm-12 text-center">
                {!! $collections->appends(@Request::except('page'))->render() !!}
            </div>
        </div>
        </div>{{-- kolekcie --}}

        </div>
    </div>
</section>


@stop

@section('javascript')

{!! Html::script('js/slick.js') !!}
{!! Html::script('js/components/artwork_carousel.js') !!}

<script type="text/javascript">

$(document).ready(function(){

    $(".dropdown-menu-sort a").click(function(e) {
        e.preventDefault();
        $('#sort_by').val($(this).attr('rel'));
        $('#filter').submit();
    });

});

</script>
@stop
