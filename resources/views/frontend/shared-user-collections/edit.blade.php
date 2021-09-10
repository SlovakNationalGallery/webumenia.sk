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
    $creating = !isset($collection);
    $method = $creating ? 'POST' : 'PUT';
    $action = $creating 
        ? route('frontend.shared-user-collections.store')
        : route('frontend.shared-user-collections.update', ['collection' => $collection, 'token' => $collection->update_token]);
@endphp

<div class="container pt-5">
    <x-user-collections.share-form
        :items="$items"
        :collection="$collection ?? null"
        :action="$action"
        :method="$method"
    />
</div>

@endsection
