@extends('layouts.master')

@section('og')
@stop

@section('title')
{{ trans('download.thank_you') }} |
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
                    	<h2 class="bottom-space">{{ utrans('download.thank_you') }}!</h2>
                        <p>{{ trans('download.thank_you_paragraph') }}</p>
                        <a href="{!! URL::to('/') !!}" class="btn btn-primary uppercase sans">{{ trans('dielo.item_download') }}</a>
                </div>
            </div>
        </div>
    </div>
</section>


@stop

@section('javascript')
@stop
