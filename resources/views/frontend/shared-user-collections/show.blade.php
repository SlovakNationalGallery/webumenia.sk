@extends('layouts.master')

@section('title')
    {{ $collection->name }}
    @parent
@stop

@section('og')
<meta property="og:title" content="{{ $collection->name }}" />
<meta property="og:description" content="{{ $collection->description ?? trans('user-collection.shared.og.fallback_description') }}" />
<meta property="og:type" content="website" />
<meta property="og:url" content="{{ route('frontend.shared-user-collections.show', $collection) }}" />
<meta property="og:image" content="{{ route('dielo.nahlad', ['id' => $items->first()->id, 'width' => 800]) }}" />
@stop

@section('main-navigation')
<!-- Skip -->
@stop

@section('content')
<div class="container pt-5 mt-5">
    <div class="mb-5 pb-5 text-lg text-muted">
        <p>
            Výber diel z <a href="{{ config('app.url') }}" class="underline">Webu <i class="fa fa-search color"></i> umenia</a>.
        </p>
        <p class="mt-3">
            Inšpirované výstavou <a href="https://medium.com/sng-online/vystava-sedmicky/home" class="underline">Sedmičky v SNG</a>.
        </p>
    </div>
    <x-user-collections.share-form
        :collection="$collection"
        :items="$items"
        disabled
    />
    <div class="pb-5 my-5 text-center text-lg text-muted">
        <p>
            Vytvor a zdieľaj vlastný výber výtvarných diel na <a href="{{ config('app.url') }}" class="underline">Webe umenia</a>.
        </p>
        <p class="mt-3">
            Tematické výbery kurátoriek a kurátorov Slovenskej národnej galérie nájdeš na výstave <a href="https://medium.com/sng-online/vystava-sedmicky/home" class="underline">Sedmičky</a>.
        </p>
    </div>
</div>
@endsection
