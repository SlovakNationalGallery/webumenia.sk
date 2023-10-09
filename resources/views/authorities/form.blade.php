@extends('layouts.admin')

@section('content')

@if(isset($authority))
  {!! Form::model($authority, ['route' => ['authority.update', $authority->id], 'method' => 'patch', 'files'=>true]) !!}
@else
  {!! Form::open(['route' => 'authority.store', 'files'=>true]) !!}
@endif

<div class="col-md-12 top-space">
  @if (Session::has('message'))
    <div class="alert alert-info alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>{!! Session::get('message') !!}</div>
  @endif


  @if($errors->any())
    <div class="alert alert-danger">
      <a href="#" class="close" data-dismiss="alert">&times;</a>
      {!! implode('', $errors->all('<li class="error">:message</li>')) !!}
    </div>
  @endif
</div>

<div class="col-lg-12">
  <div class="panel panel-default">
    <div class="panel-heading">
      Autorita
    </div>
    <div class="panel-body">
      <div class="row">

        @if(isset($new_id))
          <div class="col-md-12">
            <div class="form-group">
              {!! Form::label('id', 'Id') !!}
              {!! Form::text('id', $new_id, array('class' => 'form-control', 'readonly')) !!}
            </div>
          </div>
        @endif
        <div class="col-md-12">
          <div class="form-group">
            {!! Form::label('name', 'celé meno (Priezvisko, Meno)') !!}
            {!! Form::text('name', Request::old('name'), array('class' => 'form-control')) !!}
          </div>
        </div>


        <!-- translatable -->
        <div class="col-md-12">

          <!-- Nav tabs -->
          <ul class="nav nav-tabs top-space" role="tablist">
            @foreach (\Config::get('translatable.locales') as $i=>$locale)
            <li role="presentation" class="{{ ($i==0) ? 'active' : '' }}"><a href="#{{ $locale }}" aria-controls="{{ $locale }}" role="tab" data-toggle="tab">{{ strtoupper($locale) }}</a></li>
            @endforeach
          </ul>

          <div class="tab-content top-space">
            @foreach (\Config::get('translatable.locales') as $i=>$locale)
            <div role="tabpanel" class="tab-pane  {{ ($i==0) ? 'active' : '' }}" id="{{ $locale }}">

              <div class="form-group">
                {{ Form::label($locale . "[biography]", 'Biografia '.strtoupper($locale)) }}
                {{ Form::textarea($locale . "[biography]", isset($authority) ? @$authority->translate($locale)->biography : '', array('class' => 'form-control wysiwyg', 'rows'=>'12')) }}
              </div>

            </div>
            @endforeach
          </div>

        </div>
        <!-- /translatable -->

      </div>
      <!-- /.row (nested) -->
    </div>
    <!-- /.panel-body -->
  </div>
  <!-- /.panel -->
</div>
@isset($authority)
<div class="col-lg-12">
  <div class="panel panel-default">
    <div class="panel-heading">
      Použité zdroje
    </div>
    <div class="panel-body">
      <admin-links-input
        collection-name="sourceLinks"
        :value="{{ json_encode(Request::old('sourceLinks', $authority->sourceLinks)) }}"
      ></admin-links-input>
    </div>
  </div>
</div>

<div class="col-lg-12">
  <div class="panel panel-default">
    <div class="panel-heading">
      Externé odkazy
    </div>
    <div class="panel-body">
      <admin-links-input
        collection-name="externalLinks"
        :value="{{ json_encode(Request::old('externalLinks', $authority->externalLinks)) }}"
      ></admin-links-input>
    </div>
  </div>
</div>
@endisset
<div class="col-lg-12">
  <div class="panel panel-default">
    <div class="panel-heading">
      Obrázok
    </div>
    <div class="panel-body">
      <div class="row">
        <div class="col-md-5 col-md-offset-1">
          <div class="form-group">
            {!! Form::label('image_source_url', 'Zdroj obrázku (URL)') !!}
            {!! Form::text('image_source_url', Request::old('image_source_url'), array('class' => 'form-control form_link', 'placeholder' => 'http://')) !!}
          </div>
        </div>
        <div class="col-md-5">
          <div class="form-group">
            {!! Form::label('image_source_label', 'Zdroj obrázku (Zobrazený text)') !!}
            {!! Form::text('image_source_label', Request::old('image_source_label'), array('class' => 'form-control', 'placeholder' => 'wikipédia')) !!}
          </div>
        </div>
      </div>
      <!-- /.row (nested) -->
      <div class="row">

        <div class="col-md-offset-4 col-md-4 text-center">
          <div id="image-editor">
            <div class="cropit-image-preview-container">
              <div class="cropit-image-preview"></div>
            </div>

            <div class="image-size-label">&nbsp;</div>
            <div class="form-group" style="padding: 0 15px">
              <input type="text" class="cropit-image-zoom-input" min="0" max="1" step="0.01" data-slider-min="0" data-slider-max="1" data-slider-step="0.01" data-slider-value="0">
            </div>

            <input type="file" class="cropit-image-input" />
          </div>

          <a class="btn btn-success btn-outline select-image-btn"><i class="fa fa-picture-o"></i> nahrať obrázok</a>
          <br />
          <button type="button" id="clear-image" class="mt-3 btn btn-danger btn-xs btn-outline" style="display:none">
            <i class="fa fa-times"></i> zmazať obrázok
          </button>

          {!! Form::hidden('primary_image', null, ['id' => 'primary_image']) !!}
          {!! Form::hidden('clear_primary_image', null, ['id' => 'clear_primary_image']) !!}
        </div>

      </div>
      <!-- /.row (nested) -->
    </div>
    <!-- /.panel-body -->
  </div>
  <!-- /.panel -->
</div>

<div class="col-md-12 text-center">
  {!! Form::submit('Uložiť', array('class' => 'btn btn-default')) !!} &nbsp;
  @if(isset($authority) && $authority->record)
    <a href="{!!URL::to('harvests/'.$authority->record->id.'/refreshRecord')!!}" class="btn btn-warning">Obnoviť z OAI</a>
  @endif
  {!! link_to_route('authority.index', 'Zrušiť', null, array('class' => 'btn btn-default')) !!}
  {!!Form::close() !!}
</div>

<div class="clear">&nbsp;</div>
@stop

@section('script')

{!! Html::script('js/bootstrap-slider.min.js') !!}
{!! Html::script('js/jquery.cropit.min.js') !!}

<script>
$(document).ready(function(){
  const $clearImageButton = $('button#clear-image');
  const $clearImageInput = $('input#clear_primary_image')

  $(".cropit-image-zoom-input").slider({
    tooltip: 'hide'
  });

  $('#image-editor').cropit({
    imageBackground: true,
    imageBackgroundBorderWidth: 20,
    onImageLoaded: () => {
      $('#image-editor').show();
      $clearImageButton.show();
      $clearImageInput.val('')
    }
    @if (isset($authority) && $authority->has_image && file_exists($authority->getImagePath(true)) )
      ,imageState: {
        src: '{!! $authority->getImagePath() !!}'
    }
    @endif
  });

  $('.select-image-btn').click(function() {
    $('.cropit-image-input').click();
  });

  $clearImageButton.click(function() {
    $('#image-editor').hide()
    $clearImageButton.hide()
    $clearImageInput.val(true)
  });

  $('form').submit(function(e) {
    var imageData = $('#image-editor').cropit('export', {
      type: 'image/jpeg',
      quality: .9
    });
    $('#primary_image').val(imageData);
      return true;
  });

});

</script>
@stop
