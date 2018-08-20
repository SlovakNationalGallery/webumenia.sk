@extends('layouts.admin')

@section('content')

<div class="col-md-12 top-space">
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

<div class="col-md-6">
	<div class="panel panel-default">
        <div class="panel-heading">
            <i class="fa fa-upload fa-fw"></i> CSV súbor pre import
        </div>
        <div class="panel-body">
        	<div class="form-group">
        	{!! Form::label('file', 'Upload CSV súbor') !!}
        	{!! Form::file('file') !!}
        	<p class="help-block">
        		Vyplniť len ak chcete nahrať nový/upravený súbor pre import.<br>
        		Ak ma rovnaký názov, automaticky sa starý prepíše.
        	</p>
        	@if (!empty($options))
        		<p class="help-block">
        			@foreach ($options as $option=>$value)

        				{{ $option }}: <code>{{ ($option == 'newline') ? js_add_slashes($value) : $value }}</code><br>
        			@endforeach

        			@if (empty($options['input_encoding']))
        				input_encoding: <code>utf-8</code>
        			@endif
        		</p>
        	@endif
        	</div>

        	@if ( isset($import) && !empty($import->files))
        	<div class="form-group top-space">
        	{!! Form::label('files', 'Aktuálne CSV súbory:') !!}
        	<table class="table">
        		<tr>
        			<th>názov súboru</th>
        			<th>dátum nahratia</th>
        			<th class="text-right">velkosť</th>
        		</tr>
        		@foreach ($import->files as $file)
	        		<tr>
	        			<td>{{ $file['basename'] }}</td>
	        			<td>{{ \Carbon\Carbon::createFromTimestamp($file['timestamp'])->toDateTimeString() }}</td>
	        			<td class="text-right">{{ formatBytes($file['size']) }}</td>
	        		</tr>
        		@endforeach
        	</table>
        	</div>
        	@endif

        </div>
    </div>
</div>

<div class="col-md-6">
	<div class="panel panel-default">
        <div class="panel-heading">
            <i class="fa fa-cog fa-fw"></i> nastavenia importera
        </div>
        <div class="panel-body">
			<div class="form-group">
			{!! Form::label('name', 'Názov') !!}
			{!! Form::text('name', Input::old('name'), array('class' => 'form-control', 'placeholder' => '')) !!}
			</div>

			<div class="form-group">
			{!! Form::label('class_name', 'Trieda') !!}
			{!! Form::text('class_name', Input::old('class_name'), array('class' => 'form-control', 'placeholder' => '')) !!}
			</div>

			<div class="form-group">
			{!! Form::label('dir_path', 'Priečinok') !!}
			<div class="input-group">
				<div class="input-group-addon">/storage/app/import/</div>
				{!! Form::text('dir_path', Input::old('dir_path'), array('class' => 'form-control', 'placeholder' => '')) !!}
			</div>
			</div>

			<div class="form-group">
			{!! Form::label('iip_dir_path', 'IIP Priečinok (nepovinné)') !!}
			{!! Form::text('iip_dir_path', Input::old('iip_dir_path'), array('class' => 'form-control', 'placeholder' => '/mnt/DG_PUBLIC_IS/MGHQ')) !!}
			</div>
        </div>
    </div>
</div>


<div class="col-md-12 text-center">
	{!! Form::submit('Uložiť', array('class' => 'btn btn-primary')) !!} &nbsp;
	{!! link_to_route('imports.index', 'Zrušiť', null, array('class' => 'btn btn-default')) !!}
	{!!Form::close() !!}
</div>

@stop