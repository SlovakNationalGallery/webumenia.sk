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

@php
    $updateUrl = route(
        'frontend.shared-user-collections.update', 
        [
            'collection' => $collection,
            'token' => $collection->update_token,
        ]
    );
    $shareUrl = route('frontend.shared-user-collections.show', compact('collection'));
@endphp

<div class="container pt-5">
    <x-user-collections.share-form
        :items="$items"
        :collection="$collection"
        :action="$updateUrl"
        method="PUT"
        editable
    />
</div>
<div tabindex="-1" class="modal fade" id="confirm" role="dialog">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title">Zdieľaj odkaz na svoj výber</h4>
            </div>  
            <div class="modal-body">
                <p>
                    <strong>{{ $shareUrl }}</strong>
                    <a
                        class="btn btn-outline no-border"
                        data-toggle="tooltip"
                        data-trigger="hover"
                        title="{{ trans('general.copy') }}"
                        data-success-title="{{ trans('general.copied_to_clipboard') }}"
                        data-clipboard-text="{{ $shareUrl }}"
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
@endsection
