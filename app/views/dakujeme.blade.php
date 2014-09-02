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
                        <p>Dovoľujeme si Vám oznámiť, že Vaša žiadosť o poskytnutie reprodukcií zbierkových predmetov Slovenskej národnej galérie a súhlas SNG s ich použitím pre uvedený účel bola prijatá. V priebehu nasledujúcich 30 dní Vás budeme kontaktovať. Zatiaľ Vás žiadame o trpezlivosť a prajeme pekný zvyšok dňa! </p>
                        <a href="{{ URL::to('/') }}" class="btn btn-primary">návrat na úvod</a>
                </div>
            </div>
        </div>
    </div>
</section>


@stop

@section('javascript')
@stop
