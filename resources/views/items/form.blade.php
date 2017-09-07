@extends('layouts.admin')

@section('content')

<div class="col-md-12">
	@if(isset($item))
		{!! Form::model($item, ['route' => ['item.update', $item->id], 'method' => 'patch', 'files'=>true]) !!}
	@else
		{!! Form::open(['route' => 'item.store', 'files'=>true]) !!}
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
@if(isset($new_id))    	
<div class="col-md-12">
	<div class="form-group">
	{!! Form::label('id', 'Id') !!}
	{!! Form::text('id', $new_id, array('class' => 'form-control', 'readonly')) !!}
	</div>
</div>
@endif

<!-- translatable -->
<div class="col-md-12">

	<!-- Nav tabs -->
	<ul class="nav nav-tabs top-space" role="tablist">
		@foreach (\Config::get('translatable.locales') as $i=>$locale)
			<li role="presentation" class="{{ ($i==0) ? 'active' : '' }}"><a href="#{{ $locale }}" aria-controls="{{ $locale }}" role="tab" data-toggle="tab">{{ strtoupper($locale) }}</a></li>
		@endforeach
	</ul>

	<div class="tab-content top-space">
		@foreach (\Config::get('translatable.locales') as $i=>$locale)
			<div role="tabpanel" class="tab-pane  {{ ($i==0) ? 'active' : '' }}" id="{{ $locale }}">
				
				<div class="form-group">
				{{ Form::label($locale."[title]", 'Názov '.strtoupper($locale)) }}
				{{ Form::text($locale."[title]", isset($item) ? @$item->translate($locale)->title : '', array('class' => 'form-control')) }}
				</div>

				<div class="form-group">
				{{ Form::label($locale."[description]", 'Popis '.strtoupper($locale)) }}
				{{ Form::textarea($locale."[description]", isset($item) ? @$item->translate($locale)->description : '', array('class' => 'form-control wysiwyg')) }}	
				</div>
							
				<div class="form-group">
				{{ Form::label($locale."[work_type]", 'Výtvarný druh '.strtoupper($locale)) }}
				{{ Form::text($locale."[work_type]", isset($item) ? @$item->translate($locale)->work_type : '', array('class' => 'form-control')) }}
				</div>
							
				<div class="form-group">
				{{ Form::label($locale."[work_level]", 'Stupeň spracovania '.strtoupper($locale)) }}
				{{ Form::text($locale."[work_level]", isset($item) ? @$item->translate($locale)->work_level : '', array('class' => 'form-control')) }}
				</div>

				<div class="form-group">
				{{ Form::label($locale."[topic]", 'Žáner '.strtoupper($locale)) }}
				{{ Form::text($locale."[topic]", isset($item) ? @$item->translate($locale)->topic : '', array('class' => 'form-control')) }}
				</div>
				
				<div class="form-group">
				{{ Form::label($locale."[measurement]", 'Miery '.strtoupper($locale)) }}
				{{ Form::text($locale."[measurement]", isset($item) ? @$item->translate($locale)->measurement : '', array('class' => 'form-control')) }}
				</div>
				
				<div class="form-group">
				{{ Form::label($locale."[dating]", 'Datovanie '.strtoupper($locale)) }}
				{{ Form::text($locale."[dating]", isset($item) ? @$item->translate($locale)->dating : '', array('class' => 'form-control')) }}
				</div>
				
				<div class="form-group">
				{{ Form::label($locale."[medium]", 'Materiál '.strtoupper($locale)) }}
				{{ Form::text($locale."[medium]", isset($item) ? @$item->translate($locale)->medium : '', array('class' => 'form-control')) }}
				</div>
				
				<div class="form-group">
				{{ Form::label($locale."[technique]", 'Technika '.strtoupper($locale)) }}
				{{ Form::text($locale."[technique]", isset($item) ? @$item->translate($locale)->technique : '', array('class' => 'form-control')) }}
				</div>
				
				<div class="form-group">
				{{ Form::label($locale."[inscription]", 'Značenie '.strtoupper($locale)) }}
				{{ Form::text($locale."[inscription]", isset($item) ? @$item->translate($locale)->inscription : '', array('class' => 'form-control')) }}
				</div>
				
				<div class="form-group">
				{{ Form::label($locale."[place]", 'Geografická oblasť '.strtoupper($locale)) }}
				{{ Form::text($locale."[place]", isset($item) ? @$item->translate($locale)->place : '', array('class' => 'form-control')) }}
				</div>
				
				<div class="form-group">
				{{ Form::label($locale."[state_edition]", 'Stupeň spracovania '.strtoupper($locale)) }}
				{{ Form::text($locale."[state_edition]", isset($item) ? @$item->translate($locale)->state_edition : '', array('class' => 'form-control')) }}
				</div>
				
				<div class="form-group">
				{{ Form::label($locale."[gallery]", 'Galéria '.strtoupper($locale)) }}
				{{ Form::text($locale."[gallery]", isset($item) ? @$item->translate($locale)->gallery : '', array('class' => 'form-control')) }}
				</div>

			</div>
		@endforeach
	</div>

</div>
<!-- /translatable -->

<div class="col-md-12">
	<div class="form-group">
	{!! Form::label('identifier', 'inventárne číslo') !!}
	{!! Form::text('identifier', Input::old('identifier'), array('class' => 'form-control')) !!}
	</div>
