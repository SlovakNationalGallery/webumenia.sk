@extends('layouts.master')

@section('og')
@stop

@section('title')
{{ trans('dakujeme.title') }} | 
@parent
@stop

@section('content')

<section class="collection content-section top-section">
    <div class="collection-body">
        <div class="container">
            <div class="row">
                @if (Session::has('message'))
                    <div class="alert alert-info alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>{!! Session::get('message') !!}</div>
                @endif
                <div class="col-md-8 col-md-offset-2 text-center">
                    	<h2 class="bottom-space">{{ utrans('dakujeme.title') }}!</h2>
                        <p>{{ trans('dakujeme.paragraph') }}</p>
                        <a href="{!! URL::to('/') !!}" class="btn btn-default btn-outline  uppercase sans">{{ trans('dakujeme.button') }}</a>
                </div>
            </div>
        </div>
    </div>
</section>


@stop

@section('javascript')
@stop
