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
@if(isset($collection))
<div class="col-md-12">
	<div class="form-group">
	{{ Form::label('order', 'Poradie') }}
	{{ Form::text('order', Input::old('order'), array('class' => 'form-control')) }}
	</div>
</div>
@endif
<div class="col-md-12 text-center">
	{{ Form::submit('Uložiť', array('class' => 'btn btn-default')) }} &nbsp; 
	{{ link_to_route('collection.index', 'Zrušiť', null, array('class' => 'btn btn-default')) }}
	{{Form::close() }}
</div>

@stop