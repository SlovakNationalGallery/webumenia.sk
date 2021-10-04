@extends('layouts.master')

@section('title')
    {{ $collection->name }}
    @parent
@stop

@section('main-navigation')
<!-- Skip -->
@stop

@section('content')
<div class="container pt-5 mt-5">
    <div class="mb-5 pb-5 text-lg text-muted">
        <p>
            Výber diel z <a href="#TODO" class="underline">Webu <i class="fa fa-search color"></i> umenia</a>.
        </p>
        <p class="mt-3">
            Inšpirované výstavou <a href="#TODO" class="underline">Sedmičky v SNG</a>.
        </p>
    </div>
    <x-user-collections.share-form
        :collection="$collection"
        :items="$items"
        disabled
    />
    <div class="pb-5 my-5 text-center text-lg text-muted">
        <p>
            Vytvor a zdieľaj vlastný výber výtvarných diel na <a href="#TODO" class="underline">Webe umenia</a>.
        </p>
        <p class="mt-3">
            Tematické výbery kurátoriek a kurátorov Slovenskej národnej galérie nájdeš na výstave <a href="#TODO" class="underline">Sedmičky</a>.
        </p>
    </div>
</div>
@endsection
