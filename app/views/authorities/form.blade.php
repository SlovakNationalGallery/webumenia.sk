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
        					{{ Form::label('label', 'Zobrazená adresa') }}
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


<div class="col-md-12">
	@if(isset($authority))
	<div class="primary-image">
		aktuálny:<br>
		<img src="{{ $authority->getImagePath() }}" alt="">
	</div>
	@endif
	<div class="form-group">
	{{ Form::label('primary_image', 'obrázok') }}
	{{ Form::file('primary_image') }}
	</div>
</div>

<div class="col-md-12 text-center">
	{{ Form::submit('Uložiť', array('class' => 'btn btn-default')) }} &nbsp; 
	{{ link_to_route('authority.index', 'Zrušiť', null, array('class' => 'btn btn-default')) }}
	{{Form::close() }}
</div>

<div class="clear">&nbsp;</div>
@stop