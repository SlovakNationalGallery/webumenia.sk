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

        <tabs-controller v-cloak>
            <div class="tw-flex tw-text-4xl tw-font-semibold tw-mt-8 tw-space-x-8">
                @foreach(['Kolekcie', 'Články'] as $tab)
                <tab v-slot="{ active }">
                    <button :class="[
                        'tw-transition-colors tw-underline tw-underline-offset-8 tw-decoration-3',
                        !active && 'tw-text-gray-500'
                    ]">{{ $tab }}</button>
                </tab>
                @endforeach
            </div>
            @php
                $articles = App\Article::query()
                    ->with(['translations', 'category'])
                    ->published()
                    ->orderBy('published_date', 'desc')
                    ->limit(5)
                    ->get();

                $articlesCount = App\Article::published()->count();
                $articlesRemainingCount = floor(($articlesCount - 5) / 10) * 10; // Round down to nearest 10

            @endphp
            <div class="tw-mt-8">
                <tab-panel v-slot="{ active }">
                    <flickity :resize-once="active" :options="{ cellAlign: 'left', contain: true, pageDots: false }">
                        @foreach($articles as $a)
                            <div class="tw-w-104 tw-mr-4">
                                <a href="{{ route('frontend.article.detail', $a->slug) }}">
                                    <img src="{{ $a->getThumbnailImage() }}" class="tw-object-cover tw-h-48 tw-transition-opacity tw-duration-300 hover:tw-opacity-80">
                                </a>
                                <span class="tw-mt-5 tw-inline-block tw-bg-gray-200 tw-px-3 tw-py-1">
                                    {{ Str::ucfirst($a->category->name ?? "článok") }}
                                </span>
                                <h4 class="tw-mt-3 tw-text-xl tw-text-black tw-font-semibold tw-truncate">
                                    <a href="{{ route('frontend.article.detail', $a->slug) }}">
                                        {{ $a->title }}
                                    </a>
                                </h4>
                                <div class="tw-mt-3 tw-text-gray-600 tw-truncate">
                                    {{ $a->published_date->format('d. m. Y') }} ∙ {{ $a->author }}
                                </div>
                            </div>
                        @endforeach
                        <div class="tw-w-104 tw-h-full tw-bg-gray-200 tw-text-center tw-flex tw-flex-col tw-justify-center tw-text-xl tw-text-black tw-font-semibold">
                            <p>Na Webe umenia je ďalších viac<br/> ako {{ $articlesRemainingCount }} článkov</p>
                            <a class="tw-mt-5 tw-underline tw-decoration-gray-300 tw-decoration-3 tw-underline-offset-4 hover:tw-transition-colors hover:tw-decoration-current" href="{{ route('frontend.article.index') }}">Zobraziť ďalšie články</a>
                        </div>
                    </flickity>
                </tab-panel>
            </div>
        </tabs-controller>

        {{-- spacer --}}
        <div class="tw-mb-96"></div>
    </div>
@stop
