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

<div class="container pt-5">
    <x-user-collections.share-form
        :items="$items"
        :action="route('frontend.shared-user-collections.store')"
        method="POST"
        editable
        creating
    />
</div>

@endsection
