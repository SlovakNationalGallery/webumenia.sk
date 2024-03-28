@extends('layouts.blank')

@section('title')
{{ trans('maintenance.title') }}
@stop

@section('content')

<section class="intro intro500">
    <div class="intro-body">
        <div class="container">
            <div class="row">
                <div class="col-md-8 col-md-offset-2 content-section">
                    <div class="text-center">
                        <h2 class="top-margin tw-uppercase">{{ trans('maintenance.h2') }}</h2>
                        <p>{{ trans('maintenance.paragraph') }}</p>
                        <a href="/dielo/SVK:SNG.UP-DK_1104"><img src="/images/errors/error.maintenance.jpeg" alt="Sezónni robotníci. Oprava trate" class="img-responsive img-dielo"></a>
                        <p>
                            <a href="/dielo/SVK:SNG.UP-DK_1104">{!! trans('maintenance.image_caption') !!}</a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@stop
