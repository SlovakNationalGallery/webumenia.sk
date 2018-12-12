@extends('layouts.master')

@section('title')
@parent
@stop

@section('link')
<script type="application/ld+json">
{
    "@context": "http://schema.org",
    "@type": "WebSite",
    "logo": " {!! asset('images/logo.png') !!}",
    "url": "{!! URL::to('') !!}",
    "sameAs" : [
        "https://www.facebook.com/webumenia.sk",
        "https://twitter.com/webumeniask",
        "http://webumenia.tumblr.com/",
        "https://vimeo.com/webumeniask",
        "https://sk.pinterest.com/webumeniask/",
        "https://instagram.com/web_umenia/"
    ],
    "potentialAction": {
      "@type": "SearchAction",
      "target": "{!! URL::to('katalog') !!}/?search={query}",
      "query-input": "required name=query"
    }
}
</script>
@stop

@section('content')


<div class="row">
    @foreach ($authors as $i=>$author)
    <div class="col-6 p-0 grid-cell-speaker" style="background-image: url({!! $author->getImagePath() !!});">
        <img class="grid-cell-speaker-artwork" src="/images/khb/artist-{!!$i+1!!}.jpg">
        <h2 class="grid-cell-speaker-name m-3">{!! $author->formatedName !!}</h2>
    </div>
    @endforeach
</div>


@stop

@section('javascript')
<script type="text/javascript">

</script>
@stop
