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
                    <div class="intro-text">
                        <h1>ERROR 404</h1>
                        <h2>STRÁNKU NENAŠLO</h2>
                        <h3><a href="{{URL::to('/')}}" class="white">návrat <i class="icon-versus"></i> domov</a></h3>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@stop
