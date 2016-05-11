@extends('layouts.admin')

@section('content')

<div class="col-md-12">
	@if(isset($user))
	    {{ Form::model($user, ['route' => ['user.update', $user->id], 'method' => 'patch']) }}
	@else
	    {{ Form::open(['route' => 'user.store']) }}
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
	{{ Form::label('roles', 'Skupina') }}
	@if (isSet($user))
		{{ Form::select('roles', $roles, $user->roles->lists('id'), array('class' => 'select', 'multiple' => true)) }}
	@else
		{{ Form::select('roles', $roles, [], array('class' => 'select', 'multiple' => true)) }}
	@endif
	</div>
</div>
<div class="col-md-12">
	<div class="form-group">
	{{ Form::label('username', 'Login') }}
	{{ Form::text('username', Input::old('username'), array('class' => 'form-control', 'autocomplete' => 'off')) }}
	</div>
</div>
<div class="col-md-12">
	<div class="form-group">
	{{ Form::label('password', 'Heslo') }}
	{{ Form::password('password', array('class' => 'form-control', 'autocomplete' => 'off')) }}
	</div>
</div>
<hr>
<div class="col-md-12">
	<div class="form-group">
	{{ Form::label('name', 'Meno') }}
	{{ Form::text('name', Input::old('name'), array('class' => 'form-control')) }}
	</div>
</div>
<div class="col-md-12">
	<div class="form-group">
	{{ Form::label('email', 'Email') }}
	{{ Form::text('email', Input::old('email'), array('class' => 'form-control')) }}
	</div>
</div>

<div class="col-md-12 text-center">
	{{ Form::submit('Uložiť', array('class' => 'btn btn-default')) }} &nbsp; 
	{{ link_to_route('user.index', 'Zrušiť', null, array('class' => 'btn btn-default')) }}
	{{Form::close() }}
</div>



<div class="clearfix"></div>


@stop

@section('script')

{{ HTML::script('js/selectize.min.js') }}

<script>
$(document).ready(function(){
	
    $("#roles").selectize({
        plugins: ['remove_button'],
        maxItems: 1,
        mode: 'multi'
    });

});

</script>
@stop