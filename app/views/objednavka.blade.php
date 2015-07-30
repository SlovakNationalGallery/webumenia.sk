@extends('layouts.master')

@section('og')
@stop

@section('title')
@parent
| objednávka
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
                    	<h2 class="bottom-space">Objednávka</h2>
                        <p>K vybraným dielam zo zbierok SNG ponúkame možnosť objednať si reprodukcie v archívnej kvalite na fineartových papieroch. Po výbere diel, vyplnení údajov a odoslaní objednávky vás bude kontaktovať pracovník SNG s podrobnejšími informáciami. Momentálne je možné vyzdvihnúť si diela len osobne v kníhkupectve <a href="https://goo.gl/maps/3Uf4S" target="_blank" class="underline">Ex Libris v priestoroch SNG na Námestí Ľ. Štúra 4</a> v Bratislave. </p>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="order content-section">
        <div class="container">
            <div class="row">
            	<div class="col-xs-12">
            		<!-- <h3>Diela: </h3> -->


{{
  Former::open('objednavka')->class('form-bordered form-horizontal')->id('order')->rules(Order::$rules);
}}

<div class="form-group required has-feedback"><label for="pids" class="control-label col-lg-2 col-sm-4">Diela objednávky</label>
    <div class="col-lg-10 col-sm-8">
            @if ($items->count() == 0)
                <p class="text-center">Nemáte v košíku žiadne diela</p>
            @endif

            @foreach ($items as $i=>$item)
                <div class="media">
                    <a href="{{ $item->getDetailUrl() }}" class="pull-left">
                        <img src="{{ $item->getImagePath() }}" class="media-object" style="max-width: 80px; ">
                    </a>
                    <div class="media-body">                           
                        <a href="{{ $item->getDetailUrl() }}">
                            <em>{{ implode(', ', $item->authors) }}</em> <br> <strong>{{ $item->title }}</strong> (<em>{{ $item->getDatingFormated() }}</em>)
                        </a><br>
                        <p class="item"><a href="{{ URL::to('dielo/' . $item->id . '/odstranit') }}" class="underline"><i class="fa fa-times"></i> odstrániť</a></span>
                        @if (empty($item->iipimg_url))
                            <br><span class="bg-warning">Toto dielo momentálne nemáme zdigitalizované v dostatočnej kvalite, vybavenie objednávky preto môže trvať dlhšie ako zvyčajne.</span>
                        @endif
                    </div>
                </div>
            @endforeach                    
    </div>
</div>

{{ Former::hidden('pids')->value(implode(', ', Session::get('cart',array()))); }}
{{ Former::text('name')->label('Meno')->required(); }}
{{ Former::text('address')->label('Adresa'); }}
{{ Former::text('email')->label('E-mail')->required(); }}
{{ Former::text('phone')->label('Telefón'); }}


{{ Former::select('format')->label('Formát')->required()->options(array(
    'do formátu A4 :' => array(
        'do A4: samostatná reprodukcia 25 €/ks' => array('value'=>'samostatná reprodukcia (25 €/ks)'), 
        'do A4: reprodukcia s paspartou 35 €/ks' => array('value'=>'reprodukcia s paspartou (35 €/ks)'), 
        'do A4: s paspartou a rámom 40 €/ks' => array('value'=>'s paspartou a rámom (40 €/ks)'), 
        ),
    'od A4 do A3+ :' => array(
        'do A3+: samostatná reprodukcia 35 €/ks' => array('value'=>'samostatná reprodukcia (35 €/ks)'), 
        'do A3+: reprodukcia s paspartou 50 €/ks' => array('value'=>'reprodukcia s paspartou (50 €/ks)'), 
        'do A3+: s paspartou a rámom 60 €/ks' => array('value'=>'s paspartou a rámom (60 €/ks)'), 
        ),
    'na stiahnutie :' => array(
        'digitálna reprodukcia' => array('value'=>'digitálna reprodukcia')
        ),
)); }}

{{ Former::textarea('note')->label('Poznámka'); }}




{{ Former::actions(Form::submit('Objednať', array('class'=>'btn btn-default btn-outline  uppercase sans')) ) }}


{{Former::close();}}



            </div>
        </div>
    </div>
</section>


@stop

@section('javascript')
<script type="text/javascript" src="//cdn.jsdelivr.net/jquery.bootstrapvalidator/0.5.0/js/bootstrapValidator.min.js"></script>

<script type="text/javascript">
        $('#order').bootstrapValidator({
                    feedbackIcons: {
                        valid: 'fa fa-check',
                        invalid: 'fa fa-times',
                        validating: 'fa fa-refresh'                    
                    },
                    live: 'enabled',
            submitButtons: 'input[type="submit"]'
        });

</script>
@stop
