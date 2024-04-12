@extends('layouts.admin')

@section('content')

@if(isset($user))
    {!! Form::model($user, ['route' => ['user.update', $user->id], 'method' => 'patch']) !!}
@else
    {!! Form::open(['route' => 'user.store']) !!}
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
		{{ Form::label('frontend', 'Frontend') }}
		<div>
			@foreach(\App\Enums\FrontendEnum::cases() as $frontend)
				<label class="checkbox-inline">
					{{ Form::radio('frontend', $frontend->value, old('frontend'), ['class' => 'form-control']) }} {{ $frontend }}
				</label>
			@endforeach
		</div>
	</div>
</div>
<div class="col-md-12">
	<div class="form-group">
	{!! Form::label('username', 'Login') !!}
	{!! Form::text('username', Request::old('username'), array('class' => 'form-control', 'autocomplete' => 'off')) !!}
	</div>
</div>
<div class="col-md-12">
	<div class="form-group">
	{!! Form::label('password', 'Heslo') !!}
	{!! Form::password('password', array('class' => 'form-control', 'autocomplete' => 'off')) !!}
	</div>
</div>
<hr>
<div class="col-md-12">
	<div class="form-group">
	{!! Form::label('name', 'Meno') !!}
	{!! Form::text('name', Request::old('name'), array('class' => 'form-control')) !!}
	</div>
</div>
<div class="col-md-12">
	<div class="form-group">
	{!! Form::label('email', 'Email') !!}
	{!! Form::text('email', Request::old('email'), array('class' => 'form-control')) !!}
	</div>
</div>
<div class="col-md-12">
	<fieldset>
		<legend>Práva</legend>
		<div class="form-group">
			<label class="checkbox-inline">
				{{ Form::hidden('can_administer', 0) }}
				{{ Form::checkbox('can_administer', 1, old('can_administer'), ['class' => 'form-control']) }}
				{{ __('permissions.can_administer') }}
			</label>
		</div>
		<div class="form-group">
			<label class="checkbox-inline">
				{{ Form::hidden('can_edit', 0) }}
				{{ Form::checkbox('can_edit', 1, old('can_edit'), ['class' => 'form-control']) }}
				{{ __('permissions.can_edit') }}
			</label>
		</div>
		<div class="form-group">
			<label class="checkbox-inline">
				{{ Form::hidden('can_publish', 0) }}
				{{ Form::checkbox('can_publish', 1, old('can_publish'), ['class' => 'form-control']) }}
				{{ __('permissions.can_publish') }}
			</label>
		</div>
		<div class="form-group">
			<label class="checkbox-inline">
				{{ Form::checkbox('can_import', 0) }}
				{{ Form::checkbox('can_import', 1, old('can_import'), ['class' => 'form-control']) }}
				{{ __('permissions.can_import') }}
			</label>
		</div>
	</fieldset>
</div>
<div class="col-md-12 text-center">
	{!! Form::submit('Uložiť', array('class' => 'btn btn-default')) !!} &nbsp;
	{!! link_to_route('user.index', 'Zrušiť', null, array('class' => 'btn btn-default')) !!}
</div>

{!!Form::close() !!}

<div class="clearfix"></div>


@stop
