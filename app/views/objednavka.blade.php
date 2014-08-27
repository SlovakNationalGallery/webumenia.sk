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
                <div class="col-md-8 col-md-offset-2 text-center">
                        <img src="/images/x.svg" alt="x" class="xko">
                    	<h2 class="uppercase bottom-space">Objednávka</h2>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="order content-section">
    <div class="order-body">
        <div class="container">
            <div class="row">
            	<div class="col-xs-12">
            		<!-- <h3>Diela: </h3> -->


{{
  Former::open('order')->class('form-bordered form-horizontal')->id('order')->rules(Order::$rules);
}}

<div class="form-group required has-feedback"><label for="pids" class="control-label col-lg-2 col-sm-4">Diela objednávky<sup>*</sup></label>
    <div class="col-lg-10 col-sm-8">
            @if ($items->count() == 0)
                <p class="text-center">Nemáte v košíku žiadne diela</p>
            @endif

            @foreach ($items as $i=>$item)
               
                    <a href="{{ $item->getDetailUrl() }}">
                        <img src="{{ $item->getImagePath() }}" class="img-responsive pull-left" style="max-width: 100px; ">
                    </a>
                    <div class="clearfix"></div>
                    <div class="item-title">                            
                        <a href="{{ $item->getDetailUrl() }}">
                            <strong>{{ implode(', ', $item->authors) }} &ndash; {{ $item->title }}</strong> (<em>{{ $item->getDatingFormated() }}</em>)
                        </a>
                    </div>
            @endforeach                    
    </div>
</div>

{{ Former::hidden('pids')->label('Diela objednávky')->required(); }}
{{ Former::textarea('organization')->label('Organizácia, osoba')->required(); }}
{{ Former::text('contactPerson')->label('Kontaktná osoba')->required(); }}
{{ Former::text('email')->label('E-mail kontaktnej osoby')->required(); }}
{{ 
  Former::select('kindOfPurpose')->label('Účel použitia')->options(array('Žiadne',
'Komerčný' => array("value" => 'Komerčný' ),
'Vedecký' => array("value" => 'Vedecký' ),
'Súkromný' => array("value" => 'Súkromný' ),
'Edukačný' => array("value" => 'Edukačný' ),
'Výstava' => array("value" => 'Výstava' )))->required();
 }}


{{ Former::textarea('purpose')->label('Účel')->required(); }}
{{ Former::select('medium')->label('Médium')->required()->options(array(
'Žiadne' => array('name' => 'medium', 'value'=>''), 
'Publikácia' => array('name' => 'medium', 'value'=>'Publikácia'), 
'Monografia' => array('name' => 'medium', 'value'=>'Monografia'), 
'Film' => array('name' => 'medium', 'value'=>'Film'), 
'Článok' => array('name' => 'medium', 'value'=>'Článok'), 
'Kalendár' => array('name' => 'medium', 'value'=>'Kalendár'), 
'Iné' => array('name' => 'medium', 'value'=>'Iné'), 
)); }}


{{ Former::text('address')->label('Adresa'); }}
{{ Former::text('phone')->label('Telefón'); }}
{{ Former::text('ico')->label('IČO'); }}
{{ Former::text('dic')->label('DIČ'); }}
{{ 
Former::radios('no-dph')->label('Som platca DPH')
  ->radios(array(
    'Áno' => array('name' => 'o-dph', 'value' => '0'),
    'Nie' => array('name' => 'o-dph', 'value' => '1'),
  ))->inline()->required();
}}

{{ Former::text('numOfCopies')->label('Náklad')->required(); }}
{{ Former::select('numOfCopies')->label('Náklad')->options(array(1,2,3,4,5,6,7))->help('Počet kusov'); }}


{{ Former::actions(Form::submit('Objednať', array('class'=>'btn btn-primary')) ) }}


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
