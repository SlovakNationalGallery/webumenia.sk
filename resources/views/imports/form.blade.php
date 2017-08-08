@extends('layouts.admin')

@section('content')

<div class="col-md-12">
	@if(isset($import))
	    {!! Form::model($import, ['route' => ['imports.update', $import->id], 'method' => 'patch', 'files'=>true]) !!}
	@else
	    {!! Form::open(['route' => 'imports.store', 'files'=>true]) !!}
	@endif

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
{{-- 
<div class="col-md-12">
	<div class="form-group">
	{!! Form::label('type', 'Typ') !!}
	{!! Form::select('type', App\SpiceHarvesterHarvest::$types, Input::old('type'), array('class' => 'form-control')) !!}
	</div>
</div>
--}}
<div class="col-md-12">
	<div class="form-group">
	{!! Form::label('name', 'Názov') !!}
	{!! Form::text('name', Input::old('name'), array('class' => 'form-control', 'placeholder' => '')) !!}
	</div>
</div>

<div class="col-md-12">
	<div class="form-group">
	{!! Form::label('dir_path', 'Priečinok (nepovinné)') !!}
	<div class="input-group">
		<div class="input-group-addon">/storage/import/</div>
		{!! Form::text('dir_path', Input::old('dir_path'), array('class' => 'form-control', 'placeholder' => '')) !!}
	</div>
	</div>
</div>

<div class="col-md-12">
	<div class="form-group">
	{!! Form::label('file', 'CSV súbor') !!}
	{!! Form::file('file') !!}
	</div>
</div>


{{-- 
<div class="col-md-12">
	<div class="form-group">
	{!! Form::label('collection_id', 'Kolekcia') !!}
	{!! Form::select('collection_id', [null=>'žiadna'] + App\Collection::lists('name','id')->toArray(), Input::old('collection_id'), array('class' => 'form-control')) !!}
	</div>
</div>
 --}}
<div class="col-md-12 text-center">
	{!! Form::submit('Uložiť', array('class' => 'btn btn-default')) !!} &nbsp; 
	{!! link_to_route('imports.index', 'Zrušiť', null, array('class' => 'btn btn-default')) !!}
	{!!Form::close() !!}
</div>

@stop