@extends('layouts.master')

@section('link')
    <script type="application/ld+json">
        {
            "@context": "http://schema.org",
            "@type": "WebSite",
            "logo": "{{ asset('images/logo.png') }}",
            "url": "{{ url('') }}",
            "sameAs": [
                "https://www.facebook.com/webumenia.sk",
                "https://twitter.com/webumeniask",
                "https://vimeo.com/webumeniask",
                "https://sk.pinterest.com/webumeniask/",
                "https://instagram.com/web_umenia/"
            ],
            "potentialAction": {
                "@type": "SearchAction",
                "target": "{{ route('frontend.catalog.index', ['search' => '']) }}{query}",
                "query-input": "required name=query"
            }
        }
    </script>
@stop

@section('content')

@stop
