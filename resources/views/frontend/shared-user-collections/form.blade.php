@extends('layouts.master') {{-- TODO figure out layout --}}

@section('title')
    Zdieľanie kolekcie {{-- TODO i18n --}}
    |
    @parent
@stop

@section('main-navigation')
<!-- Skip -->
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
    $action = request()->route()->getActionMethod();
@endphp

<div class="container pt-5">
    <div class="container pt-5">
    <user-collections-share-form
        action="{{ $formAction ?? route('frontend.shared-user-collections.store') }}"
        :creating="@json($action === 'create')"
        v-slot="form"
    >
        @method($formMethod ?? 'POST')
        @csrf

        <div class="row">
            <div class="col-xs-12 text-center">
                <inline-input 
                    name="name" 
                    placeholder="Nazvi svoj výber" {{-- TODO i18n --}}
                    value="{{ old('name', $collection->name ?? null) }}"
                    :class="['text-4xl text-center', { border: form.editing }]"
                    :disabled="@json($action === 'show')"
                    :focused="@json($action === 'create')"
                    spellcheck="false"
                    required
                    v-on:focus="form.setEditing(true)"
                /></inline-input>

                <br />

                <inline-input 
                    name="author"
                    placeholder="Tvoje meno" {{-- TODO i18n --}}
                    value="{{ old('author', $collection->author ?? null) }}"
                    :class="['mt-5 pb-2 text-xl text-center dark-grey', { border: form.editing }]"
                    :disabled="@json($action === 'show')"
                    spellcheck="false"
                    v-on:focus="form.setEditing(true)"
                ></inline-input>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-6 col-sm-offset-3">
                <inline-input 
                    name="description" 
                    placeholder="Stručne popíš svoj výber. Môžeš priblížiť jeho tému, príbeh, súvislosti medzi dielami alebo emócie, ktoré ťa viedli práve k tejto selekcii." {{-- TODO i18n --}}
                    value="{{ old('description', $collection->description ?? null) }}"
                    :class="['mt-5 pb-2 text-lg  grey font-serif', { border: form.editing }]"
                    :disabled="@json($action === 'show')"
                    spellcheck="false"
                    v-on:focus="form.setEditing(true)"
                /></inline-input>
            </div>
        </div>
        @if (session('created-message'))
            <div class="row mt-5">
                <div class="col-sm-6 col-sm-offset-3">
                    <div class="alert alert-info alert-dismissable mb-0">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                        {{ session('created-message') }}
                    </div>
                </div>
            </div>
        @endif
        @if (in_array($action, ['edit', 'create'], true))
            <div class="row mt-5" style="height:34px" v-cloak>
                <div class="col-sm-6 col-sm-offset-3 text-center">
                    <transition
                        enter-active-class="animated fadeInDown faster"
                        leave-active-class="animated fadeOutUp faster"
                        mode="out-in"
                    >
                        <button v-if="form.editing" type="submit" class="btn btn-secondary" key="save">
                            Uložiť {{-- TODO i18n --}}
                        </button>
                        @if ($action === 'edit')
                            <button v-if="!form.editing" type="button" class="btn btn-info" key="share" data-toggle="modal" data-target="#confirm">
                                Zdieľať výber <i class='ml-1 fa fa-share-alt'></i> {{-- TODO i18n --}}
                            </button>
                        @endif
                    </transition> 
                </div>
            </div>
        @endif
        <div class="row grid mt-5 pt-5" style="max-width: 800px; margin: auto">
            <div id="column-sizer" class="col-sm-6"></div>
            @foreach ($items as $index => $item)
                <input type="hidden" name="items[][id]" value="{{ $item->id }}" />
                @include('components.artwork_grid_item', [
                    'item' => $item,
                    'isotope_item_selector_class' => 'item',
                    'class_names' => 'grid-item ' . (function () use ($index, $item) {
                        if ($index === 0) {
                            return $item->image_ratio > 1 ? 'col-sm-12' : 'col-sm-6';    
                        }

                        return $item->image_ratio > 1.2 ? 'col-sm-12' : 'col-sm-6';
                    })()
                ])
            @endforeach
        </div>
    </user-collections-share-form>
</div>
@if ($action === 'edit')
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

@section('javascript')
<script type="text/javascript">
    $('.grid').masonry({
        itemSelector: '.grid-item',
        columnWidth: '#column-sizer',
        percentPosition: true,
    })
</script>
@endsection
