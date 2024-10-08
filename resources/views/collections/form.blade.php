@extends('layouts.admin')

@section('content')

@if(isset($collection))
		{!! Form::model($collection, ['route' => ['collection.update', $collection->id], 'method' => 'patch', 'files'=>true]) !!}
@else
		{!! Form::open(['route' => 'collection.store', 'files'=>true]) !!}
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
@can('administer')
<div class="col-md-4">
	<div class="form-group">
	{!! Form::label('user_id', 'autor') !!}
	{!! Form::select('user_id', App\User::pluck('name','id'), Request::old('user_id', (isSet($collection)) ? $collection->user_id : Auth::user()->id), array('class' => 'form-control')) !!}
	</div>
</div>
@endcan

<!-- translatable -->
<div class="col-md-12">

	@can('admin')
	<div class="form-group">
		{{ Form::label('frontends[]', 'Publikovať na') }}
		<div>
			@foreach(\App\Enums\FrontendEnum::cases() as $frontend)
				<label class="checkbox-inline">
					{{ Form::checkbox('frontends[]', $frontend->value, old('frontends[]'), ['class' => 'form-control']) }} {{ $frontend }}
				</label>
			@endforeach
		</div>
	</div>
	@endcan

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
				{{ Form::label($locale . "[name]", 'Názov') }}
				{{ Form::textarea($locale . "[name]", isset($collection) ? @$collection->translate($locale)->name : '', array('class' => 'form-control', 'rows' => '2')) }}
				</div>

				<div class="form-group">
					{{ Form::label($locale . "[url]", 'URL') }}
					{{ Form::text($locale . "[url]", isset($collection) ? @$collection->translate($locale)->url : '', array('class' => 'form-control')) }}
				</div>

				<div class="form-group">
				{{ Form::label($locale . "[type]", 'Typ') }}
				{{ Form::text($locale . "[type]", isset($collection) ? @$collection->translate($locale)->type : '', array('class' => 'form-control')) }}
				</div>

				<div class="form-group">
				{{ Form::label($locale . "[text]", 'Text') }}
				{{ Form::textarea($locale . "[text]", isset($collection) ? @$collection->translate($locale)->text : '', array('class' => 'form-control wysiwyg', 'rows'=>'12')) }}
				</div>
			</div>
		@endforeach
	</div>

</div>
<!-- /translatable -->

<div class="col-md-6">
	<div class="form-group">
		{!! Form::label('title_color', 'Farba nadpisu') !!}
		<div class="input-group colorpicker-component">
		    {!! Form::text('title_color', Request::old('title_color'), array('class' => 'form-control', 'placeholder' => '#ffffff')) !!}
		    <span class="input-group-addon"><i></i></span>
		</div>
	</div>
</div>
<div class="col-md-6">
	<div class="form-group">
		{!! Form::label('color', 'Tieň pod nadpisom') !!}
		<div class="input-group colorpicker-component">
		    {!! Form::text('title_shadow', Request::old('title_shadow'), array('class' => 'form-control', 'placeholder' => '#666666')) !!}
		    <span class="input-group-addon"><i></i></span>
		</div>
	</div>
</div>
<div class="col-md-6">
	<div class="form-group">
		{!! Form::label('main_image', 'Obrázok') !!}
		{!! Form::file('main_image', array('class' => 'form-control', 'placeholder' => '#666666'))!!}
		<p>obrazok bude automaticky zmenseny na sirku 1400px</p>
		<p>šírka min: 1400px<br>formát: JPG (vysoka kompresia ~50-60%)</p>
		@if (isset($collection) && $collection->hasHeaderImage())
			<div class="primary-image">
				<b>Aktuálny obrázok:</b>
				<img src="{{ $collection->header_image_src }}" class="img-responsive">
			</div>
		@endif
	</div>
</div>
@can('publish')
<div class="col-md-6">
	<div class="form-group checkbox">
		{!! Form::label('published_at', 'Publikovať') !!}
		{!! Form::text('published_at', @$input['published_at'] ) !!}
	</div>
</div>
@endcan

<div class="col-md-6">
	<div class="form-group checkbox">
		{{ Form::label('featured', 'Zobraziť na homepage') }}
		{{ Form::checkbox('featured', @$input['featured'], options: ['class' => 'form-control']) }}
	</div>
</div>

<div class="col-md-12 text-center">
	{!! Form::submit('Uložiť', array('class' => 'btn btn-default')) !!} &nbsp;
	{!! link_to_route('collection.index', 'Zrušiť', null, array('class' => 'btn btn-default')) !!}
</div>

{!!Form::close() !!}

<div class="col-md-12">
	<h2>Diela</h2>
	@if(isset($collection))

	{!! Form::open(['url' => 'collection/fill']) !!}
	<div class="col-md-5"  id="types">
		<div class="form-group">
		{!! Form::text('ids', @$input['ids'], array('class'=> 'form-control', 'placeholder' => 'ID (oddelene bodkociarkou)')) !!}
		{!! Form::hidden('collection', $collection->id) !!}
		</div>
	</div>
	<div class="col-md-1 text-right">
		<button type="submit" class="btn btn-default" id="add_event"><i class="fa fa-plus"></i> pridať</button>
	</div>
	<div class="col-md-6 text-center"><span class="hidden loader"><i class="fa fa-refresh fa-spin fa-lg"></i> čakaj</span></div>
	{!!Form::close() !!}

	<div class="clearfix"></div>

	<ul class="list-group" id="sortable" data-entity="item"  data-id="{!! $collection->id !!}">
		@foreach ($collection->items as $item)
		<li class="list-group-item vertical-center" data-id="{!! $item->id !!}">
				<i class="fa fa-bars sortable-handle"></i>
				<img src="{!! $item->getImagePath(); !!}" alt="náhľad" class="nahlad" >
				{!! $item->id !!}
				<a href="{!! URL::to('item/' . $item->id . '/edit' ) !!}">{!! $item->author !!} - {!! $item->title !!}</a>
			<span class="pull-right vertical-center">
			    {{-- <a href="#"><i class="fa fa-arrow-up"></i></a> --}}
				{{-- <a href="#"><i class="fa fa-arrow-down"></i></a> --}}
				<a href="{!! URL::to('collection/'.$collection->id.'/detach/'.$item->id) !!}" class="btn btn-danger btn-xs btn-outline" >zmazať</a>
				<a href="{!! $item->getUrl() !!}" class="btn btn-success btn-xs btn-outline" target="_blank">na webe</a>
			</span>
		</li>
		@endforeach
	</ul>
	@else
	    <p>Diela sa dajú pridávať až po vytvorení kolekcie</p>
	@endif
</div>

<div class="clearfix"></div>


@stop

@section('script')

{!! Html::script('js/bootstrap-datepicker.js') !!}

@if (isSet($collection))
<script>
	$(document).ready(function() {
		$('[name="published_at"]').datepicker({
			format: "yyyy-mm-dd",
			language: "sk"
		});

		var sortable = document.getElementById('sortable')
		Sortable.create(sortable, {
			handle: '.sortable-handle',
			ghostClass: "sortable-ghost",
			animation: 150,
			dataIdAttr: 'data-id',

			onEnd: function () {
				$('.loader').removeClass('hidden');

				$.post('/collection/sort', {
					entity: $('#sortable').attr('data-entity'),
					id: $('#sortable').attr('data-id'),
					ids: this.toArray(),
					_token: @js(csrf_token())
				}, function () {
					$('.loader').addClass('hidden');
				});
			}

		});
	});

</script>
@endif
@stop
