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

@php
    $editable = $editable ?? false;    
@endphp
<form action="{{ $formAction ?? route('frontend.shared-user-collections.store') }}" method="POST">
    @method($formMethod ?? 'POST')
    @csrf

    <div class="container content-section">
        <div class="row">
            <div class="column">
                <textarea name="name" class="borderless">{{ old('name', $collection->name ?? null) }}</textarea>
                {{-- <input type="text" class="borderless" id="name" name="name" value="{{ old('name', $collection->name ?? null) }}" /> --}}
                {{-- <input type="hidden" name="name" value="{{ old('name', $collection->name ?? null) }}" /> --}}

                <borderless-input 
                    name="name" 
                    value="{{ old('name', $collection->name ?? null) }}"
                    class="borderless-72"
                    placeholder="Zadaj nazov"
                ></borderless-input>

                <br />

                <label for="author">Autor</label>
                <input type="text" id="author" name="author" value="{{ old('author', $collection->author ?? null) }}" />

                <br />

                <label for="description">Popis</label>
                <textarea id="description" name="description">{{ old('description', $collection->description ?? null) }}</textarea>

                <br />

                @if(isset($collection) && $editable)
                @php
                    $shareableUrl = route('frontend.shared-user-collections.show', compact('collection'));
                @endphp

                <div class="panel panel-default">
                    <h4>Zdieľaj odkaz na svoj výber:</h4>
                    <p>
                        <strong>{{ $shareableUrl }}</strong>
                        <a
                            class="btn btn-outline no-border"
                            data-toggle="tooltip"
                            data-trigger="hover"
                            title="{{ trans('general.copy') }}"
                            data-success-title="{{ trans('general.copied_to_clipboard') }}"
                            data-clipboard-text="{{ $shareableUrl }}"
                        >
                            <i class="fa fa-clipboard"></i> {{ trans('general.copy') }}
                        </a>
                    </p>
                    <p>Ak chceš dalej editovať, ulož si túto adresu:
                        {{ route('frontend.shared-user-collections.edit', ['collection' => $collection, 'token' => $collection->update_token]) }}
                    </p>
                </div>
                @endif

                <ul>
                    @foreach ($items as $item)
                        <li>
                            {{ $item->title }}
                            <input type="hidden" name="items[][id]" value="{{ $item->id }}" />
                        </li>
                    @endforeach
                </ul>

                @if($editable)
                <button type="submit" name="shared-user-collection-submit">Uložiť</button>
                @endif
            </div>
        </div>
    </div>
</form>
@endsection
