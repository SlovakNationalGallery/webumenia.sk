@extends('layouts.admin')

@section('content')

@if(isset($harvest))
    {!! Form::model($harvest, ['route' => ['harvests.update', $harvest->id], 'method' => 'patch']) !!}
@else
    {!! Form::open(['route' => 'harvests.store']) !!}
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
	{!! Form::label('type', 'Typ') !!}
	{!! Form::select('type', App\SpiceHarvesterHarvest::$types, Request::old('type'), array('class' => 'form-control')) !!}
	</div>
</div>
<div class="col-md-12">
	<div class="form-group">
	{!! Form::label('base_url', 'URL') !!}
	{!! Form::text('base_url', Request::old('base_url'), array('class' => 'form-control', 'placeholder' => 'http://')) !!}
	</div>
</div>
<div class="col-md-6">
	<div class="form-group">
	{!! Form::label('username', 'Username (ak vyžaduje autentifikáciu)') !!}
	{!! Form::text('username', Request::old('username'), array('class' => 'form-control')) !!}
	</div>
</div>
<div class="col-md-6">
	<div class="form-group">
	{!! Form::label('password', 'Heslo (ak vyžaduje autentifikáciu)') !!}
	{!! Form::text('password', Request::old('password'), array('class' => 'form-control')) !!}
	</div>
</div>
<div class="col-md-12">
	<div class="form-group">
	{!! Form::label('metadata_prefix', 'Metadata prefix') !!}
	{!! Form::text('metadata_prefix', Request::old('metadata_prefix'), array('class' => 'form-control')) !!}
	</div>
</div>
<div class="col-md-6">
	<div class="form-group">
	{!! Form::label('set_name', 'set_name') !!}
	{!! Form::text('set_name', Request::old('set_name'), array('class' => 'form-control')) !!}
	</div>
</div>
<div class="col-md-6">
	<div class="form-group">
	{!! Form::label('set_spec', 'set_spec') !!}
	{!! Form::text('set_spec', Request::old('set_spec'), array('class' => 'form-control')) !!}
	</div>
</div>
<div class="col-md-12">
	<div class="form-group">
	{!! Form::label('set_description', 'Popis setu') !!}
	{!! Form::textarea('set_description', Request::old('set_description'), array('class' => 'form-control')) !!}
	</div>
</div>
<div class="col-md-12">
	<div class="form-group">
	{!! Form::label('collection_id', 'Kolekcia') !!}
	{!! Form::select('collection_id', [null=>'žiadna'] + \App\Collection::listsTranslations('name')->pluck('name', 'id')->toArray(), Request::old('collection_id'), array('class' => 'form-control')) !!}
	</div>
</div>
<div class="col-md-12">
	<div class="form-group">
	{!! Form::label('cron_status', 'Cron Status') !!}
	{!! Form::select('cron_status', App\SpiceHarvesterHarvest::$cron_statuses, Request::old('cron_status'), array('class' => 'form-control')) !!}
	</div>
</div>

<div class="col-md-12 tw-text-center">
	{!! Form::submit('Uložiť', array('class' => 'btn btn-default')) !!} &nbsp;
	{!! link_to_route('harvests.index', 'Zrušiť', null, array('class' => 'btn btn-default')) !!}
</div>

{!! Form::close() !!}

@stop
