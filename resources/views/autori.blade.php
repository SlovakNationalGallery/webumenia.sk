@extends('layouts.master')

@section('title')
{!! getTitleWithFilters('App\Authority', $input, ' | ') !!}
{{ trans('autori.title') }} |
@parent
@stop

@section('link')
    @include('includes.pagination_links', ['paginator' => $paginator])
    <link rel="canonical" href="{!! getCanonicalUrl() !!}">
@stop

@section('content')

{{-- no filters for now --}}
{{--
<section class="filters">
    <div class="container content-section">
        @if (empty($cc))
        {!! Form::open(array('id'=>'filter', 'method' => 'get')) !!}
        {!! Form::hidden('search', @$search) !!}
        <div class="row">
            <!-- <h3>Filter: </h3> -->
            <div  class="col-md-4 col-xs-6 bottom-space">
                    {!! Form::select('role', array('' => '') + $roles,  @$input['role'], array('class'=> 'custom-select form-control', 'data-placeholder' => utrans('autori.filters_role') )) !!}
            </div>
            <div  class="col-md-4 col-xs-6 bottom-space">
                    {!! Form::select('nationality', array('' => '') + $nationalities, @$input['nationality'], array('class'=> 'custom-select form-control', 'data-placeholder' => utrans('autori.filters_nationality'))) !!}
            </div>
            <div  class="col-md-4 col-xs-6 bottom-space">
                    {!! Form::select('place', array('' => '') + $places,  @$input['place'], array('class'=> 'custom-select form-control', 'data-placeholder' => utrans('autori.filters_place'))) !!}
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
 --}}

<section class="authors py-5">
    @foreach ($authors as $i=>$author)
    <div class="author">
        <a href="{!! $author->getUrl() !!}" class="author-title">
            {!! $author->formatedName !!}
        </a>
    </div>
    @endforeach
    {{--
    <div class="row">
        <div class="col-sm-12 text-center">
            {!! $paginator->appends(@Input::except('page'))->render() !!}
        </div>
    </div>
        --}}
</section>

<section class="purpose py-5">
    <p>{{ trans('autori.intention') }}</p>
    <p>
        {{ trans('autori.plan', ['type' => (Request::is('teoretici')) ? 'teoretikov' : 'umelcov']) }}<br>
        {{ trans('autori.subscribe') }}
    </p>

    <div class="col-lg-6 col-md-10 border-0">
    @include('components.khb_newsletter_signup_form')
    </div>

</section>

<div class="row border-top">
    @foreach (range('A', 'Z') as $char)
        <div class="col-3 col-sm-2 alphabet text-sans grid-cell-link {!! (Input::get('first-letter')==$char) ? 'active' : '' !!}">
            <a href="{!! url_to(((Request::is('teoretici')) ? 'teoretici' : 'umelci'), ['first-letter' => $char]) !!}" rel="{!! $char !!}"></a>
            <span>{!! $char !!}</span>
        </div>
    @endforeach
</div>


@stop

@section('javascript')


<script type="text/javascript">

</script>
@stop
