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
                <div class="col-md-8 col-md-offset-2">

                    @if (Session::has('message'))
                        <div class="alert alert-info alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>{!! Session::get('message') !!}</div>
                    @endif
                    @if (strtotime('now') < strtotime('2019-12-24'))
                        <div class="alert alert-warning text-center" role="alert">
                            {!! trans('objednavka.order_alert') !!}
                        </div>
                    @endif

                    <div class="alert alert-warning">
                        {!! trans('reprodukcie.alert_covid-19') !!}
                    </div>
                    
                    
                    <h2 class="bottom-space text-center">{{ trans('objednavka.order_title') }}</h2>
                    {!! trans('objednavka.order_content') !!}

                </div>
            </div>
        </div>
    </div>
</section>

<section class="order content-section">
        <div class="container">
            <div class="row">
            	<div class="col-xs-12 col-md-10">
            		<!-- <h3>Diela: </h3> -->


{!!
  Former::open()->route('objednavka.post')->class('form-bordered form-horizontal')->id('order')->rules(App\Order::$rules);
!!}

<div class="form-group required has-feedback"><label for="pids" class="control-label col-lg-2 col-sm-4">{{ trans('objednavka.form_title') }}</label>
    <div class="col-lg-8 col-sm-8">
            @if ($items->count() == 0)
                <p class="text-center">{{ trans('objednavka.order_none') }}</p>
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
                        <p class="item"><a href="{!! URL::to('dielo/' . $item->id . '/odstranit') !!}" class="underline"><i class="fa fa-times"></i> {{ trans('objednavka.order_remove') }}</a></span>
                        @if ($item->images->isEmpty())
                            <br><span class="bg-warning">{{ trans('objednavka.order_warning') }}</span>
                        @endif
                    </div>
                </div>
            @endforeach

            @if($errors->any())
                <div class="alert alert-danger">
                    <a href="#" class="close" data-dismiss="alert">&times;</a>
                    {!! implode('', $errors->all('<li class="error">:message</li>')) !!}
                </div>
            @endif

    </div>
</div>

{!! Former::hidden('pids')->value(implode(', ', Session::get('cart',array()))); !!}
{!! Former::text('name')->label(trans('objednavka.form_name'))->required(); !!}
{!! Former::text('address')->label(trans('objednavka.form_address')); !!}
{!! Former::text('email')->label(trans('objednavka.form_email'))->required(); !!}
{!! Former::text('phone')->label(trans('objednavka.form_phone'))->required(); !!}

@if ($allow_printed_reproductions)

    {!! Former::select('format')->label(trans('objednavka.form_format'))->required()->options([
        trans('objednavka.form_format_for-print_a4') => [
            'do A4: samostatná reprodukcia 28 €/ks' => [
                'value'=> trans('objednavka.form_format_standalone') . ' (28 €/'.trans('objednavka.form_piece').')'
            ],
            'do A4: reprodukcia s paspartou 38 €/ks' => [
                'value'=> trans('objednavka.form_format_with_mounting') . ' (38 €/'.trans('objednavka.form_piece').')'
            ],
            'do A4: s paspartou a rámom 48 €/ks' => [
                'value'=> trans('objednavka.form_format_with_mounting_and_framing') . ' (48 €/'.trans('objednavka.form_piece').')'
            ],
        ],
        trans('objednavka.form_format_for-print_a3') => [
            'do A3+: samostatná reprodukcia 40 €/ks' => [
                'value'=> trans('objednavka.form_format_standalone') . ' (40 €/'.trans('objednavka.form_piece').')'
            ],
            'do A3+: reprodukcia s paspartou 55 €/ks' => [
                'value'=> trans('objednavka.form_format_with_mounting') . ' (55 €/'.trans('objednavka.form_piece').')'
            ],
            'do A3+: s paspartou a rámom 65 €/ks' => [
                'value'=> trans('objednavka.form_format_with_mounting_and_framing') . ' (65 €/'.trans('objednavka.form_piece').')'
            ],
        ],
        trans('objednavka.form_format_for-print_a2') => [
            'do A2: samostatná reprodukcia 50 €/ks' => [
                'value'=> trans('objednavka.form_format_standalone') . ' (50 €/'.trans('objednavka.form_piece').')'
            ],
        ],
        trans('objednavka.form_format_for-print_a1') => [
            'do A1: samostatná reprodukcia 60 €/ks' => [
                'value'=> trans('objednavka.form_format_standalone') . ' (60 €/'.trans('objednavka.form_piece').')'
            ],
        ],
        trans('objednavka.form_format_for-poster_a1') => [
            'poster A1: samostatná reprodukcia 38 €/ks' => [
                'value'=> trans('objednavka.form_format_standalone') . ' (38 €/'.trans('objednavka.form_piece').')'
            ],
        ],
        trans('objednavka.form_format_for-download') => [
                'digitálna reprodukcia' => ['value'=>trans('objednavka.form_format_digital')]
        ],
    ]); !!}

@else

    {!! Former::select('format')->label('Formát')->required()->options([
        trans('objednavka.form_format_for-download') => [
                'digitálna reprodukcia' => ['value'=>trans('objednavka.form_format_digital')]
        ],
    ]); !!}

@endif


{{-- {!! Former::select('format')->label(trans('objednavka.form_format'))->required()->options(array(
    trans('objednavka.form_format_for-print') => array(
        'do A4: samostatná reprodukcia 25 €/ks' => array(
            'value'=>trans('objednavka.form_format_a4')
        ),
        'do A3+: samostatná reprodukcia 35 €/ks' => array('value'=>trans('objednavka.form_format_a3')),
        ),
    trans('objednavka.form_format_for-download') => array(
        'digitálna reprodukcia' => array('value'=>trans('objednavka.form_format_digital'))
        ),
)); !!}
 --}}

