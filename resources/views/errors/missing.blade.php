@extends('layouts.master')

@section('title')
ERROR 404 - Not Found
@stop

@section('content')

<section class="intro intro404">
    <div class="intro-body">
        <div class="container">
            <div class="row">
                <div class="col-md-8 col-md-offset-2">
                    <div class="text-center">
                        <h1>ERROR 404</h1>
                        <h2>STRÁNKU NENAŠLO</h2>
                        @if ($item)
                            <p>No toto! Na tejto adrese nič nie je, ale môžete si pozrieť napríklad toto dielo:</p>

                            <a href="{!! $item->getUrl() !!}"><img src="{!! $item->getImagePath() !!}" class="img-responsive img-dielo"></a>
                            <p>
                                <a href="{!! $item->getUrl() !!}">{!! implode(', ', $item->authors)!!} - {!! $item->title !!}</a>
                            </p>
                        @else
                            <p>No toto! Na tejto adrese nič nie je.</p>
                        @endif

                        <h3><a href="{!!URL::to('/')!!}">návrat <i class="icon-versus"></i> domov</a></h3>

                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@stop