</div>
<div class="col-md-12">
	<div class="form-group">
	{!! Form::label('author', 'autor') !!}
	{!! Form::text('author', Input::old('author'), array('class' => 'form-control')) !!}
	</div>
</div>
<div class="col-md-4">
	<div class="form-group">
	{!! Form::label('description_user_id', 'popis - autor') !!}
	{!! Form::select('description_user_id', App\User::lists('name','id'), Input::old('description_user_id', (isSet($item)) ? $item->description_user_id : Auth::user()->id), array('class' => 'form-control')) !!}
	</div>
</div>
<div class="col-md-4">
	<div class="form-group">
	{!! Form::label('description_source', 'popis - zdroj') !!}
	{!! Form::text('description_source', Input::old('description_source'), array('class' => 'form-control')) !!}
	</div>
</div>
<div class="col-md-4">
	<div class="form-group">
	{!! Form::label('description_source_link', 'popis - link na zdroj') !!}
	{!! Form::text('description_source_link', Input::old('description_source_link'), array('class' => 'form-control', 'placeholder' => 'http://')) !!}
	</div>
</div>
<div class="col-md-12">
	<div class="form-group">
	{!! Form::label('tags', 'tagy') !!}
	{!! Form::select('tags[]', App\Item::existingTags()->lists('name','name'), (isSet($item)) ? $item->tagNames() : [], ['id' => 'tags', 'multiple' => 'multiple']) !!}

	</div>
</div>
<div class="col-md-12">
	<div class="form-group">
	{!! Form::label('date_earliest', 'datovanie najskôr') !!}
	{!! Form::text('date_earliest', Input::old('date_earliest'), array('class' => 'form-control')) !!}
	</div>
</div>
<div class="col-md-12">
	<div class="form-group">
	{!! Form::label('date_latest', 'datovanie najneskôr') !!}
	{!! Form::text('date_latest', Input::old('date_latest'), array('class' => 'form-control')) !!}
	</div>
</div>
<div class="col-md-6">
	<div class="form-group">
	{!! Form::label('lat', 'latitúda') !!}
	{!! Form::text('lat', Input::old('lat'), array('class' => 'form-control')) !!}
	</div>
</div>
<div class="col-md-6">
	<div class="form-group">
	{!! Form::label('lng', 'longitúda') !!}
	{!! Form::text('lng', Input::old('lng'), array('class' => 'form-control')) !!}
	</div>
</div>
<div class="col-md-12">
	<div class="form-group">
	{!! Form::label('state_edition', 'stupeň spracovania') !!}
	{!! Form::text('state_edition', Input::old('state_edition'), array('class' => 'form-control')) !!}
	</div>
</div>

<div class="clearfix"></div>

<div class="col-md-5">
	<div class="form-group">
	{{ Form::label('relationship_type', 'typ integrity') }}
	{{ Form::text('relationship_type', Input::old('relationship_type'), array('class' => 'form-control', 'placeholder' => 'zo súboru / z cyklu / z albumu / ...')) }}
	</div>
</div>
<div class="col-md-5">
	<div class="form-group">
	{{ Form::label('related_work', 'názov integrity') }}
	{{ Form::text('related_work', Input::old('related_work'), array('class' => 'form-control', 'placeholder' => 'Ecce vita')) }}
	</div>
</div>
<div class="col-md-1">
	<div class="form-group">
	{{ Form::label('related_work_order', 'poradie') }}
	{{ Form::text('related_work_order', Input::old('related_work_order'), array('class' => 'form-control')) }}
	</div>
</div>
<div class="col-md-1">
	<div class="form-group">
	{{ Form::label('related_work_total', 'z počtu') }}
	{{ Form::text('related_work_total', Input::old('related_work_total'), array('class' => 'form-control')) }}
	</div>
</div>

<div class="col-md-12">
	<div class="form-group">
	{!! Form::label('iipimg_url', 'IIPImage url') !!}
	{!! Form::text('iipimg_url', Input::old('iipimg_url'), array('class' => 'form-control')) !!}
	</div>
</div>
<div class="col-md-12">
	@if(isset($item))
	<div class="primary-image">
		aktuálny:<br>
		<img src="{!! $item->getImagePath() !!}" alt="">
	</div>
	@endif
	<div class="form-group">
	{!! Form::label('primary_image', 'obrázok') !!}
	{!! Form::file('primary_image') !!}
	</div>
</div>

<div class="col-md-12 text-center">
	{!! Form::submit('Uložiť', array('class' => 'btn btn-default')) !!} &nbsp; 
	@if(isset($item) && $item->record)
		<a href="{!!URL::to('harvests/'.$item->record->id.'/refreshRecord')!!}" class="btn btn-warning">Obnoviť z OAI</a>
		&nbsp; 
	@endif
	{!! link_to_route('item.index', 'Zrušiť', null, array('class' => 'btn btn-default')) !!}
	{!!Form::close() !!}
</div>

<div class="clear">&nbsp;</div>
@stop

@section('script')

{!! Html::script('js/selectize.min.js') !!}

<script>
$(document).ready(function(){
	
	$("#tags").selectize({
		plugins: ['remove_button'],
		persist: false,
		create: true,
		createOnBlur: true
	});

});

</script>
@stop
