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
<x-user-collections.share-form 
    :collection="$collection"
    :items="$items"
    disabled 
/>
@endsection
