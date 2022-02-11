@extends('layouts.admin')

@section('content')
    <div class="col-xs-12 pt-5 tailwind-rules">
        <autocomplete :remote="{ url: '/katalog/suggestions?search=%QUERY' }" option-label="id">
            <template v-slot:option="option">
                <div class="tw-flex">
                    <img :src="option.image" class="tw-h-16 tw-w-16 tw-object-cover">
                    <div class="tw-px-4 tw-py-2 tw-max-w-full tw-truncate">
                        <h4 class="tw-font-semibold">@{{ option.title }}</h4>
                        <span>@{{ option.author }} ∙ @{{ option.id }}</span>
                    </div>
                </div>
            </template>
        </autocomplete>
    </div>

    {{-- @if (isset($featuredPiece))
    {!! Form::model($featuredPiece, ['route' => ['featured-pieces.update', $featuredPiece], 'method' => 'patch', 'files' => true]) !!}
@else
    {!! Form::open(['route' => 'featured-pieces.store', 'files' => true]) !!}
@endif

<div class="col-xs-12">
    @if (Session::has('message'))
        <div class="alert alert-info alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>{!! Session::get('message') !!}</div>
    @endif


    @if ($errors->any())
        <div class="alert alert-danger">
            <a href="#" class="close" data-dismiss="alert">&times;</a>
            <ul>
                @foreach ($errors->all() as $error)
                    <li class="error">{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
</div>

<div class="col-xs-12">
    <div class="form-group">
    {{ Form::label('title', 'Nadpis') }}
    {{ Form::textarea('title', null, ['class' => 'form-control', 'rows' => '2']) }}
    </div>
</div>

<div class="col-xs-12">
    <div class="form-group">
    {{ Form::label('excerpt', 'Text') }}
    {{ Form::textarea('excerpt', null, ['class' => 'form-control', 'rows' => '4']) }}
    </div>
</div>

<div class="col-xs-12">
    <div class="form-group">
    {{ Form::label('url', 'URL') }}
    {{ Form::text('url', null, ['class' => 'form-control', 'placeholder' => 'http://']) }}
    </div>
</div>

<div class="col-md-4">
    <div class="form-group">
        {{ Form::label('image', 'Obrázok') }}
        @if (isset($featuredPiece) && $featuredPiece->hasMedia('image'))
        <div class="pb-4">
            {{ $featuredPiece->getFirstMedia('image')->img()->attributes(['width' => '200', 'height' => null]) }}
        </div>
        @endif
        {{ Form::file('image') }}
        <p>min. šírka 1200px</p>
    </div>
</div>

<div class="col-md-4">
    <div class="form-group">
        {{ Form::label('type', 'Typ') }}
        <div class="radio">
            <label>
                {{ Form::radio('type', 'article', true) }}
                článok
            </label>
        </div>
        <div class="radio">
            <label>
                {{ Form::radio('type', 'collection') }}
                kolekcia
            </label>
        </div>
    </div>
    <div class="form-group">
        {{ Form::label('publish', 'Publikovaný') }}<br>
        {{ Form::hidden('publish', '0') }}
        {{ Form::checkbox('publish', '1', null, ['class' => 'checkbox']) }}
    </div>
</div>

<div class="col-md-12 text-center">
    {{ Form::submit('Uložiť', ['class' => 'btn btn-primary']) }}
    <a href="{{ route('featured-pieces.index') }}" class="btn btn-default ml-2">Zrušiť</a>
</div>

{!! Form::close() !!} --}}

@endsection
