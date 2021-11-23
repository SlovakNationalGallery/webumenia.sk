@extends('layouts.admin')

@section('title')
presmerovanie |
@parent
@stop

@section('content')

@if(isset($redirect))
    {!! Form::model($redirect, ['route' => ['redirects.update', $redirect->id], 'method' => 'patch']) !!}
@else
    {!! Form::open(['route' => 'redirects.store']) !!}
@endif

<div class="col-md-12">
    <h1 class="page-header">Presmerovanie</h1>

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
	{!! Form::label('source_url', 'Zdrojová URL') !!}
	{!! Form::text('source_url', Request::old('source_url'), array('class' => 'form-control')) !!}
	</div>
</div>

<div class="col-md-12">
	<div class="form-group">
	{!! Form::label('target_url', 'Cieľová URL') !!}
	{!! Form::text('target_url', Request::old('target_url'), array('class' => 'form-control')) !!}
	</div>
</div>

<div class="col-md-4">
	<div class="form-group">
	{!! Form::label('is_enabled', 'Aktívne') !!}<br>
	{!! Form::hidden('is_enabled', 0) !!}
	{!! Form::checkbox('is_enabled', '1', Request::old('is_enabled'), ['class'=>'checkbox']) !!}
	</div>
</div>

<div class="col-md-12 text-center">
	{!! Form::submit('Uložiť', array('class' => 'btn btn-default')) !!} &nbsp;
	{!! link_to_route('redirects.index', 'Zrušiť', null, array('class' => 'btn btn-default')) !!}
</div>

{!! Form::close() !!}

<div class="clearfix"></div>

<hr>
<div class="text-center small">
	URL vpisovať v relatívnom tvare + je možné používať paramtre
    (<a class="small" href="https://github.com/spatie/laravel-missing-page-redirector#usage" target="_blank">dokumentácia</a>)<br>
    napr.
    <code>/retart</code> <i class="fa fa-arrow-right"></i> <code>/katalog?tag=retart</code>
    alebo
    <code>/old-blog/{slug}</code> <i class="fa fa-arrow-right"></i> <code>/new-blog/{slug}</code>
</div>


@stop
