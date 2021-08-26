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
            <div class="column text-center">
                <inline-input 
                    name="name" 
                    value="{{ old('name', $collection->name ?? null) }}"
                    class="text-xl"
                    placeholder="Zadaj názov"
                ></inline-input> 

                <br />

                <inline-input 
                    name="author" 
                    value="{{ old('author', $collection->author ?? null) }}"
                    class="mt-4 dark-grey"
                    placeholder="Author(ka)"
                ></inline-input>

                <br />

                <inline-input 
                    name="description" 
                    value="{{ old('description', $collection->description ?? null) }}"
                    placeholder="Popíš svoju collection"
                    class="text-lg mt-4 grey"
                /></inline-input>

                @if($editable)
                <button type="submit" name="shared-user-collection-submit">Uložiť</button>
                @endif
            </div>
        </div>
        <div class="row">
            <div class="column">
                <ul>
                    @foreach ($items as $item)
                        <li>
                            {{ $item->title }}
                            <input type="hidden" name="items[][id]" value="{{ $item->id }}" />
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
</form>

<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#confirm">
    Launch demo modal
  </button>

@if(isset($collection) && $editable)
    @php
        $shareableUrl = route('frontend.shared-user-collections.show', compact('collection'));
    @endphp

    <div tabindex="-1" class="modal fade" id="confirm" role="dialog">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                     <h4 class="modal-title">Zdieľaj odkaz na svoj výber</h4>
                </div>  
                <div class="modal-body">
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
            </div>
        </div>
    </div>
@endif
@endsection
