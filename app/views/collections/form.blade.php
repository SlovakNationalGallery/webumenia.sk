@extends('layouts.admin')

@section('content')

<div class="col-md-12">
	@if(isset($collection))
	    {{ Form::model($collection, ['route' => ['collection.update', $collection->id], 'method' => 'patch']) }}
	@else
	    {{ Form::open(['route' => 'collection.store']) }}
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
	{{ Form::label('type', 'Typ') }}
	{{ Form::select('type', ['sekcia'=>'sekcia'], Input::old('type'), array('class' => 'form-control')) }}
	</div>
</div>
<div class="col-md-12">
	<div class="form-group">
	{{ Form::label('name', 'Názov') }}
	{{ Form::text('name', Input::old('name'), array('class' => 'form-control')) }}
	</div>
</div>
<div class="col-md-12">
	<div class="form-group">
	{{ Form::label('text', 'Text') }}
	{{ Form::textarea('text', Input::old('text'), array('class' => 'form-control wysiwyg')) }}	
	</div>
</div>
<div class="col-md-6">
	<div class="form-group">
		{{ Form::label('title_color', 'Farba nadpisu') }}
		<div class="input-group colorpicker-component">
		    {{ Form::text('title_color', Input::old('title_color'), array('class' => 'form-control', 'placeholder' => '#ffffff')) }}
		    <span class="input-group-addon"><i></i></span>
		</div>
	</div>
</div>
<div class="col-md-6">
	<div class="form-group">
		{{ Form::label('color', 'Tieň pod nadpisom') }}
		<div class="input-group colorpicker-component">
		    {{ Form::text('title_shadow', Input::old('title_shadow'), array('class' => 'form-control', 'placeholder' => '#666666')) }}
		    <span class="input-group-addon"><i></i></span>
		</div>
	</div>
</div>
<div class="col-md-12">
	<div class="form-group">
		{{ Form::label('main_image', 'Obrázok') }}
		{{ Form::file('main_image') }}
		<p>šírka: 1400px<br>formát: JPG (vysoka kompresia ~50-60%)</p>
	</div>
</div>
<div class="col-md-12">
	<h2>Diela</h2>
	<table class="table table-striped">
		@foreach ($collection->items as $item)
		<tr>
			<td class="text-center"><img src="{{ $item->getImagePath(); }}" alt="náhľad" class="img-responsive nahlad" ></td>
			<td>{{ $item->id }}</td>
			<td class="text-left">
				<a href="{{ URL::to('item/' . $item->id . '/edit' ) }}">{{ $item->author }} - {{ $item->title }}</a>
			</td>
			<td class="text-left">
				<a href="#"><i class="fa fa-arrow-up"></i></a>
				<a href="#"><i class="fa fa-arrow-down"></i></a>
				<a href="#">zmazať</a>

			</td>
		</tr>
		@endforeach
	</table>
</div>
<div class="col-md-12 text-center">
	{{ Form::submit('Uložiť', array('class' => 'btn btn-default')) }} &nbsp; 
	{{ link_to_route('collection.index', 'Zrušiť', null, array('class' => 'btn btn-default')) }}
	{{Form::close() }}
</div>
<div class="clearfix"></div>


@stop