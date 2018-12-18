@extends('layouts.master')

@section('title')
{{ utrans('master.keywords') }} |
@parent
@stop

@section('content')

<section class="authors py-5">
    @foreach ($tags as $tag)
        <p class="mb-2 mt-4 font-weight-bold">{{ $tag->name }}</p>

        @php
            $authors = \App\Authority::withAnyTag([$tag->name])->get();
        @endphp

        @foreach ($authors as $i=>$author)
        <div class="author">
            <a href="{!! $author->getUrl() !!}" class="author-title">
                {!! $author->formatedName !!}
            </a>
        </div>
        @endforeach

    @endforeach
</section>

@stop
