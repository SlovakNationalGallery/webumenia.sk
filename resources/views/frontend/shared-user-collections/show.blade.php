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

<user-collections-share-form 
    action="{{ $formAction ?? route('frontend.shared-user-collections.store') }}"
    initial-value-name="{{ old('name', $collection->name ?? null) }}"
    initial-value-author="{{ old('author', $collection->author ?? null) }}"
    initial-value-description="{{ old('description', $collection->description ?? null) }}"
    v-slot="form"
>
    @method($formMethod ?? 'POST')
    @csrf

    
    <div class="container content-section">
        <transition
            enter-active-class="animated fadeInDown faster"
            leave-active-class="animated fadeOut"
        >
            <div v-if="form.editing" class="row">
                <div class="column">
                    <button type="submit" class="btn btn-primary">Uložiť</button>
                </div>
            </div>
        </transition>

        <div class="row">
            <div class="column text-center">
                <inline-input 
                    name="name" 
                    placeholder="Zadaj názov"
                    :class="['text-xl', { border: form.editing }]"
                    v-model="form.values.name"
                    v-on:focus="form.setEditing(true)"
                /></inline-input>

                <br />

                <inline-input 
                    name="author"
                    placeholder="Autor(ka)"
                    :class="['mt-4 pb-2 text-lg dark-grey', { border: form.editing }]"
                    v-model="form.values.author"
                    v-on:focus="form.setEditing(true)"
                ></inline-input>

                <br />

                <inline-input 
                    name="description" 
                    placeholder="Popíš svoju collection"
                    :class="['text-lg mt-4 pb-2 grey', { border: form.editing }]"
                    v-model="form.values.description"
                    v-on:focus="form.setEditing(true)"
                /></inline-input>
            </div>
        </div>
        <div class="row my-5">
            <div class="column text-center">
                <button type="button" class="btn btn-secondary" data-toggle="modal" data-target="#confirm">
                    Zdieľať
                </button>
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
</user-collections-share-form>

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
