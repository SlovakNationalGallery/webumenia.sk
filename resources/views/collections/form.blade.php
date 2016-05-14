@extends('layouts.admin')

@section('content')

<div class="col-md-12">
	@if(isset($collection))
	    {!! Form::model($collection, ['route' => ['collection.update', $collection->id], 'method' => 'patch', 'files'=>true]) !!}
	@else
	    {!! Form::open(['route' => 'collection.store', 'files'=>true]) !!}
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
@if (Entrust::hasRole('admin'))
<div class="col-md-4">
	<div class="form-group">
	{!! Form::label('user_id', 'autor') !!}
	{!! Form::select('user_id', App\User::lists('name','id'), Input::old('user_id', (isSet($collection)) ? $collection->user_id : Auth::user()->id), array('class' => 'form-control')) !!}
	</div>
</div>
@endif
<div class="col-md-12">
	<div class="form-group">
	{!! Form::label('type', 'Typ') !!}
	{!! Form::select('type', ['sekcia'=>'sekcia'], Input::old('type'), array('class' => 'form-control')) !!}
	</div>
</div>
<div class="col-md-12">
	<div class="form-group">
	{!! Form::label('name', 'Názov') !!}
	{!! Form::text('name', Input::old('name'), array('class' => 'form-control')) !!}
	</div>
</div>
<div class="col-md-12">
	<div class="form-group">
	{!! Form::label('text', 'Text') !!}
	{!! Form::textarea('text', Input::old('text'), array('class' => 'form-control wysiwyg')) !!}	
	</div>
</div>
<div class="col-md-6">
	<div class="form-group">
		{!! Form::label('title_color', 'Farba nadpisu') !!}
		<div class="input-group colorpicker-component">
		    {!! Form::text('title_color', Input::old('title_color'), array('class' => 'form-control', 'placeholder' => '#ffffff')) !!}
		    <span class="input-group-addon"><i></i></span>
		</div>
	</div>
</div>
<div class="col-md-6">
	<div class="form-group">
		{!! Form::label('color', 'Tieň pod nadpisom') !!}
		<div class="input-group colorpicker-component">
		    {!! Form::text('title_shadow', Input::old('title_shadow'), array('class' => 'form-control', 'placeholder' => '#666666')) !!}
		    <span class="input-group-addon"><i></i></span>
		</div>
	</div>
</div>
<div class="col-md-6">
	<div class="form-group">
		{!! Form::label('main_image', 'Obrázok') !!}
		{!! Form::file('main_image') !!}
		<p>obrazok bude automaticky zmenseny na sirku 1400px</p>
		<p>šírka min: 1400px<br>formát: JPG (vysoka kompresia ~50-60%)</p>
	</div>
</div>
<div class="col-md-6">
	<div class="form-group checkbox">
		{!! Form::label('publish', 'Publikovať') !!}
		{!! Form::checkbox('publish', '1', @$input['publish']) !!}
	</div>
</div>
<div class="col-md-12 text-center">
	{!! Form::submit('Uložiť', array('class' => 'btn btn-default')) !!} &nbsp; 
	{!! link_to_route('collection.index', 'Zrušiť', null, array('class' => 'btn btn-default')) !!}
	{!!Form::close() !!}
</div>

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
@if (isSet($collection))
<script>

	Sortable.create(sortable, {
	  handle: '.sortable-handle',
	  ghostClass: "sortable-ghost", 
	  animation: 150,
	  dataIdAttr: 'data-id',

	  onEnd: function () {
	      $('.loader').removeClass('hidden');

	      $.post('/collection/sort', {
	          // entity: this.attr('data-entity'),
	          entity: $('#sortable').attr('data-entity'),
	          id: $('#sortable').attr('data-id'),
	          ids: this.toArray()
	      }, function (data) {
	          if('success'==data.result){
	              // for (id in data.orders) {
	                  // var order = data.orders[id],
	                      // $list = $('#sortable[data-entity=' + data.entity + '] .list-group-item[data-id=' + id + ']').attr('data-order', order);
	              // }
	              // $('#sortable[data-entity=' + data.entity + ']').each(function () {
	                  // var $list = $(this);
	                  // LarakitManager.sort($list);
	              // });
	          } else {

	          }
	          // larakit_toastr(data);
	          $('.loader').addClass('hidden');
	      });
	  }

	});

</script>
@endif
@stop