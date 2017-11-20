@extends('layouts.master')

@section('title')
{{ trans('kolekcie.title') }} | 
@parent
@stop

@section('link')
    @include('includes.pagination_links', ['paginator' => $collections])
    <link rel="canonical" href="{!! getCanonicalUrl() !!}">
@stop

@section('content')

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
                    {{ trans('general.sort_by') }} {!! App\Collection::$sortable[$sort_by]; !!}
                    <span class="caret"></span>
                  </a>
                  <ul class="dropdown-menu dropdown-menu-right dropdown-menu-sort" role="menu" aria-labelledby="dropdownSortBy">
                    @foreach (App\Collection::$sortable as $sort=>$label)
                        <li role="presentation"><a role="menuitem" tabindex="-1" href="#" rel="{!! $sort !!}">{!! $label !!}</a></li>
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
                <div class="artworks-preview">
                @foreach ($collection->getPreviewItems() as $item)
                    <a href="{!! $item->getUrl(['collection' => $collection->id]) !!}"><img data-lazy="{!! $item->getImagePath() !!}" class="img-responsive-width" alt="{!! $item->getTitleWithAuthors() !!} " ></a>
                @endforeach
                </div>
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

});

</script>
@stop
