@extends('layouts.admin')

@section('content')

@if(isset($slide))
    {!! Form::model($slide, ['route' => ['slide.update', $slide->id], 'method' => 'patch', 'files'=>true]) !!}
@else
    {!! Form::open(['route' => 'slide.store', 'files'=>true]) !!}
@endif

<div class="col-md-12">

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

<div class="col-md-12">
	<div class="form-group">
	{!! Form::label('title', 'Nadpis') !!}
	{!! Form::textarea('title', Input::old('title'), array('class' => 'form-control', 'rows' => '2')) !!}
	</div>
</div>

<div class="col-md-12">
	<div class="form-group">
	{!! Form::label('subtitle', 'Podnadpis') !!}
	{!! Form::textarea('subtitle', Input::old('subtitle'), array('class' => 'form-control', 'rows' => '2')) !!}
	</div>
</div>

<div class="col-md-12">
	<div class="form-group">
	{!! Form::label('url', 'URL') !!}
	{!! Form::text('url', Input::old('url'), array('class' => 'form-control', 'placeholder' => 'http://')) !!}
	</div>
</div>

<div class="col-md-8">
	<div class="form-group">
		{!! Form::label('image', 'Obrázok') !!}
		{!! Form::file('image') !!}
		<p>min. šírka 1200px</p>
	</div>
</div>

<div class="col-md-4">
	<div class="form-group">
	{!! Form::label('publish', 'Publikovaný') !!}<br>
	{!! Form::hidden('publish', 0) !!}
	{!! Form::checkbox('publish', '1', Input::old('publish'), ['class'=>'checkbox']) !!}
	</div>
</div>


<div class="col-md-12 text-center">
	{!! Form::submit('Uložiť', array('class' => 'btn btn-default')) !!} &nbsp;
	{!! link_to_route('slide.index', 'Zrušiť', null, array('class' => 'btn btn-default')) !!}
</div>

{!!Form::close() !!}

<div class="clearfix"></div>


@stop