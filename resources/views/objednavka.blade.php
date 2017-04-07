@extends('layouts.master')

@section('og')
@stop

@section('title')
{{ trans('objednavka.title') }} | 
@parent
@stop

@section('content')

<section class="order content-section top-section">
    <div class="order-body">
        <div class="container">
            <div class="row">
                @if (Session::has('message'))
                    <div class="alert alert-info alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>{!! Session::get('message') !!}</div>
                @endif
                @if (strtotime('now') < strtotime('2016-12-24'))
                    <div class="alert alert-warning text-center" role="alert">
                        {!! trans('objednavka.order_alert') !!}
                    </div>
                @endif
                <div class="col-md-8 col-md-offset-2 text-center">
                        {!! trans('objednavka.order_content') !!}
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


{!!
  Former::open('objednavka')->class('form-bordered form-horizontal')->id('order')->rules(App\Order::$rules);
!!}

<div class="form-group required has-feedback"><label for="pids" class="control-label col-lg-2 col-sm-4">{{ trans('objednavka.orders_form_title') }}</label>
    <div class="col-lg-10 col-sm-8">
            @if ($items->count() == 0)
                <p class="text-center">{{ trans('objednavka.orders_none') }}</p>
            @endif

            @foreach ($items as $i=>$item)
                <div class="media">
                    <a href="{!! $item->getUrl() !!}" class="pull-left">
                        <img src="{!! $item->getImagePath() !!}" class="media-object" style="max-width: 80px; ">
                    </a>
                    <div class="media-body">                           
                        <a href="{!! $item->getUrl() !!}">
                            <em>{!! implode(', ', $item->authors) !!}</em> <br> <strong>{!! $item->title !!}</strong> (<em>{!! $item->getDatingFormated() !!}</em>)
                        </a><br>
                        <p class="item"><a href="{!! URL::to('dielo/' . $item->id . '/odstranit') !!}" class="underline"><i class="fa fa-times"></i> {{ trans('objednavka.orders_remove') }}</a></span>
                        @if (empty($item->iipimg_url))
                            <br><span class="bg-warning">{{ trans('objednavka.orders_warning') }}</span>
                        @endif
                    </div>
                </div>
            @endforeach                    
    </div>
</div>

{!! Former::hidden('pids')->value(implode(', ', Session::get('cart',array()))); !!}
{!! Former::text('name')->label(trans('objednavka.orders_form_name'))->required(); !!}
{!! Former::text('address')->label(trans('objednavka.orders_form_address')); !!}
{!! Former::text('email')->label(trans('objednavka.orders_form_email'))->required(); !!}
{!! Former::text('phone')->label(trans('objednavka.orders_form_phone'))->required(); !!}

{{-- 
{!! Former::select('format')->label('Formát')->required()->options(array(
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
)); !!}
 --}}

{!! Former::select('format')->label(trans('objednavka.orders_form_format'))->required()->options(array(
    trans('objednavka.orders_form_format_for-print') => array(
        'do A4: samostatná reprodukcia 25 €/ks' => array('value'=>trans('objednavka.orders_form_format_a4')), 
        'do A3+: samostatná reprodukcia 35 €/ks' => array('value'=>trans('objednavka.orders_form_format_a3')),
        ),
    trans('objednavka.orders_form_format_for-download') => array(
        'digitálna reprodukcia' => array('value'=>trans('objednavka.orders_form_format_digital'))
        ),
)); !!}

{{-- ak digitalna --}}
<div id="ucel">
    <div class="alert alert-info col-lg-offset-2 col-md-offset-4" role="alert">
        {!! trans('objednavka.orders_form_purpose-alert') !!}
    </div>
{!! Former::select('purpose_kind')->label(trans('objednavka.orders_form_purpose-label'))->required()->options(App\Order::$availablePurposeKinds); !!}
{!! Former::textarea('purpose')->label(trans('objednavka.orders_form_purpose-info'))->required(); !!}
</div>
{{-- /ak digitalna --}}

{{-- ak nie digitalna --}}
<div id="miesto_odberu">
{!! Former::select('delivery_point')->label(trans('objednavka.orders_form_delivery-point'))->required()->options(array(
        trans('objednavka.orders_form_delivery-point_exlibris') => array('value'=>'Kníhkupectvo Ex Libris v SNG'), 
        trans('objednavka.orders_form_delivery-point_zvolen') => array('value'=>'Zvolenský zámok'), 
)); !!}
</div>
{{-- /ak nie digitalna --}}

{!! Former::textarea('note')->label(trans('objednavka.orders_form_note')); !!}

{!! Former::actions(Form::submit(trans('objednavka.orders_form_order'), array('class'=>'btn btn-default btn-outline  uppercase sans')) ) !!}

{!!Former::close();!!}



            </div>
        </div>
    </div>
</section>


@stop

@section('javascript')
<script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/jquery.bootstrapvalidator/0.5.3/js/bootstrapValidator.min.js"></script>
{!! Html::script('js/jquery.bootstrapvalidator/sk_SK.js') !!}

<script type="text/javascript">

    $('#order').bootstrapValidator({
                feedbackIcons: {
                    valid: 'fa fa-check',
                    invalid: 'fa fa-times',
                    validating: 'fa fa-refresh'                    
                },
                live: 'enabled',
                submitButtons: 'input[type="submit"]',
                locale: 'sk_SK',
                excluded: [':disabled', ':hidden', ':not(:visible)']
    })
    .on('change', '#format', function() {
            var isDigital = $(this).val() == 'digitálna reprodukcia';
            $('#order').bootstrapValidator('enableFieldValidators', 'purpose', isDigital);
        });

    function tooglePurpose() {
        if( $('#format').val() == 'digitálna reprodukcia')  {
            $("#ucel").show();
            $("#purpose").attr("disabled", false);
            $("#miesto_odberu").hide();
            $("#delivery_point").attr("disabled", true);
        } else {
            $("#ucel").hide();        
            $("#purpose").attr("disabled", true);
            $("#miesto_odberu").show();
            $("#delivery_point").attr("disabled", false);
        }
    } 

    tooglePurpose();

    $("#format").change(function(){
        tooglePurpose()
    });


</script>
@stop
