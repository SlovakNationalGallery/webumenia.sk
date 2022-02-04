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

        <tabs-controller v-cloak v-slot="{ activeIndex }">
            <div class="tw-flex tw-items-end">
                <div class="tw-flex tw-text-4xl tw-font-semibold tw-mt-8 tw-space-x-8 tw-grow">
                    @foreach(['Kolekcie', 'Články'] as $tab)
                    <tab v-slot="{ active }">
                        <button :class="[
                            'tw-transition-colors tw-underline tw-underline-offset-8 tw-decoration-3',
                            !active && 'tw-text-gray-500'
                        ]">{{ $tab }}</button>
                    </tab>
                    @endforeach
                </div>

                <a v-if="activeIndex === 0" href="{{ route('frontend.collection.index') }}" class="tw-hidden sm:tw-inline-block tw-border-gray-300 tw-border tw-px-4 tw-py-2 hover:tw-bg-gray-300 hover:tw-border-gray-400 tw-transition tw-duration-300">
                    Všetky kolekcie
                    <i class="fa icon-arrow-right tw-ml-4"></i>
                </a>
                <a v-if="activeIndex === 1" href="{{ route('frontend.article.index') }}" class="tw-hidden sm:tw-inline-block tw-border-gray-300 tw-border tw-px-4 tw-py-2 hover:tw-bg-gray-300 hover:tw-border-gray-400 tw-transition tw-duration-300">
                    Všetky články
                    <i class="fa icon-arrow-right tw-ml-4"></i>
                </a>
            </div>
            <div class="tw-mt-8">
                <tab-panel v-slot="{ active }" class="tw-relative">
                    <flickity :resize-once="active" :options="{ cellAlign: 'left', contain: true, pageDots: false, prevNextButtons: false }">
                        @foreach($collections as $c)
                            <div class="tw-w-104 tw-mr-4">
                                <div class="tw-relative">
                                    <a href="{{ route('frontend.collection.detail', $c->id) }}">
                                        <img src="{{ $c->getThumbnailImage() }}" class="tw-object-cover tw-h-48 tw-transition-opacity tw-duration-300 hover:tw-opacity-80">
                                    </a>

                                    <div class="tw-absolute tw-inset-0 tw-pointer-events-none tw-text-right">
                                        <div class="tw-inline-block tw-relative tw-m-4">
                                            <svg viewBox="0 0 106 28" class="tw-absolute tw-inset-0 tw-fill-transparent" preserveAspectRatio="none" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M100 23.1818V26C100 26.5523 99.5523 27 99 27H2C1.44772 27 1 26.5523 1 26V7C1 6.44772 1.44772 6 2 6H4" stroke="black"/>
                                                <rect x="4" width="98" height="20" rx="1" fill="black" fill-opacity="0.6"/>
                                                <path d="M102 4H104C104.552 4 105 4.44772 105 5V22C105 22.5523 104.552 23 104 23H8C7.44771 23 7 22.5523 7 22V20.15" stroke="black"/>
                                            </svg>
                                            <div class="tw-text-white tw-text-sm tw-relative px-3">
                                                {{ $c->items_count }} diel v kolekcii
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <span class="tw-mt-5 tw-inline-block tw-bg-gray-200 tw-px-3 tw-py-1">
                                    {{ Str::ucfirst($c->type ?? "kolekcia") }}
                                </span>
                                <h4 class="tw-mt-3 tw-text-xl tw-text-black tw-font-semibold tw-truncate">
                                    <a href="{{ route('frontend.collection.detail', $c->id) }}">
                                        {{ $c->name }}
                                    </a>
                                </h4>
                                <div class="tw-mt-3 tw-text-gray-600 tw-truncate">
                                    {{ $c->published_at->format('d. m. Y') }} ∙ {{ $c->user->name }}
                                </div>
                            </div>
                        @endforeach
                        <div class="tw-w-full sm:tw-w-104 tw-h-full tw-bg-gray-200 tw-text-center tw-flex tw-flex-col tw-justify-center tw-text-xl tw-text-black tw-font-semibold">
                            <p>Na Webe umenia je ďalších viac<br/> ako {{ $collectionsRemainingCount }} kolekcií</p>
                            <a class="tw-mt-5 tw-underline tw-decoration-gray-300 tw-decoration-3 tw-underline-offset-4 hover:tw-transition-colors hover:tw-decoration-current" href="{{ route('frontend.collection.index') }}">Zobraziť ďalšie kolekcie</a>
                        </div>

                        <template v-slot:custom-ui="{ next, previous, selectedIndex, slides }">
                            <div class="tw-absolute tw-inset-y-0 tw-inset-x-4 md:tw--inset-x-7 tw-flex tw-items-center tw-justify-between tw-pointer-events-none">
                                <button
                                    v-on:click="previous"
                                    :disabled="selectedIndex === 0"
                                    class="tw-pointer-events-auto tw-rounded-full tw-bg-gray-300 tw-bg-opacity-60 hover:tw-bg-opacity-90 disabled:hover:tw-bg-opacity-60 disabled:tw-opacity-30 tw-transition tw-w-10 tw-h-10 sm:tw-w-14 sm:tw-h-14 tw-flex tw-items-center tw-justify-center tw-text-xl">
                                    <i class="fa icon-arrow-left"></i>
                                </button>
                                <button
                                    v-on:click="next"
                                    :disabled="selectedIndex === slides.length - 1"
                                    class="tw-pointer-events-auto tw-rounded-full tw-bg-gray-300 tw-bg-opacity-60 hover:tw-bg-opacity-90 disabled:hover:tw-bg-opacity-60 disabled:tw-opacity-30 tw-transition tw-w-10 tw-h-10 sm:tw-w-14 sm:tw-h-14 tw-flex tw-items-center tw-justify-center tw-text-xl">
                                    <i class="fa icon-arrow-right"></i>
                                </button>
                            </div>
                        </template>
                    </flickity>
                </tab-panel>

                <tab-panel v-slot="{ active }" class="tw-relative">
                    <flickity :resize-once="active" :options="{ cellAlign: 'left', contain: true, pageDots: false, prevNextButtons: false }">
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
                        <div class="tw-w-full sm:tw-w-104 tw-h-full tw-bg-gray-200 tw-text-center tw-flex tw-flex-col tw-justify-center tw-text-xl tw-text-black tw-font-semibold">
                            <p>Na Webe umenia je ďalších viac<br/> ako {{ $articlesRemainingCount }} článkov</p>
                            <a class="tw-mt-5 tw-underline tw-decoration-gray-300 tw-decoration-3 tw-underline-offset-4 hover:tw-transition-colors hover:tw-decoration-current" href="{{ route('frontend.article.index') }}">Zobraziť ďalšie články</a>
                        </div>

                        <template v-slot:custom-ui="{ next, previous, selectedIndex, slides }">
                            <div class="tw-absolute tw-inset-y-0 tw-inset-x-4 md:tw--inset-x-7 tw-flex tw-items-center tw-justify-between tw-pointer-events-none">
                                <button
                                    v-on:click="previous"
                                    :disabled="selectedIndex === 0"
                                    class="tw-pointer-events-auto tw-rounded-full tw-bg-gray-300 tw-bg-opacity-60 hover:tw-bg-opacity-90 disabled:hover:tw-bg-opacity-60 disabled:tw-opacity-30 tw-transition tw-w-10 tw-h-10 sm:tw-w-14 sm:tw-h-14 tw-flex tw-items-center tw-justify-center tw-text-xl">
                                    <i class="fa icon-arrow-left"></i>
                                </button>
                                <button
                                    v-on:click="next"
                                    :disabled="selectedIndex === slides.length - 1"
                                    class="tw-pointer-events-auto tw-rounded-full tw-bg-gray-300 tw-bg-opacity-60 hover:tw-bg-opacity-90 disabled:hover:tw-bg-opacity-60 disabled:tw-opacity-30 tw-transition tw-w-10 tw-h-10 sm:tw-w-14 sm:tw-h-14 tw-flex tw-items-center tw-justify-center tw-text-xl">
                                    <i class="fa icon-arrow-right"></i>
                                </button>
                            </div>
                        </template>
                    </flickity>
                </tab-panel>
            </div>
        </tabs-controller>

        {{-- spacer --}}
        <div class="tw-mb-96"></div>
    </div>
@stop