{{-- ak digitalna --}}
<div id="ucel">
    <div class="alert alert-info col-lg-offset-2 col-md-offset-4" role="alert">
        {!! trans('objednavka.form_purpose-alert') !!}
        @if ($allow_printed_reproductions)
            <br><strong>{!! trans('objednavka.form_purpose-alert-print') !!}</strong>
        @endif
    </div>
    {!! Former::select('purpose_kind')->label(trans('objednavka.form_purpose-label'))->required()->options([
            trans('objednavka.form_purpose_private') => ['value'=> 'Súkromný'],
            trans('objednavka.form_purpose_commercial') => ['value'=> 'Komerčný'],
            trans('objednavka.form_purpose_research') => ['value'=> 'Výskumný'],
            trans('objednavka.form_purpose_education') => ['value'=> 'Edukačný'],
            trans('objednavka.form_purpose_exhibition') => ['value'=> 'Výstava'],
        ]); !!}
    {!! Former::textarea('purpose')->label(trans('objednavka.form_purpose-info'))->required(); !!}
</div>
{{-- /ak digitalna --}}

{{-- ak nie digitalna --}}
<div id="for_frame">
    {!! Former::select('frame')->label(trans('objednavka.form_frame'))->required()->options(array(
            trans('objednavka.form_frame_black') => array('value'=>'čierny'),
            trans('objednavka.form_frame_white') => array('value'=>'svetly'),
    ))->help('<a href="#" class="underline" data-toggle="modal" data-target="#previewFrames"><i class="fa fa-info-circle"></i> '.trans('objednavka.form_frame_help').'</a>'); !!}
</div>
<div id="for_printed">
    {!! Former::select('delivery_point')->label(trans('objednavka.form_delivery-point'))->required()->options(array(
            trans('objednavka.form_delivery-point_exlibris') => array('value'=>'Kníhkupectvo Ex Libris v SNG'),
            trans('objednavka.form_delivery-point_zvolen') => array('value'=>'Zvolenský zámok'),
    )); !!}
</div>
{{-- /ak nie digitalna --}}

{{-- ak digitalna --}}
<div id="poster_alert">
    <div class="alert alert-info col-lg-offset-2 col-md-offset-4" role="alert">
        {!! trans('objednavka.form_purpose-alert-poster') !!}
    </div>
</div>
{{-- /ak digitalna --}}



{!! Former::textarea('note')->label(trans('objednavka.form_note')); !!}

<div class="form-group">
    <div class="col-lg-2 col-sm-4">&nbsp;</div>
    <div class="col-lg-10 col-sm-8">
        <div class="checkbox">
            <input id="terms_and_conditions" name="terms_and_conditions" type="checkbox" value="1" required>
            <label for="terms_and_conditions">
              {!! trans('objednavka.form_terms_and_conditions') !!}
              <sup>*</sup>
            </label>
        </div>
    </div>
</div>


{!! Former::actions(Form::submit(trans('objednavka.form_order'), array('class'=>'btn btn-default btn-outline  uppercase sans')) ) !!}

{!!Former::close();!!}



            </div>
        </div>
    </div>
</section>

<!-- Modal -->
<div tabindex="-1" class="modal fade" id="previewFrames" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header text-center">
                <h1>{{ trans('objednavka.modal_frame_colors') }}</h1>
            </div>
            <div class="modal-body">
                <p>
                    <img src="/images/frames.jpg" alt="frames preview" class="img-responsive">
                </p>
                <p class="text-left">
                    {{ trans('objednavka.modal_frame_availability') }}<br>
                    {{ trans('objednavka.modal_frame_multiple') }}
                </p>
            </div>
            <div class="modal-footer">
                <div class="text-center"><button type="button" data-dismiss="modal" class="btn btn-default btn-outline sans">{{ trans('general.close') }}</button></div>
            </div>
        </div>
    </div>
</div>

@stop

@section('javascript')

<script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/jquery.bootstrapvalidator/0.5.3/js/bootstrapValidator.min.js"></script>
{{-- default language en_US is bundled with bootstrapValidator.min.js --}}
@if (App::getLocale() == 'sk')
    {!! Html::script('js/jquery.bootstrapvalidator/sk_SK.js') !!}
@elseif (App::getLocale() == 'cs')
    {!! Html::script('js/jquery.bootstrapvalidator/cs_CZ.js') !!}
@endif

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
        $("#poster_alert").hide();
        if( $('#format').val() == 'digitálna reprodukcia')  {
            $("#ucel").show();
            $("#purpose").attr("disabled", false);
            $("#for_printed").hide();
            $("#delivery_point").attr("disabled", true);
            $("#for_frame").hide();
            $("#frame").attr("disabled", true);
        } else {
            $("#ucel").hide();
            $("#purpose").attr("disabled", true);
            $("#for_printed").show();
            $("#delivery_point").attr("disabled", false);
            if( $('#format').val().indexOf("poster") >= 0)  {
                $("#poster_alert").show();
            }
            if( $('#format').val().indexOf("rámom") >= 0)  {
                $("#for_frame").show();
                $("#frame").attr("disabled", false);
            } else {
                $("#for_frame").hide();
                $("#frame").attr("disabled", true);
            }
        }
    }

    tooglePurpose();

    $("#format").change(function(){
        tooglePurpose()
    });


</script>
@stop
