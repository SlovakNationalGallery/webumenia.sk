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
        @if ($shuffledItems->count() > 0)
            <home.shuffle-orchestrator v-bind:items="{{ Js::from($shuffledItems) }}"
                @if (request()->has('shuffleItemId')) v-bind:initial-item-id="{{ request('shuffleItemId') }}" @endif
                v-slot="orchestrator">
                <div class="tw-relative tw-overflow-hidden">
                    <img v-bind:class="['tw-absolute tw-h-full tw-w-full tw-object-cover tw-transition-all tw-scale-[1.005] tw-duration-700 tw-ease-in', {'tw-blur tw-scale-100': orchestrator.isShuffling }]"
                        v-bind:src="orchestrator.item.img.src"
                        v-bind:srcset="orchestrator.item.img.srcset"
                        sizes="(max-width: 480px) 250vw, (max-width: 640px) 150vw, 100vw" />

                    <img class="tw-invisible tw-absolute tw-h-full tw-w-full tw-scale-[1.005] tw-object-cover"
                        v-bind:src="orchestrator.nextImg.src"
                        v-bind:srcset="orchestrator.nextImg.srcset"
                        sizes="(max-width: 480px) 250vw, (max-width: 640px) 150vw, 100vw" />

                    {{-- Partial bottom overlay (for readability) --}}
                    <div v-cloak
                        class="tw-absolute tw-inset-x-0 tw-bottom-0 tw-h-1/2 tw-bg-gradient-to-b tw-from-black/0 tw-to-black/60 md:tw-h-1/3 md:tw-to-black/50">
                    </div>

                    {{-- Full overlay for transitions --}}
                    <div v-cloak
                        v-bind:class="['tw-absolute tw-inset-0 tw-transition-all tw-bg-black tw-duration-500', orchestrator.isShuffling ? 'tw-opacity-50' : 'tw-opacity-0']">
                    </div>
                    <div
                        class="tw-relative tw-flex tw-flex-col tw-items-center tw-p-6 tw-text-white md:tw-p-8">
                        <h1
                            class="tw-mt-20 tw-text-center tw-text-3xl tw-drop-shadow-[0_1px_2px_rgba(0,0,0,0.7)] md:tw-mt-40 md:tw-text-6xl md:tw-drop-shadow-[0_4px_3px_rgba(0,0,0,0.5)]">
                            {{ trans('home.shuffled_item.tagline') }}
                        </h1>
                        <div class="tw-max-w-5xl tw-text-center">
                            <p
                                class="tw-mt-2 tw-drop-shadow-[0_1px_2px_rgba(0,0,0,0.7)] md:tw-mt-6 md:tw-text-2xl">
                                {!! trans('home.shuffled_item.subtitle') !!}
                            </p>
                        </div>

                        <div class="tw-mt-6 tw-self-stretch md:tw-mt-10" v-cloak>
                            <div
                                class="tw-mx-auto tw-flex tw-w-60 tw-flex-col md:tw-w-auto md:tw-max-w-3xl">
                                <div class="tw-flex tw-flex-col tw-items-stretch">
                                    <div class="tw-justify-items-stretch md:tw-flex">
                                        <div
                                            class="tw-grid tw-grow tw-grid-cols-1 tw-gap-2.5 tw-bg-black/60 tw-p-4 tw-shadow md:tw-grid-cols-3">

                                            <div v-cloak
                                                v-for="filterAttribute, filterAttributeIndex in orchestrator.filter.attributes"
                                                v-bind:key="filterAttributeIndex">

                                                <home.transition-in-place
                                                    v-bind:transition-key="filterAttribute.label + filterAttribute.value">
                                                    <div>
                                                        <div class="tw-text-xs tw-text-white/40">
                                                            @{{ filterAttribute.label }}
                                                        </div>
                                                        <div
                                                            v-bind:class="['tw-whitespace-nowrap tw-transition-opacity tw-text-sm md:tw-text-base tw-text-white', {'tw-opacity-40': orchestrator.isShuffling }]">
                                                            <a :href="filterAttribute.url"
                                                                class="hover:tw-underline">@{{ filterAttribute.value }}</a>
                                                        </div>
                                                    </div>
                                                </home.transition-in-place>
                                            </div>
                                        </div>
                                        <button v-on:click="orchestrator.shuffle"
                                            class="tw-group tw-w-full tw-basis-0 tw-bg-sky-300 tw-px-4 tw-py-2 tw-text-center tw-text-xs tw-text-black tw-transition-colors hover:tw-bg-sky-400 md:tw-px-6 md:tw-text-sm">
                                            <i
                                                class="fa fa-repeat tw-mr-2 tw--ml-4 tw-transition-transform group-hover:tw-rotate-45 md:tw-mx-0"></i>
                                            {{ trans('home.shuffled_item.button_shuffle') }}
                                        </button>
                                    </div>

                                </div>

                                <x-home.button v-bind:href="orchestrator.filter.url"
                                    v-bind:class="['tw-mt-6 tw-self-stretch tw-bg-white tw-text-gray-800 hover:tw-bg-gray-300 tw-text-center md:tw-hidden', {'tw-opacity-0 tw-pointer-events-none': orchestrator.isShuffling}]">
                                    {{ trans('home.shuffled_item.more_like_this') }}
                                </x-home.button>
                            </div>
                        </div>

                        <div class="tw-mt-10 tw-grid tw-self-stretch md:tw-mt-20 md:tw-grid-cols-3">
                            <div
                                v-bind:class="['tw-col-start-2 tw-hidden tw-text-center md:tw-block md:tw-pb-24 tw-transition-all tw-duration-300',
                                    { 'tw-opacity-0 tw-pointer-events-none tw-scale-95': orchestrator.isShuffling }
                                ]">
                                <x-home.button v-bind:href="orchestrator.filter.url"
                                    class="tw-bg-white tw-text-gray-800 hover:tw-bg-gray-300">
                                    {{ trans('home.shuffled_item.more_like_this') }}
                                </x-home.button>
                            </div>
                            <div
                                v-bind:class="['tw-text-white/80 tw-flex tw-flex-col tw-items-center tw-text-xs md:tw-self-end md:tw-items-end md:tw-text-sm tw-transition-opacity tw-duration-500', {'tw-opacity-0 tw-pointer-events-none': orchestrator.isShuffling}]">
                                <div
                                    class="tw-hidden tw-flex-col tw-items-end tw-justify-end tw-gap-x-1 tw-text-right md:tw-flex">
                                    <span>@{{ orchestrator.item.authors }}</span>
                                    <strong>@{{ orchestrator.item.title }}</strong>
                                    <span>@{{ orchestrator.item.dating }}</span>
                                </div>
                                <div class="text-center tw-justify-center md:tw-hidden">
                                    <span>@{{ orchestrator.item.authors }},</span>
                                    <strong>@{{ orchestrator.item.title }}</strong>,
                                    <span>@{{ orchestrator.item.dating }}</span>
                                </div>

                                <div class="tw-mt-2 tw-text-center">
                                    <a v-bind:href="orchestrator.item.url"
                                        class="tw-underline tw-decoration-2 tw-underline-offset-2 tw-transition-colors hover:tw-text-white">
                                        {{ trans('home.shuffled_item.go_to_item') }}
                                    </a>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </home.shuffle-orchestrator>
        @endif
        {{-- Counts blurb --}} <div class="tw-bg-gray-200">
            <div
                class="tw-container tw-mx-auto tw-grid tw-max-w-screen-xl tw-px-6 tw-py-5 tw-text-gray-500 lg:tw-py-10">
                <p class="tw-text-center lg:tw-text-2xl">
                    {{ utrans('home.definition_start') }}
                    <a href="{{ route('frontend.catalog.index') }}"
                        class="tw-font-bold tw-text-gray-800 hover:tw-underline">{{ formatNum($countsBlurb->items_count) }}</a>
                    {{ trans('home.definition_end') }}<br>
                    {{ trans($countsBlurb->start_lang_string) }}
                    <a href="{{ $countsBlurb->url }}"
                        class="tw-font-bold tw-text-gray-800 hover:tw-underline">{{ formatNum($countsBlurb->count) }}</a>
                    {{ trans($countsBlurb->end_lang_string) }}
                </p>
            </div>
        </div>

        @if ($featuredPiece)
            <div class="tw-relative">
                {{ $featuredPiece->image->img()->attributes(['class' => 'tw-object-cover tw-absolute tw-h-full tw-w-full', 'width' => null, 'height' => null]) }}
                <div
                    class="tw-absolute tw-inset-0 tw-bg-gradient-to-r tw-from-black/50 tw-to-black/20 md:tw-to-black/0">
                </div>

                <featured-piece-click-tracker v-bind:id="{{ $featuredPiece->id }}"
                    url="{{ $featuredPiece->url }}" v-slot="{ track }">
                    <div
                        class="tw-container tw-relative tw-mx-auto tw-flex tw-max-w-screen-xl tw-flex-col tw-items-start tw-py-8 tw-px-6 tw-text-white md:tw-py-20">
                        <a href="{{ $featuredPiece->url }}" v-on:click.once.prevent="track"
                            class="tw-absolute tw-inset-0"></a>

                        <h2 class="tw-mt-36 tw-font-semibold tw-drop-shadow md:tw-mt-48 md:tw-text-lg">
                            {{ trans('home.featured_piece.title') }}
                        </h2>
                        <h3 class="tw-mt-4 tw-text-3xl tw-font-semibold tw-drop-shadow md:tw-text-6xl">
                            {{ $featuredPiece->title }}
                        </h3>
                        <div
                            class="tw-prose-invert tw-mt-5 tw-max-w-lg tw-font-serif tw-leading-relaxed tw-drop-shadow prose-p:tw-mb-4 md:tw-text-xl">
                            {!! $featuredPiece->excerpt !!}
                        </div>
                        <x-home.button href="{{ $featuredPiece->url }}"
                            v-on:click.once.prevent="track"
                            class="tw-relative tw-mt-3 tw-bg-white/10 md:tw-mt-6">
                            {{ $featuredPiece->is_collection? trans('home.featured_piece.button_collection'): trans('home.featured_piece.button_article') }}
                        </x-home.button>
                    </div>
                </featured-piece-click-tracker>
            </div>
        @endif

        @if ($featuredArtwork)
            <div class="tw-bg-gray-800 tw-text-white">
                <div
                    class="tw-container tw-mx-auto tw-grid tw-max-w-screen-xl tw-px-6 tw-py-8 md:tw-pt-20 md:tw-pb-16 lg:tw-grid-cols-2 lg:tw-gap-x-14">
                    <div class="tw-text-center lg:tw-order-1">
                        <a href="{{ route('dielo', ['id' => $featuredArtwork->item->id]) }}"
                            class="tw-inline-block">
                            <x-item_image :id="$featuredArtwork->item->id" alt="{{ $featuredArtwork->title }}"
                                class="tw-max-h-80 lg:tw-max-h-[32rem]" />
                        </a>
                    </div>
                    <h2
                        class="tw-mt-6 tw-mb-4 tw-font-semibold lg:tw-col-span-2 lg:tw-mt-0 lg:tw-text-lg">
                        {{ trans('home.featured_artwork.title') }}
                    </h2>
                    <div>
                        <h3 class="tw-text-3xl lg:tw-text-4xl">
                            <a href="{{ route('dielo', ['id' => $featuredArtwork->item->id]) }}">
                                {{ $featuredArtwork->title }}
                            </a>
                        </h3>
                        <div class="tw-mt-2 tw-text-sm lg:tw-mt-3 lg:tw-text-lg">
                            @foreach ($featuredArtwork->author_links as $l)
                                <a href="{{ $l->url }}"
                                    class="hover:tw-underline">{{ $l->label }}</a>
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
                        <div
                            class="tw-prose-invert tw-mt-4 tw-hidden tw-font-serif tw-text-xl tw-leading-relaxed prose-p:tw-mb-4 lg:tw-block">
                            {!! $featuredArtwork->description !!}
                        </div>
                        <x-home.button
                            href="{{ route('dielo', ['id' => $featuredArtwork->item->id]) }}"
                            class="tw-mt-6">{{ trans('home.featured_artwork.button') }}
                        </x-home.button>
                    </div>
                </div>
            </div>
        @endif

        {{-- Latest content --}}
        <div class="tw-container tw-mx-auto tw-max-w-screen-xl tw-px-6 tw-py-8 md:tw-py-16">
            <h2 class="tw-font-semibold md:tw-text-lg">
                {{ trans('home.latest_content.collections.title') }}
            </h2>

            <div class="tw-mt-4 tw-flex tw-items-end tw-justify-between">
                <h3 class="tw-text-2xl tw-font-semibold md:tw-text-4xl">
                    {{ trans('home.latest_content.collections.tab') }}
                </h3>

                <x-home.button href="{{ route('frontend.collection.index') }}"
                    class="tw-hidden hover:tw-bg-gray-300 sm:tw-inline-block">
                    {{ trans('home.latest_content.collections.button') }}
                </x-home.button>
            </div>
            <x-home.carousel class="tw-mt-6" button-container-class="tw-h-48">
                @foreach ($collections as $c)
                    <div class="tw-mr-4 tw-w-72 md:tw-w-[25rem]">
                        <div class="tw-relative tw-bg-sky-400">
                            <a href="{{ route('frontend.collection.detail', $c->id) }}">
                                <img src="{{ $c->getThumbnailImage() }}"
                                    class="tw-h-48 tw-object-cover tw-transition-opacity tw-duration-300 hover:tw-opacity-80">
                            </a>

                            <div
                                class="tw-pointer-events-none tw-absolute tw-right-6 tw-top-6 tw-rounded-sm tw-bg-black/60 tw-px-1.5 tw-text-right tw-text-sm tw-text-white">
                                {{ trans_choice('general.artworks_counted', $c->items_count) }}
                                {{ trans('home.latest_content.collections.item_count_suffix') }}
                            </div>
                        </div>
                        <span class="tw-mt-4 tw-inline-block tw-text-sm tw-text-gray-600">
                            {{ Str::ucfirst($c->type ?? trans('home.latest_content.collections.default_type')) }}
                        </span>
                        <h4
                            class="tw-truncate tw-text-lg tw-font-semibold tw-leading-tight tw-text-black">
                            <a href="{{ route('frontend.collection.detail', $c->id) }}"
                                title="{{ $c->name }}">
                                {{ $c->name }}
                            </a>
                        </h4>
                        <div class="tw-mt-2 tw-truncate tw-text-sm tw-text-gray-600">
                            <a
                                href="{{ route('frontend.collection.index', ['author' => $c->user->name]) }}">{{ $c->user->name }}</a>
                            ∙ {{ $c->published_at->format('d. m. Y') }}
                        </div>
                    </div>
                @endforeach
                <div
                    class="tw-flex tw-h-48 tw-w-72 tw-flex-col tw-justify-center tw-bg-gray-200 tw-text-center tw-text-xl tw-font-semibold tw-text-black md:tw-w-[25rem]">
                    <p>{!! trans('home.latest_content.collections.promo_slide.claim', ['count' => $collectionsRemainingCount]) !!}</p>
                    <a class="tw-mt-5 tw-underline tw-decoration-gray-300 tw-decoration-[3px] tw-underline-offset-4 hover:tw-decoration-current hover:tw-transition-colors"
                        href="{{ route('frontend.collection.index') }}">{{ trans('home.latest_content.collections.promo_slide.link') }}</a>
                </div>
            </x-home.carousel>

            <div class="tw-mt-8 tw-flex tw-items-end tw-justify-between">
                <h3 class="tw-text-2xl tw-font-semibold md:tw-text-4xl">
                    {{ trans('home.latest_content.articles.tab') }}
                </h3>

                <x-home.button href="{{ route('frontend.collection.index') }}"
                    class="tw-hidden hover:tw-bg-gray-300 sm:tw-inline-block">
                    {{ trans('home.latest_content.articles.button') }}
                </x-home.button>
            </div>
            <x-home.carousel class="tw-mt-6" button-container-class="tw-h-48">
                @foreach ($articles as $a)
                    <div class="tw-mr-4 tw-w-72 md:tw-w-[25rem]">
                        <a href="{{ route('frontend.article.detail', $a->slug) }}"
                            class="tw-block tw-bg-sky-400">
                            <img src="{{ $a->getThumbnailImage() }}"
                                class="tw-h-48 tw-object-cover tw-transition-opacity tw-duration-300 hover:tw-opacity-80">
                        </a>
                        <span class="tw-mt-4 tw-inline-block tw-text-sm tw-text-gray-600">
                            {{ Str::ucfirst($c->type ?? trans('home.latest_content.articles.default_type')) }}
                        </span>
                        <h4
                            class="tw-truncate tw-text-lg tw-font-semibold tw-leading-tight tw-text-black">
                            <a href="{{ route('frontend.article.detail', $a->slug) }}"
                                title="{{ $a->title }}">
                                {{ $a->title }}
                            </a>
                        </h4>
                        <div class="tw-mt-2 tw-truncate tw-text-sm tw-text-gray-600">
                            <a
                                href="{{ route('frontend.article.index', ['author' => $a->author]) }}">{{ $a->author }}</a>
                            ∙ {{ $a->published_date->format('d. m. Y') }}
                        </div>
                    </div>
                @endforeach
                <div
                    class="tw-flex tw-h-48 tw-w-72 tw-flex-col tw-justify-center tw-bg-gray-200 tw-text-center tw-text-xl tw-font-semibold tw-text-black md:tw-w-[25rem]">
                    <p>{!! trans('home.latest_content.articles.promo_slide.claim', ['count' => $articlesRemainingCount]) !!}</p>
                    <a class="tw-mt-5 tw-underline tw-decoration-gray-300 tw-decoration-[3px] tw-underline-offset-4 hover:tw-decoration-current hover:tw-transition-colors"
                        href="{{ route('frontend.article.index') }}">{{ trans('home.latest_content.articles.promo_slide.link') }}</a>
                </div>
            </x-home.carousel>
        </div>

        @if ($featuredAuthor)
            <div class="tw-bg-gray-200">
                <div
                    class="tw-container tw-mx-auto tw-max-w-screen-xl tw-gap-x-6 tw-px-6 tw-py-8 md:tw-py-16 lg:tw-grid lg:tw-grid-cols-2">
                    <div class="flex">
                        <img src="{{ $featuredAuthor->getImagePath() }}"
                            class="tw-mt-12 tw-mr-6 tw-hidden tw-h-52 tw-w-52 tw-rounded-full lg:tw-block"
                            alt="{{ $featuredAuthor->formated_name }}">
                        <div>
                            <h2 class="tw-font-semibold lg:tw-text-lg">
                                {{ trans('home.featured_author.title') }}</h2>
                            <h3 class="tw-mt-4 tw-text-3xl tw-font-semibold lg:tw-text-4xl">
                                <a href="{{ route('frontend.author.detail', $featuredAuthor) }}"
                                    class="tw-underline tw-decoration-gray-300 tw-underline-offset-4 tw-transition-colors hover:tw-decoration-current">
                                    {{ $featuredAuthor->formated_name }}
                                </a>
                            </h3>
                            <div class="tw-mt-3 lg:tw-text-lg lg:tw-leading-snug">
                                @foreach ($featuredAuthor->roles as $role)
                                    <a href="{{ route('frontend.author.index', ['role' => $role]) }}"
                                        class="tw-cursor-pointer hover:tw-underline">{{ $role }}</a>{{ $loop->last ? '' : ', ' }}
                                @endforeach
                            </div>
                            <div class="tw-mt-3 tw-text-gray-500 lg:tw-text-lg">
                                {{ Str::replace('.', '. ', $featuredAuthor->birth_date) }}
                                {{ $featuredAuthor->birth_place }}
                                @if ($featuredAuthor->death_year)
                                    &mdash;
                                    {{ Str::replace('.', '. ', $featuredAuthor->death_date) }}
                                    {{ $featuredAuthor->death_place }}
                                @endif
                            </div>
                            <x-home.button
                                href="{{ route('frontend.author.detail', $featuredAuthor) }}"
                                class="tw-mt-6">
                                {!! trans('home.featured_author.button_author', ['count' => $featuredAuthor->items_count]) !!}
                            </x-home.button>
                            <br />
                            <a href="{{ route('frontend.author.index') }}"
                                class="tw-mt-6 tw-hidden tw-cursor-pointer tw-text-sm tw-underline tw-decoration-gray-300 tw-underline-offset-4 tw-transition-colors hover:tw-decoration-current lg:tw-inline-block">
                                {{ trans('home.featured_author.button_more') }}
                            </a>
                        </div>
                    </div>

                    <x-home.carousel class="tw-mt-6 lg:tw-mt-12" images-loaded
                        button-container-class="tw-h-56">
                        @foreach ($featuredAuthorItems as $item)
                            <a href="{{ route('dielo', ['id' => $item]) }}"
                                class="tw-ml-4 tw-w-max first:tw-ml-0">
                                <x-item_image :id="$item->id"
                                    src="{{ route('dielo.nahlad', ['id' => $item->id, 'width' => 70]) }}"
                                    class="tw-h-56"
                                    onload="this.onload=null;this.sizes=Math.ceil(this.getBoundingClientRect().width/window.innerWidth*100)+'vw';"
                                    sizes="1px" />
                            </a>
                        @endforeach
                    </x-home.carousel>
                    <a href="{{ route('frontend.author.index') }}"
                        class="tw-mt-6 tw-inline-block tw-cursor-pointer tw-underline tw-decoration-gray-300 tw-underline-offset-4 tw-transition-colors hover:tw-decoration-current lg:tw-hidden">
                        {{ trans('home.featured_author.button_more') }}
                    </a>
                </div>
            </div>
        @endif

    </div>
@stop
