@extends('layouts.master') {{-- TODO figure out layout --}}

@section('title')
    Zdieľanie kolekcie {{-- TODO i18n --}}
    |
    @parent
@stop

@section('content')

@if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form action="{{ $formAction ?? route('frontend.shared-user-collections.store') }}" method="POST">
    @method($formMethod ?? 'POST')
    @csrf

    <label for="name">Názov</label>
    <input type="text" id="name" name="name" value="{{ old('name', $collection->name ?? null) }}" />

    <br />

    <label for="author">Autor</label>
    <input type="text" id="author" name="author" value="{{ old('author', $collection->author ?? null) }}" />

    <br />

    <label for="description">Popis</label>
    <textarea id="description" name="description">{{ old('description', $collection->description ?? null) }}</textarea>

    <br />

    <ul>
        @foreach ($items as $item)
            <li>
                {{ $item->title }}
                <input type="hidden" name="items[][id]" value="{{ $item->id }}" />
            </li>
        @endforeach
    </ul>

    <button type="submit" name="shared-user-collection-submit">Uložiť</button>
</form>
@endsection
