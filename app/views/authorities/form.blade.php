@extends('layouts.admin')

@section('content')

@if(isset($authority))
    {{ Form::model($authority, ['route' => ['authority.update', $authority->id], 'method' => 'patch', 'files'=>true]) }}
@else
    {{ Form::open(['route' => 'authority.store', 'files'=>true]) }}
@endif

	<div class="col-md-12 top-space">
		@if (Session::has('message'))
		    <div class="alert alert-info alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>{{ Session::get('message') }}</div>
		@endif


		@if($errors->any())
			<div class="alert alert-danger">
				<a href="#" class="close" data-dismiss="alert">&times;</a>
				{{ implode('', $errors->all('<li class="error">:message</li>')) }}
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
					{{ Form::label('id', 'Id') }}
				    {{ Form::text('id', $new_id, array('class' => 'form-control', 'readonly')) }}
					</div>
				</div>
				@endif
				<div class="col-md-12">
					<div class="form-group">
					{{ Form::label('name', 'celé meno (Priezvisko, Meno)') }}
					{{ Form::text('name', Input::old('name'), array('class' => 'form-control')) }}
					</div>
				</div>
				<div class="col-md-12">
					<div class="form-group">
					{{ Form::label('biography', 'biografia') }}
					{{ Form::textarea('biography', Input::old('biography'), array('class' => 'form-control wysiwyg')) }}	
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
        					{{ Form::label('url', 'URL') }}
        					{{ Form::text('links['.$i.'][url]', $link->url, array('class' => 'form-control form_link', 'placeholder' => 'http://')) }}
        					{{ Form::hidden('links['.$i.'][id]', $link->id) }}
        					</div>
        				</div>
        				<div class="col-md-5">
        					<div class="form-group">
        					{{ Form::label('label', 'Zobrazený text') }}
        					{{ Form::text('links['.$i.'][label]', $link->label, array('class' => 'form-control', 'placeholder' => 'wikipédia')) }}
        					</div>
        				</div>
        				<div class="col-md-2 text-right">
        					<div class="form-group">
        					{{ Form::label('', '&nbsp;', ['class'=>'force-block']) }}
        					<a href="{{ URL::to('authority/destroyLink', array('link_id'=>$link->id ))  }}"  class="btn btn-danger btn-outline"><i class="fa-times fa"></i> zmazať</a>
        					</div>
        				</div>
                    </div>
	        	@endforeach
	        @endif
            <div class="row" id="external_links">
				<div class="col-md-5" id="urls">
					<div class="form-group">
					{{ Form::label('url', 'URL') }}
					{{ Form::text('links['.$link_counter.'][url]', Input::old('links['.$link_counter.'][url]'), array('class' => 'form-control form_link', 'placeholder' => 'http://')) }}
					</div>
				</div>
				<div class="col-md-5"  id="labels">
					<div class="form-group">
					{{ Form::label('label', 'Zobrazená adresa') }}
					{{ Form::text('links['.$link_counter.'][label]', Input::old('links['.$link_counter.'][label]'), array('class' => 'form-control', 'placeholder' => 'wikipédia')) }}
					</div>
				</div>
				<div class="col-md-2 text-right">
					<div class="form-group">
					{{ Form::label('', '&nbsp;', ['class'=>'force-block']) }}
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
					{{ Form::label('image_source_url', 'Zdroj obrázku (URL)') }}
					{{ Form::text('image_source_url', Input::old('image_source_url'), array('class' => 'form-control form_link', 'placeholder' => 'http://')) }}
					</div>
				</div>
				<div class="col-md-5">
					<div class="form-group">
					{{ Form::label('image_source_label', 'Zdroj obrázku (Zobrazený text)') }}
					{{ Form::text('image_source_label', Input::old('image_source_label'), array('class' => 'form-control', 'placeholder' => 'wikipédia')) }}
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
						{{ Form::hidden('primary_image', null, ['id' => 'primary_image']) }}
						
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
	{{ Form::submit('Uložiť', array('class' => 'btn btn-default')) }} &nbsp; 
	{{ link_to_route('authority.index', 'Zrušiť', null, array('class' => 'btn btn-default')) }}
	{{Form::close() }}
</div>

<div class="clear">&nbsp;</div>
@stop

@section('script')

{{ HTML::script('js/bootstrap-slider.min.js') }}
{{ HTML::script('js/jquery.cropit.min.js') }}

<script>
$(document).ready(function(){
	$(".cropit-image-zoom-input").slider({
	    tooltip: 'hide'
	});

	$('#image-editor').cropit({
	  imageBackground: true,
	  imageBackgroundBorderWidth: 20
	  @if (isset($authority) && $authority->has_image)
		  ,imageState: {
		    src: '{{ $authority->getImagePath() }}'
	  }
	  @endif
	});

	$('.select-image-btn').click(function() {
	  $('.cropit-image-input').click();
	});

	$('form').submit(function(e) {
      var self = this;
      e.preventDefault();
	  var imageData = $('#image-editor').cropit('export', {
		  type: 'image/jpeg',
		  quality: .9
		});
	  $('#primary_image').val(imageData);
	  self.submit();
	});



});

</script>
@stop