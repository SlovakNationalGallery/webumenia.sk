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
    <div class="tailwind-rules">

        @if ($featuredPiece)
            <div class="tw-relative">
                {{ $featuredPiece->image->img()->attributes(['class' => 'tw-object-cover tw-absolute tw-h-full tw-w-full', 'width' => null, 'height' => null]) }}
                <div class="tw-absolute tw-inset-0 tw-bg-gradient-to-r tw-from-black/70 tw-to-black/40 md:tw-to-black/0">
                </div>
                <div
                    class="tw-container tw-mx-auto tw-px-6 tw-max-w-screen-xl tw-relative tw-pt-60 tw-pb-8 md:tw-pb-20 tw-text-white">
                    <h2 class="md:tw-text-2xl tw-font-semibold">Odporúčame</h2>
                    <h3 class="tw-mt-4 md:tw-mt-6 tw-text-3xl md:tw-text-6xl tw-font-semibold">{{ $featuredPiece->title }}</h3>
                    <p class="tw-mt-5 tw-font-serif md:tw-text-xl tw-max-w-lg tw-leading-relaxed">{{ $featuredPiece->excerpt }}</p>
                    <a href="{{ $featuredPiece->url }}"
                        class="tw-inline-block tw-mt-3 md:tw-mt-6 tw-text-sm tw-border-gray-300 tw-border tw-px-4 tw-py-2 hover:tw-bg-white hover:tw-border-gray-400 tw-transition tw-duration-300">
                        {{ $featuredPiece->is_collection ? 'Prejsť na kolekciu' : 'Prejsť na článok' }}
                        <i class="fa icon-arrow-right tw-ml-2"></i>
                    </a>
                </div>
            </div>
        @endif

        <div class="tw-container tw-mx-auto tw-px-6 tw-pt-6 tw-max-w-screen-xl">
            <h2 class="md:tw-text-2xl tw-font-semibold">Nový obsah</h2>

            <tabs-controller v-cloak v-slot="{ activeIndex }">
                <div class="tw-flex tw-items-end">
                    <div class="tw-flex tw-mt-2 md:tw-mt-4 tw-space-x-4 tw-grow">
                        @foreach (['Kolekcie', 'Články'] as $tab)
                            <tab v-slot="{ active }">
                                <button
                                    :class="[
                            'tw-transition-colors tw-font-semibold tw-text-2xl md:tw-text-4xl tw-underline tw-underline-offset-[5px] md:tw-underline-offset-8 tw-decoration-3',
                            !active && 'tw-text-gray-300'
                        ]">{{ $tab }}</button>
                            </tab>
                        @endforeach
                    </div>

                    <transition enter-active-class="tw-transition tw-duration-150" enter-class="tw-opacity-0"
                        enter-to-class="tw-opacity-100" leave-active-class="tw-transition tw-duration-150"
                        leave-class="tw-opacity-100" leave-to-class="tw-opacity-0" mode="out-in">
                        <a key="0" v-if="activeIndex === 0" href="{{ route('frontend.collection.index') }}"
                            class="tw-hidden sm:tw-inline-block tw-text-sm tw-border-gray-300 tw-border tw-px-4 tw-py-2 hover:tw-bg-gray-300 hover:tw-border-gray-400 tw-transition tw-duration-300">
                            Všetky kolekcie
                            <i class="fa icon-arrow-right tw-ml-1 tw-mr-2"></i>
                        </a>
                        <a key="1" v-if="activeIndex === 1" href="{{ route('frontend.article.index') }}"
                            class="tw-hidden sm:tw-inline-block tw-text-sm tw-border-gray-300 tw-border tw-px-4 tw-py-2 hover:tw-bg-gray-300 hover:tw-border-gray-400 tw-transition tw-duration-300">
                            Všetky články
                            <i class="fa icon-arrow-right tw-ml-1 tw-mr-2"></i>
                        </a>
                    </transition>
                </div>
                <div class="tw-mt-6 md:tw-mt-8">
                    <tab-panel v-slot="{ active }" class="tw-relative">
                        <flickity :resize-once="active"
                            :options="{ cellAlign: 'left', contain: true, pageDots: false, prevNextButtons: false, freeScroll: true }">
                            @foreach ($collections as $c)
                                <div class="tw-w-72 md:tw-w-1/3 xl:tw-w-1/4 tw-pr-4">
                                    <div class="tw-relative">
                                        <a href="{{ route('frontend.collection.detail', $c->id) }}">
                                            <img src="{{ $c->getThumbnailImage() }}"
                                                class="tw-object-cover tw-h-48 tw-transition-opacity tw-duration-300 hover:tw-opacity-80">
                                        </a>

                                        <div class="tw-absolute tw-inset-0 tw-pointer-events-none tw-text-right">
                                            <div class="tw-inline-block tw-relative tw-m-4">
                                                <img class="tw-absolute tw-inset-0"
                                                    src="{{ asset('images/collection-items-count.svg') }}" />
                                                <div class="tw-text-white tw-text-sm tw-relative px-3">
                                                    {{ $c->items_count }} diel v kolekcii
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <h4 class="tw-mt-4 tw-text-lg tw-text-black tw-font-semibold tw-truncate">
                                        <a href="{{ route('frontend.collection.detail', $c->id) }}">
                                            {{ $c->name }}
                                        </a>
                                    </h4>
                                    <div class="tw-mt-2 tw-text-sm tw-text-gray-600 tw-truncate">
                                        {{ $c->published_at->format('d. m. Y') }} ∙ {{ $c->user->name }}
                                    </div>
                                    <span class="tw-mt-2 tw-inline-block tw-text-sm tw-bg-gray-200 tw-px-3 tw-py-1">
                                        {{ Str::ucfirst($c->type ?? 'kolekcia') }}
                                    </span>
                                </div>
                            @endforeach
                            <div
                                class="tw-w-full md:tw-w-1/3 xl:tw-w-1/4 tw-h-full tw-bg-gray-200 tw-text-center tw-flex tw-flex-col tw-justify-center tw-text-xl tw-text-black tw-font-semibold">
                                <p>Na Webe umenia je ďalších viac<br /> ako {{ $collectionsRemainingCount }} kolekcií</p>
                                <a class="tw-mt-5 tw-underline tw-decoration-gray-300 tw-decoration-3 tw-underline-offset-4 hover:tw-transition-colors hover:tw-decoration-current"
                                    href="{{ route('frontend.collection.index') }}">Zobraziť ďalšie kolekcie</a>
                            </div>

                            <template v-slot:custom-ui="{ next, previous, selectedIndex, slides }">
                                <div
                                    class="tw-absolute tw-inset-y-0 tw-inset-x-4 md:tw--inset-x-7 tw-flex tw-items-center tw-justify-between tw-pointer-events-none">
                                    <button v-on:click="previous" :disabled="selectedIndex === 0"
                                        class="tw-pointer-events-auto tw-rounded-full tw-bg-gray-300 tw-bg-opacity-60 hover:tw-bg-opacity-90 disabled:hover:tw-bg-opacity-60 disabled:tw-opacity-30 tw-transition tw-w-10 tw-h-10 sm:tw-w-14 sm:tw-h-14 tw-flex tw-items-center tw-justify-center tw-text-xl">
                                        <i class="fa icon-arrow-left"></i>
                                    </button>
                                    <button v-on:click="next" :disabled="selectedIndex === slides.length - 1"
                                        class="tw-pointer-events-auto tw-rounded-full tw-bg-gray-300 tw-bg-opacity-60 hover:tw-bg-opacity-90 disabled:hover:tw-bg-opacity-60 disabled:tw-opacity-30 tw-transition tw-w-10 tw-h-10 sm:tw-w-14 sm:tw-h-14 tw-flex tw-items-center tw-justify-center tw-text-xl">
                                        <i class="fa icon-arrow-right"></i>
                                    </button>
                                </div>
                            </template>
                        </flickity>
                    </tab-panel>

                    <tab-panel v-slot="{ active }" class="tw-relative">
                        <flickity :resize-once="active"
                            :options="{ cellAlign: 'left', contain: true, pageDots: false, prevNextButtons: false, freeScroll: true }">
                            @foreach ($articles as $a)
                                <div class="tw-w-72 md:tw-w-1/3 xl:tw-w-1/4 tw-pr-4">
                                    <a href="{{ route('frontend.article.detail', $a->slug) }}">
                                        <img src="{{ $a->getThumbnailImage() }}"
                                            class="tw-object-cover tw-h-48 tw-transition-opacity tw-duration-300 hover:tw-opacity-80">
                                    </a>
                                    <h4 class="tw-mt-4 tw-text-lg tw-text-black tw-font-semibold tw-truncate">
                                        <a href="{{ route('frontend.article.detail', $a->slug) }}">
                                            {{ $a->title }}
                                        </a>
                                    </h4>
                                    <div class="tw-mt-2 tw-text-sm tw-text-gray-600 tw-truncate">
                                        {{ $a->published_date->format('d. m. Y') }} ∙ {{ $a->author }}
                                    </div>
                                    <span class="tw-mt-2 tw-inline-block tw-text-sm tw-bg-gray-200 tw-px-3 tw-py-1">
                                        {{ Str::ucfirst($a->category->name ?? 'článok') }}
                                    </span>
                                </div>
                            @endforeach
                            <div
                                class="tw-w-full md:tw-w-1/3 xl:tw-w-1/4 tw-h-full tw-bg-gray-200 tw-text-center tw-flex tw-flex-col tw-justify-center tw-text-xl tw-text-black tw-font-semibold">
                                <p>Na Webe umenia je ďalších viac<br /> ako {{ $articlesRemainingCount }} článkov</p>
                                <a class="tw-mt-5 tw-underline tw-decoration-gray-300 tw-decoration-3 tw-underline-offset-4 hover:tw-transition-colors hover:tw-decoration-current"
                                    href="{{ route('frontend.article.index') }}">Zobraziť ďalšie články</a>
                            </div>

                            <template v-slot:custom-ui="{ next, previous, selectedIndex, slides }">
                                <div
                                    class="tw-absolute tw-inset-y-0 tw-inset-x-4 md:tw--inset-x-7 tw-flex tw-items-center tw-justify-between tw-pointer-events-none">
                                    <button v-on:click="previous" :disabled="selectedIndex === 0"
                                        class="tw-pointer-events-auto tw-rounded-full tw-bg-gray-300 tw-bg-opacity-60 hover:tw-bg-opacity-90 disabled:hover:tw-bg-opacity-60 disabled:tw-opacity-30 tw-transition tw-w-10 tw-h-10 sm:tw-w-14 sm:tw-h-14 tw-flex tw-items-center tw-justify-center tw-text-xl">
                                        <i class="fa icon-arrow-left"></i>
                                    </button>
                                    <button v-on:click="next" :disabled="selectedIndex === slides.length - 1"
                                        class="tw-pointer-events-auto tw-rounded-full tw-bg-gray-300 tw-bg-opacity-60 hover:tw-bg-opacity-90 disabled:hover:tw-bg-opacity-60 disabled:tw-opacity-30 tw-transition tw-w-10 tw-h-10 sm:tw-w-14 sm:tw-h-14 tw-flex tw-items-center tw-justify-center tw-text-xl">
                                        <i class="fa icon-arrow-right"></i>
                                    </button>
                                </div>
                            </template>
                        </flickity>
                    </tab-panel>
                </div>
            </tabs-controller>
        </div>

        {{-- spacer --}}
        <div class="tw-mb-96"></div>
    </div>
@stop
