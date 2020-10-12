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
            {!! Form::label('type', 'typ autority') !!}
            {!! Form::select('type', ['author' => 'author', 'theoretician' => 'theoretician'], Input::old('type'), array('class' => 'form-control')) !!}

          </div>
        </div>

        <div class="col-md-12">
          <div class="form-group">
            {!! Form::label('name', 'celé meno (Priezvisko, Meno)') !!}
            {!! Form::text('name', Input::old('name'), array('class' => 'form-control')) !!}
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
                {!! Form::label($locale . "[birth_place]", 'Miesto narodenia '.strtoupper($locale)) !!}
                {!! Form::text($locale . "[birth_place]", isset($authority) ? @$authority->translate($locale)->birth_place : '', array('class' => 'form-control')) !!}
              </div>
              <div class="form-group">
                {{ Form::label($locale . "[biography]", 'Biografia '.strtoupper($locale)) }}
                {{ Form::textarea($locale . "[biography]", isset($authority) ? @$authority->translate($locale)->biography : '', array('class' => 'form-control wysiwyg', 'rows'=>'12')) }}
              </div>

              <div class="form-group">
                  {{ Form::label($locale . "[exhibitions]", 'Zoznam výstav '.strtoupper($locale)) }}
                  {{ Form::textarea($locale . "[exhibitions]", isset($authority) ? @$authority->translate($locale)->exhibitions : '', array('class' => 'form-control wysiwyg', 'rows'=>'12')) }}
              </div>

              <div class="form-group">
                  {{ Form::label($locale . "[bibliography]", 'Bibliografia '.strtoupper($locale)) }}
                  {{ Form::textarea($locale . "[bibliography]", isset($authority) ? @$authority->translate($locale)->bibliography : '', array('class' => 'form-control wysiwyg', 'rows'=>'12')) }}
              </div>

              <div class="form-group">
                  {{ Form::label($locale . "[archive]", 'Archív '.strtoupper($locale)) }}
                  {{ Form::textarea($locale . "[archive]", isset($authority) ? @$authority->translate($locale)->archive : '', array('class' => 'form-control wysiwyg', 'rows'=>'12')) }}
              </div>

              <div class="form-group">
                  <label for="document-dropzone-{{$locale}}">Archív súbory {{ strtoupper($locale) }}</label>
                  <div class="needsclick dropzone" id="document-dropzone-{{$locale}}" data-locale="{{ $locale }}" >
                    <div class="dz-message needsclick">
                      <strong>Drop</strong> files here or <strong>click</strong> to upload.<br />
                      <span class="note needsclick small text-muted">(Supported file types are image/document/pdf/video. Max filesize: 6MB)</span>
                    </div>

                  </div>
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


<div class="col-lg-12">
  <div class="panel panel-default">
    <div class="panel-heading">
      KHB data
    </div>
    <div class="panel-body">
      <div class="col-md-12">
        <div class="form-group">
          {!! Form::label('active_in', 'Miesto pôsobenia') !!}
          {!! Form::text('active_in', Input::old('active_in'), array('class' => 'form-control')) !!}
        </div>
      </div>
      <div class="col-md-12">
        <div class="form-group">
          {!! Form::label('studied_at', 'Štúdium') !!}
          {!! Form::text('studied_at', Input::old('studied_at'), array('class' => 'form-control')) !!}
        </div>
      </div>
      <div class="col-md-12">
        <div class="form-group">
          {!! Form::label('birth_year', 'Dátum narodenia (rok)') !!}
          {!! Form::text('birth_year', Input::old('birth_year'), array('class' => 'form-control')) !!}
        </div>
      </div>
      <div class="col-md-12">
        <div class="form-group">
        {!! Form::label('tags', 'tagy') !!}
        {!! Form::select('tags[]', \Conner\Tagging\Model\Tag::lists('name','name'), (isSet($authority)) ? $authority->tagNames() : [], ['id' => 'tags', 'multiple' => 'multiple']) !!}

        </div>
      </div>

    </div>
    <!-- /.panel-body -->
  </div>
  <!-- /.panel -->
</div>

