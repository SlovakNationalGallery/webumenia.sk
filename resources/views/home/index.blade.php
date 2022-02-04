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
        <h2 class="tw-text-2xl tw-font-semibold">Nový obsah</h2>

        <tabs-controller v-slot="tc">
            <div class="tw-flex tw-text-4xl tw-font-semibold tw-mt-8 tw-space-x-8">
                @foreach(['Kolekcie', 'Články'] as $tab)
                <tab v-slot="{ active }">
                    <h3 :class="[
                        'tw-transition tw-cursor-pointer tw-underline tw-underline-offset-8 tw-decoration-4',
                        !active && 'tw-text-gray-500'
                    ]">{{ $tab }}</h3>
                </tab>
                @endforeach
            </div>
            <div class="tw-mt-8">
                <tab-panel>
                    <flickity :options="{ cellAlign: 'left', contain: true, pageDots: false }">
                        @foreach([1,2,3,4,5] as $f)
                            <div class="tw-w-96 tw-mr-4">
                                <img src="https://webumenia.sk/images/clanky/148/06f6288416067a3704ca579fa22f986b.thumbnail.jpg" class="tw-object-cover tw-w-96 tw-h-48">
                                <span class="tw-mt-4 tw-inline-block tw-bg-gray-200 tw-px-3 tw-py-1">Kolekcia</span>
                                <h4 class="tw-mt-3 tw-text-xl tw-text-black tw-font-semibold">Brána</h4>
                                <span class="tw-mt-3 tw-inline-block tw-text-gray-600">
                                    26. 11. 2021 ∙ Oddelenie Galerijnej pedagogiky SNG
                                </span>
                            </div>
                        @endforeach
                    </flickity>
                </tab-panel>
                <tab-panel>
                    TODO Clanky
                </tab-panel>
            </div>
        </tabs-controller>

        {{-- spacer --}}
        <div class="tw-mb-96"></div>
    </div>
@stop
