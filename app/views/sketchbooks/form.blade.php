@extends('layouts.admin')

@section('content')

<div class="col-md-12">
	@if(isset($sketchbook))
	    {{ Form::model($sketchbook, ['route' => ['sketchbook.update', $sketchbook->id], 'method' => 'patch']) }}
	@else
	    {{ Form::open(['route' => 'sketchbook.store']) }}
	@endif

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
<div class="col-md-12">
	<div class="form-group">
	{{ Form::label('item_id', 'ID') }}
	{{ Form::text('item_id', Input::old('item_id'), array('class' => 'form-control', 'autocomplete' => 'off')) }}
	</div>
</div>

@if(isset($sketchbook))
<hr>
<div class="col-md-12">
	<div class="form-group">
	{{ Form::label('title', 'Zobrazený názov') }}
	{{ Form::text('title', Input::old('title'), array('class' => 'form-control')) }}
	</div>
</div>
<div class="col-md-5">
	<div class="form-group">
		{{ Form::label('height', 'Výška') }}
		<div class="input-group">
			{{ Form::text('height', Input::old('height'), array('class' => 'form-control')) }}
			<div class="input-group-addon">cm</div>
		</div>
	</div>
</div>

<div class="col-md-5">
	<div class="form-group">
		{{ Form::label('width', 'Šírka') }}
		<div class="input-group">
			{{ Form::text('width', Input::old('width'), array('class' => 'form-control')) }}
			<div class="input-group-addon">cm</div>
		</div>
	</div>
</div>

<div class="col-md-2">
	<div class="form-group">
	{{ Form::label('publish', 'Publikovaný') }}<br>
	{{ Form::hidden('publish', 0) }}
	{{ Form::checkbox('publish', '1', Input::old('publish'), ['class'=>'checkbox']) }}
	</div>
</div>

@endif

<div class="col-md-12 text-center">
	{{ Form::submit('Uložiť', array('class' => 'btn btn-default')) }} &nbsp; 
	{{ link_to_route('sketchbook.index', 'Zrušiť', null, array('class' => 'btn btn-default')) }}
	{{Form::close() }}
</div>



<div class="clearfix"></div>


@stop