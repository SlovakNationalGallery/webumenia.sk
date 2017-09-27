@extends('layouts.mg')

@section('title')
{{ trans('missing.title') }}
@stop

@section('content')

<section class="intro intro404">
    <div class="intro-body">
        <div class="container">
            <div class="row">
                <div class="col-md-8 col-md-offset-2">
                    <div class="text-center">
                        <h1>{{ trans('missing.h1') }}</h1>
                        <h2 class="uppercase">{{ trans('missing.h2') }}</h2>
                        @if ($item)
                            <p>{{ trans('missing.paragraph_item') }}</p>

                            <a href="{!! $item->getUrl() !!}"><img src="{!! $item->getImagePath() !!}" class="img-responsive img-dielo"></a>
                            <p>
                                <a href="{!! $item->getUrl() !!}">{!! implode(', ', $item->authors)!!} - {!! $item->title !!}</a>
                            </p>
                        @else
                            <p>{{ trans('missing.paragraph_noitem') }}</p>
                        @endif
                        <h3><a href="{!!URL::to('/')!!}" class="btn btn-default btn-lg btn-outline sans"> {{ trans('general.return_home') }}</a></h3>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@stop
