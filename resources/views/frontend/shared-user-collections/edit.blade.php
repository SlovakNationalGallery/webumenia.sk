@extends('layouts.master')

@php
    $creating = !isset($collection);
    $method = $creating ? 'POST' : 'PUT';
    $action = $creating
        ? route('frontend.shared-user-collections.store')
        : route('frontend.shared-user-collections.update', ['collection' => $collection, 'token' => $collection->update_token]);
@endphp

@section('title')
    @if($creating)
        Zdieľanie kolekcie
    @else
        {{ $collection->name }}
    @endif
    |
    @parent
@stop

@section('main-navigation')
<!-- Skip -->
@stop

@section('content')

<div class="container pt-5 mt-5">
    <div class="mb-5 pb-5 tw-text-lg">
        <i class="fa fa-arrow-left tw-text-xl tw-text-gray-800 mt-2 mr-3"></i>
        <user-collections-link
            base-href="{{ route('frontend.user-collection.show') }}"
            class="underline"
            style="vertical-align: text-bottom"
        >
            Späť na obľúbené diela
        </user-collections-link>
    </div>
    <x-user-collections.share-form
        :items="$items"
        :collection="$collection ?? null"
        :action="$action"
        :method="$method"
    />
</div>

@endsection
