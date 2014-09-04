@extends('layouts.master')

@section('og')
@stop

@section('title')
@parent
| ďakujeme
@stop

@section('content')

<section class="collection content-section top-section">
    <div class="collection-body">
        <div class="container">
            <div class="row">
                @if (Session::has('message'))
                    <div class="alert alert-info alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>{{ Session::get('message') }}</div>
                @endif
                <div class="col-md-8 col-md-offset-2 text-center">
                        <img src="/images/x.svg" alt="x" class="xko">
                    	<h2 class="uppercase bottom-space">Ďakujeme!</h2>
                        <p>Dovoľujeme si Vám oznámiť, že Vaša objednávka bola prijatá. V priebehu nasledujúcich dní Vás budeme kontaktovať. Zatiaľ Vás žiadame o trpezlivosť a prajeme pekný zvyšok dňa! </p>
                        <a href="{{ URL::to('/') }}" class="btn btn-default btn-outline  uppercase sans">návrat na úvod</a>
                </div>
            </div>
        </div>
    </div>
</section>


@stop

@section('javascript')
@stop
