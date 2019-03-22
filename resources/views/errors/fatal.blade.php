@extends('layouts.master')

@section('title')
{{ trans('fatal.title') }}
@stop

@section('content')

<section class="intro intro500">
    <div class="intro-body">
        <div class="container">
            <div class="row">
                <div class="col-md-8 col-md-offset-2 content-section">
                    <div class="text-center">
                        {{-- <h1>ERROR 500</h1> --}}
                        <h2 class="top-margin uppercase">{{ trans('fatal.h2') }}</h2>
                        <p>{{ trans('fatal.paragraph') }}</p>

                        <h3><a href="{!!URL::to('/')!!}" class="btn btn-default btn-lg btn-outline sans"> {{ trans('general.return_home') }}</a></h3>

                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@stop