<div class="col-lg-12">
  <div class="panel panel-default">
    <div class="panel-heading">
      Externé odkazy
      <?php $link_counter = 0 ?>
    </div>
    <div class="panel-body">
      @if(isset($authority))
        <?php $link_counter += $authority->links->count() ?>
        @foreach ($authority->links as $i=>$link)
          <div class="row">
            <div class="col-md-5">
              <div class="form-group">
                {!! Form::label('url', 'URL') !!}
                {!! Form::text('links['.$i.'][url]', $link->url, array('class' => 'form-control form_link', 'placeholder' => 'http://')) !!}
                {!! Form::hidden('links['.$i.'][id]', $link->id) !!}
              </div>
            </div>
            <div class="col-md-5">
              <div class="form-group">
                {!! Form::label('label', 'Zobrazený text') !!}
                {!! Form::text('links['.$i.'][label]', $link->label, array('class' => 'form-control', 'placeholder' => 'wikipédia')) !!}
              </div>
            </div>
            <div class="col-md-2 text-right">
              <div class="form-group">
                {!! Form::label('', '&nbsp;', ['class'=>'force-block']) !!}
                <a href="{!! URL::to('authority/destroyLink', array('link_id'=>$link->id ))  !!}"  class="btn btn-danger btn-outline"><i class="fa-times fa"></i> zmazať</a>
              </div>
            </div>
          </div>
        @endforeach
      @endif
      <div class="row" id="external_links">
        <div class="col-md-5" id="urls">
          <div class="form-group">
            {!! Form::label('url', 'URL') !!}
            {!! Form::text('links['.$link_counter.'][url]', Input::old('links['.$link_counter.'][url]'), array('class' => 'form-control form_link', 'placeholder' => 'http://')) !!}
          </div>
        </div>
        <div class="col-md-5"  id="labels">
          <div class="form-group">
            {!! Form::label('label', 'Zobrazená adresa') !!}
            {!! Form::text('links['.$link_counter.'][label]', Input::old('links['.$link_counter.'][label]'), array('class' => 'form-control', 'placeholder' => 'wikipédia')) !!}
          </div>
        </div>
        <div class="col-md-2 text-right">
          <div class="form-group">
            {!! Form::label('', '&nbsp;', ['class'=>'force-block']) !!}
            <a class="btn btn-info btn-outline" id="add_link"><i class="fa fa-plus"></i> pridať</a>
          </div>
        </div>
      </div>
      <!-- /.row (nested) -->
    </div>
    <!-- /.panel-body -->
  </div>
  <!-- /.panel -->
</div>

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
            {!! Form::text('image_source_url', Input::old('image_source_url'), array('class' => 'form-control form_link', 'placeholder' => 'http://')) !!}
          </div>
        </div>
        <div class="col-md-5">
          <div class="form-group">
            {!! Form::label('image_source_label', 'Zdroj obrázku (Zobrazený text)') !!}
            {!! Form::text('image_source_label', Input::old('image_source_label'), array('class' => 'form-control', 'placeholder' => 'wikipédia')) !!}
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
            <a class="btn btn-success btn-outline select-image-btn"><i class="fa fa-picture-o"></i> nahrať obrázok</a>
            {!! Form::hidden('primary_image', null, ['id' => 'primary_image']) !!}

          </div>
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

{!! Html::script('js/plugins/bootstrap-slider.min.js') !!}
{!! Html::script('js/plugins/jquery.cropit.min.js') !!}
{!! Html::script('js/plugins/selectize.min.js') !!}
<script src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.5.1/min/dropzone.min.js"></script>


