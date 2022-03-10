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
                    <h3 class="tw-mt-4 md:tw-mt-6 tw-text-3xl md:tw-text-6xl tw-font-semibold">
                        {{ $featuredPiece->title }}
                    </h3>
                    <p class="tw-mt-5 tw-font-serif md:tw-text-xl tw-max-w-lg tw-leading-relaxed">
                        {{ $featuredPiece->excerpt }}</p>
                    <a href="{{ $featuredPiece->url }}"
                        class="tw-inline-block tw-mt-3 md:tw-mt-6 tw-text-sm tw-border-gray-300 tw-border tw-px-4 tw-py-2 hover:tw-bg-white hover:tw-border-gray-400 hover:tw-text-gray-800 tw-transition tw-duration-300">
                        {{ $featuredPiece->is_collection ? 'Prejsť na kolekciu' : 'Prejsť na článok' }}
                        <i class="fa icon-arrow-right tw-ml-2"></i>
                    </a>
                </div>
            </div>
        @endif
        @if ($featuredArtwork)
            <div class="tw-text-white tw-bg-gray-800 tw-py-8 lg:tw-py-16">
                <div class="tw-container tw-mx-auto tw-px-6 tw-max-w-screen-xl tw-grid lg:tw-grid-cols-2 lg:tw-gap-x-14">
                    <div class="lg:tw-order-1 tw-text-center">
                        <a href="{{ route('dielo', ['id' => $featuredArtwork->item->id]) }}" class="tw-inline-block">
                            <x-item_image :id="$featuredArtwork->item->id" class="tw-max-h-80 lg:tw-max-h-[32rem]" />
                        </a>
                    </div>
                    <h2 class="lg:tw-text-2xl tw-font-semibold tw-mt-6 tw-mb-2 lg:tw-mt-0 lg:tw-mb-6 lg:tw-col-span-2">
                        Dielo dňa
                    </h2>
                    <div>
                        <h3 class="tw-text-3xl lg:tw-text-4xl">{{ $featuredArtwork->title }}</h3>
                        <div class="tw-text-sm tw-mt-2 lg:tw-text-lg lg:tw-mt-3">
                            @foreach ($featuredArtwork->author_links as $l)
                                <a href="{{ $l->url }}" class="hover:tw-underline">{{ $l->label }}</a>
                            @endforeach
                        </div>
                        <div class="tw-text-sm tw-text-gray-500 lg:tw-text-lg">
                            @foreach ($featuredArtwork->metadataLinks as $m)
                                @if ($m->url)
                                    <a href="{{ $m->url }}"
                                        class="hover:tw-underline">{{ $m->label }}</a>{{ $loop->last ? '' : ', ' }}
                                @else
                                    {{ $m->label }}{{ $loop->last ? '' : ', ' }}
                                @endif
                            @endforeach
                        </div>
                        <div class="tw-hidden lg:tw-block tw-mt-4 tw-font-serif tw-text-xl tw-leading-relaxed">
                            {!! $featuredArtwork->description !!}
                        </div>
                        <a href="{{ route('dielo', ['id' => $featuredArtwork->item->id]) }}"
                            class="tw-inline-block tw-mt-6 tw-text-sm tw-border-gray-300 tw-border tw-px-4 tw-py-2 hover:tw-bg-white hover:tw-border-gray-400 hover:tw-text-gray-800 tw-transition tw-duration-300">
                            Viac o diele
                            <i class="fa icon-arrow-right tw-ml-2"></i>
                        </a>
                    </div>
                </div>
            </div>
        @endif

        <div class="tw-container tw-mx-auto tw-px-6 tw-py-6 tw-max-w-screen-xl">
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
        @php
            $featuredAuthor = \App\Authority::find(11436);
        @endphp
        <div class="tw-py-6 lg:tw-py-16 tw-mb-6 tw-bg-gray-200">
            <div class="tw-container tw-mx-auto tw-px-6 tw-max-w-screen-xl lg:tw-grid lg:tw-grid-cols-2 tw-gap-x-6">
                <div class="flex">
                    <img src="{{ $featuredAuthor->getImagePath() }}" class="tw-hidden lg:tw-block tw-mt-14 tw-mr-6 tw-w-52 tw-h-52 tw-rounded-full" alt="{{ $featuredAuthor->formated_name }}">
                    <div>
                        <h2 class="tw-font-semibold lg:tw-text-2xl">Autor týždňa</h2>
                        <h2 class="tw-mt-2 lg:tw-mt-6 tw-font-semibold tw-text-3xl lg:tw-text-4xl">
                            <a href="{{ route('frontend.author.detail', $featuredAuthor) }}"
                                class="tw-cursor-pointer tw-underline tw-underline-offset-4 tw-decoration-gray-300 hover:tw-decoration-current tw-transition-colors">
                                {{ $featuredAuthor->formated_name }}
                            </a>
                        </h2>
                        <div class="tw-mt-3 lg:tw-text-lg">
                            @foreach ($featuredAuthor->roles as $role)
                                <a
                                    href="{{ route('frontend.author.index', ['role' => $role]) }}"
                                    class="tw-cursor-pointer">{{ $role }}</a>{{ $loop->last ? '' : ', ' }}
                            @endforeach
                        </div>
                        <div class="tw-mt-3 tw-text-gray-500 lg:tw-text-lg">
                            {{ $featuredAuthor->birth_date }} {{ $featuredAuthor->birth_place }}
                            @if ($featuredAuthor->death_year)
                                &mdash; {{ $featuredAuthor->death_date }} {{ $featuredAuthor->death_place }}
                            @endif
                        </div>
                        <a href="{{ route('frontend.author.detail', $featuredAuthor) }}"
                            class="tw-inline-block tw-mt-6 tw-text-sm tw-border-gray-300 tw-border tw-px-4 tw-py-2 hover:tw-bg-white hover:tw-border-gray-400 hover:tw-text-gray-800 tw-transition tw-duration-300">
                            Zobraziť <strong>{{ $featuredAuthor->items->count() }} diel</strong> od autora
                            <i class="fa icon-arrow-right tw-ml-2"></i>
                        </a>
                        <br/>
                        <a href="{{ route('frontend.author.index') }}"
                            class="tw-hidden lg:tw-inline-block tw-mt-6 tw-text-sm tw-cursor-pointer tw-underline tw-underline-offset-4 tw-decoration-gray-300 hover:tw-decoration-current tw-transition-colors">
                            zobraziť ďalších autorov
                        </a>
                    </div>
                </div>

                <x-home.carousel class="tw-mt-6 lg:tw-mt-14" images-loaded>
                    @foreach ($featuredAuthor->items as $item)
                        <a href="{{ route('dielo', ['id' => $item]) }}" class="tw-w-max tw-ml-4 first:tw-ml-0">
                            <x-item_image
                                :id="$item->id"
                                src="{{ route('dielo.nahlad', ['id' => $item->id, 'width' => 70]) }}"
                                class="tw-h-56"
                                onload="this.onload=null;this.sizes=Math.ceil(this.getBoundingClientRect().width/window.innerWidth*100)+'vw';"
                                sizes="1px"
                            />
                        </a>
                    @endforeach
                </x-home.carousel>
                <a href="{{ route('frontend.author.index') }}"
                    class="lg:tw-hidden tw-mt-6 tw-inline-block tw-cursor-pointer tw-underline tw-underline-offset-4 tw-decoration-gray-300 hover:tw-decoration-current tw-transition-colors">
                    zobraziť ďalších autorov
                </a>
            </div>
        </div>
    </div>
@stop
