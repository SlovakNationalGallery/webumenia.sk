@extends('layouts.admin')

@section('content')

<div class="col-md-12">
	@if(isset($article))
	    {{ Form::model($article, ['route' => ['article.update', $article->id], 'method' => 'patch', 'files'=>true]) }}
	@else
	    {{ Form::open(['route' => 'article.store', 'files'=>true]) }}
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
	{{ Form::label('category_id', 'Kategória') }}
	@if (isSet($article))
		{{ Form::select('category_id', [''=>''] + Category::lists('name', 'id'), Input::old('category_id'), array('class' => 'select', 'multiple' => true)) }}
	@else
		{{ Form::select('category_id', [''=>''] + Category::lists('name', 'id'), [], array('class' => 'select')) }}
	@endif
	</div>

</div>
<div class="col-md-12">
	<div class="form-group">
	{{ Form::label('author', 'Autor') }}
	{{ Form::text('author', Input::old('author'), array('class' => 'form-control')) }}
	</div>
</div>
<div class="col-md-12">
	<div class="form-group">
	{{ Form::label('title', 'Názov') }}
	{{ Form::text('title', Input::old('title'), array('class' => 'form-control')) }}
	</div>
</div>
<div class="col-md-12">
	<div class="form-group">
	{{ Form::label('slug', 'URL Slug') }}
	{{ Form::text('slug', Input::old('slug'), array('class' => 'form-control')) }}
	</div>
</div>
<div class="col-md-12">
	<div class="form-group">
	{{ Form::label('summary', 'Anotácia') }}
	{{ Form::textarea('summary', Input::old('summary'), array('class' => 'form-control wysiwyg')) }}	
	</div>
</div>
<div class="col-md-12">
	<div class="form-group">
	{{ Form::label('content', 'Text') }}
	{{ Form::textarea('content', Input::old('content'), array('class' => 'form-control wysiwyg')) }}	
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
<div class="col-md-6">
	<div class="form-group">
		{{ Form::label('main_image', 'Obrázok') }}
		{{ Form::file('main_image') }}
		<p>obrazok bude automaticky zmenseny na sirku 1400px</p>
		<p>šírka min: 1400px<br>formát: JPG (vysoka kompresia ~50-60%)</p>
	</div>
</div>
<div class="col-md-6">
	<div class="form-group checkbox">
		{{ Form::label('publish', 'Publikovať') }}
		{{ Form::checkbox('publish', '1', @$input['publish']) }}
	</div>
</div>
<div class="col-md-6">
	<div class="form-group checkbox">
		{{ Form::label('promote', 'Promovať na titulke') }}
		{{ Form::checkbox('promote', '1', @$input['promote']) }}
	</div>
</div>
<div class="col-md-12 text-center">
	{{ Form::submit('Uložiť', array('class' => 'btn btn-default')) }} &nbsp; 
	{{ link_to_route('article.index', 'Zrušiť', null, array('class' => 'btn btn-default')) }}
	{{Form::close() }}
</div>


<div class="clearfix"></div>


@stop

@section('script')

{{ HTML::script('js/selectize.min.js') }}

<script>
$(document).ready(function(){
	
    $("#category_id").selectize({
        plugins: ['remove_button'],
        maxItems: 1,
        allowEmpty: 1,
        mode: 'multi'
    });

    @if(!isset($article))
    $("#title").keyup(function(){
            var text = $(this).val();
            slug = getSlug(text);
            console.log("slug: " + slug);
            $("#slug").val(slug);        
    });
    @endif


});

</script>
@stop