<script>
  Dropzone.autoDiscover = false;


  $(".dropzone").each(function() {
    var uploadedDocumentMap = {};
    var locale= $(this).data('locale');

  $(this).dropzone({
    url: '{{ route('authority.storeMedia') }}',
    maxFilesize: 6, // MB
    addRemoveLinks: true,
    headers: {
      'X-CSRF-TOKEN': "{{ csrf_token() }}"
    },
    params: {
      'locale': locale
    },
    previewTemplate: "<div class=\"dz-preview dz-file-preview\">\n  <div class=\"dz-image\"><img data-dz-thumbnail /></div>\n  <div class=\"dz-details\">\n    <div class=\"dz-size\"><span data-dz-size></span></div>\n    <div class=\"dz-filename\"><span data-dz-name></span></div>\n  </div>\n  <div class=\"dz-progress\"><span class=\"dz-upload\" data-dz-uploadprogress></span></div>\n  <div class=\"dz-error-message\"><span data-dz-errormessage></span></div>\n  <div class=\"dz-success-mark\">\n    <svg width=\"54px\" height=\"54px\" viewBox=\"0 0 54 54\" version=\"1.1\" xmlns=\"http://www.w3.org/2000/svg\" xmlns:xlink=\"http://www.w3.org/1999/xlink\" xmlns:sketch=\"http://www.bohemiancoding.com/sketch/ns\">\n      <title>Check</title>\n      <defs></defs>\n      <g id=\"Page-1\" stroke=\"none\" stroke-width=\"1\" fill=\"none\" fill-rule=\"evenodd\" sketch:type=\"MSPage\">\n        <path d=\"M23.5,31.8431458 L17.5852419,25.9283877 C16.0248253,24.3679711 13.4910294,24.366835 11.9289322,25.9289322 C10.3700136,27.4878508 10.3665912,30.0234455 11.9283877,31.5852419 L20.4147581,40.0716123 C20.5133999,40.1702541 20.6159315,40.2626649 20.7218615,40.3488435 C22.2835669,41.8725651 24.794234,41.8626202 26.3461564,40.3106978 L43.3106978,23.3461564 C44.8771021,21.7797521 44.8758057,19.2483887 43.3137085,17.6862915 C41.7547899,16.1273729 39.2176035,16.1255422 37.6538436,17.6893022 L23.5,31.8431458 Z M27,53 C41.3594035,53 53,41.3594035 53,27 C53,12.6405965 41.3594035,1 27,1 C12.6405965,1 1,12.6405965 1,27 C1,41.3594035 12.6405965,53 27,53 Z\" id=\"Oval-2\" stroke-opacity=\"0.198794158\" stroke=\"#747474\" fill-opacity=\"0.816519475\" fill=\"#FFFFFF\" sketch:type=\"MSShapeGroup\"></path>\n      </g>\n    </svg>\n  </div>\n  <div class=\"dz-error-mark\">\n    <svg width=\"54px\" height=\"54px\" viewBox=\"0 0 54 54\" version=\"1.1\" xmlns=\"http://www.w3.org/2000/svg\" xmlns:xlink=\"http://www.w3.org/1999/xlink\" xmlns:sketch=\"http://www.bohemiancoding.com/sketch/ns\">\n      <title>Error</title>\n      <defs></defs>\n      <g id=\"Page-1\" stroke=\"none\" stroke-width=\"1\" fill=\"none\" fill-rule=\"evenodd\" sketch:type=\"MSPage\">\n        <g id=\"Check-+-Oval-2\" sketch:type=\"MSLayerGroup\" stroke=\"#747474\" stroke-opacity=\"0.198794158\" fill=\"#FFFFFF\" fill-opacity=\"0.816519475\">\n          <path d=\"M32.6568542,29 L38.3106978,23.3461564 C39.8771021,21.7797521 39.8758057,19.2483887 38.3137085,17.6862915 C36.7547899,16.1273729 34.2176035,16.1255422 32.6538436,17.6893022 L27,23.3431458 L21.3461564,17.6893022 C19.7823965,16.1255422 17.2452101,16.1273729 15.6862915,17.6862915 C14.1241943,19.2483887 14.1228979,21.7797521 15.6893022,23.3461564 L21.3431458,29 L15.6893022,34.6538436 C14.1228979,36.2202479 14.1241943,38.7516113 15.6862915,40.3137085 C17.2452101,41.8726271 19.7823965,41.8744578 21.3461564,40.3106978 L27,34.6568542 L32.6538436,40.3106978 C34.2176035,41.8744578 36.7547899,41.8726271 38.3137085,40.3137085 C39.8758057,38.7516113 39.8771021,36.2202479 38.3106978,34.6538436 L32.6568542,29 Z M27,53 C41.3594035,53 53,41.3594035 53,27 C53,12.6405965 41.3594035,1 27,1 C12.6405965,1 1,12.6405965 1,27 C1,41.3594035 12.6405965,53 27,53 Z\" id=\"Oval-2\" sketch:type=\"MSShapeGroup\"></path>\n        </g>\n      </g>\n    </svg>\n   </div><input type=\"text\" class=\"form-control input-sm\" style=\"max-width:120px\" placeholder=\"názov\" name=\"document_name["+locale+"][]\">\n</div>",
    success: function (file, response) {
      $('form').append('<input type="hidden" name="document['+locale+'][]" value="' + response.name + '">')
      uploadedDocumentMap[file.name] = response.name
    },
    removedfile: function (file) {
      file.previewElement.remove()
      var name = ''
      if (typeof file.file_name !== 'undefined') {
        name = file.file_name
      } else {
        name = uploadedDocumentMap[file.name]
      }
      $('form').find('input[name="document['+locale+'][]"][value="' + name + '"]').remove()
    },
    init: function () {
      @if(isset($authority) && $authority->getMedia())
        var files = {!! json_encode($media_files) !!};

        for (var i in files[locale]) {
          var file = files[locale][i]
          this.options.addedfile.call(this, file)
          this.options.thumbnail.call(this, file, file.path);
          file.previewElement.classList.add('dz-complete')
          $('form').append('<input type="hidden" name="document['+locale+'][]" value="' + file.file_name + '">')
          file.previewElement.querySelector('input.form-control').value = file.name
        }
      @endif
    }
  });

  });
</script>

<script>
$(document).ready(function(){
  $(".cropit-image-zoom-input").slider({
    tooltip: 'hide'
  });

  $('#image-editor').cropit({
    imageBackground: true,
    imageBackgroundBorderWidth: 20
    @if (isset($authority) && $authority->has_image && file_exists($authority->getImagePath(true)) )
      ,imageState: {
        src: '{!! $authority->getImagePath() !!}'
    }
    @endif
  });

  $('.select-image-btn').click(function() {
    $('.cropit-image-input').click();
  });

  $('form').submit(function(e) {
    var imageData = $('#image-editor').cropit('export', {
      type: 'image/jpeg',
      quality: .9
    });
    $('#primary_image').val(imageData);
      return true;
  });

  $("#tags").selectize({
    plugins: ['remove_button'],
    persist: false,
    create: true,
    createOnBlur: true
  });

});

</script>
@stop