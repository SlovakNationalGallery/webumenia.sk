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
    <div class="tailwind-rules container">
        <h3 class="tw-text-4xl tw-font-semibold">Nový obsah</h3>
        <div class="tw-flex tw-underline-offset-4 tw-text-5xl tw-font-semibold tw-cursor-pointer tw-mt-8">
            <h4 class="tw-transition hover:tw-text-gray-500 tw-underline">Kolekcie</h4>
            <h4 class="tw-transition hover:tw-text-gray-500 tw-underline tw-text-gray-400 tw-ml-4">Články</h4>
        </div>
    </div>
@stop
