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
                <div
                    class="tw-absolute tw-inset-0 tw-bg-gradient-to-r tw-from-black/50 tw-to-black/20 md:tw-to-black/0">
                </div>
                <div
                    class="tw-container tw-relative tw-mx-auto tw-max-w-screen-xl tw-py-8 tw-px-6 tw-text-white md:tw-py-20">
                    <h2 class="tw-mt-36 tw-font-semibold tw-drop-shadow md:tw-mt-48 md:tw-text-lg">
                        {{ trans('home.featured_piece.title') }}</h2>
                    <h3 class="tw-mt-4 tw-text-3xl tw-font-semibold tw-drop-shadow md:tw-text-6xl">
                        {{ $featuredPiece->title }}
                    </h3>
                    <p
                        class="tw-mt-5 tw-max-w-lg tw-font-serif tw-leading-relaxed tw-drop-shadow md:tw-text-xl">
                        {{ $featuredPiece->excerpt }}
                    </p>
                    <livewire:home.tracked-featured-piece-button :featured-piece="$featuredPiece"
                        class="tw-mt-3 md:tw-mt-6" />
                </div>
            </div>
        @endif

        {{-- Counts blurb --}}
        <div class="tw-bg-gray-200">
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

        @if ($featuredArtwork)
            <div class="tw-bg-gray-800 tw-text-white">
                <div
                    class="tw-container tw-mx-auto tw-grid tw-max-w-screen-xl tw-px-6 tw-py-8 md:tw-pt-20 md:tw-pb-16 lg:tw-grid-cols-2 lg:tw-gap-x-14">
                    <div class="tw-text-center lg:tw-order-1">
                        <a href="{{ route('dielo', ['id' => $featuredArtwork->item->id]) }}"
                            class="tw-inline-block">
                            <x-item_image :id="$featuredArtwork->item->id"
                                alt="{{ $featuredArtwork->title }}"
                                class="tw-max-h-80 lg:tw-max-h-[32rem]" />
                        </a>
                    </div>
                    <h2
                        class="tw-mt-6 tw-mb-4 tw-font-semibold lg:tw-col-span-2 lg:tw-mt-0 lg:tw-text-lg">
                        {{ trans('home.featured_artwork.title') }}
                    </h2>
                    <div>
                        <h3 class="tw-text-3xl lg:tw-text-4xl">{{ $featuredArtwork->title }}</h3>
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
                            class="tw-mt-4 tw-hidden tw-font-serif tw-text-xl tw-leading-relaxed lg:tw-block">
                            {!! $featuredArtwork->description !!}
                        </div>
                        <a href="{{ route('dielo', ['id' => $featuredArtwork->item->id]) }}"
                            class="tw-mt-6 tw-inline-block tw-border tw-border-gray-300 tw-px-4 tw-py-2 tw-text-sm tw-transition tw-duration-300 hover:tw-border-gray-400 hover:tw-bg-white hover:tw-text-gray-800">
                            {{ trans('home.featured_artwork.button') }}
                            <i class="fa icon-arrow-right tw-ml-2"></i>
                        </a>
                    </div>
                </div>
            </div>
        @endif

        {{-- Latest content --}}
        <div class="tw-container tw-mx-auto tw-max-w-screen-xl tw-px-6 tw-py-8 md:tw-py-16">
            <h2 class="tw-font-semibold md:tw-text-lg">Nový obsah</h2>

            <tabs-controller v-cloak v-slot="{ activeIndex }" class="tw-mt-4">
                <div class="tw-flex tw-items-end">
                    <div class="tw-flex tw-grow tw-space-x-4">
                        @foreach ([trans('home.latest_content.collections.tab'), trans('home.latest_content.articles.tab')] as $tab)
                            <tab v-slot="{ active }">
                                <button
                                    :class="['tw-transition-colors tw-font-semibold tw-text-2xl md:tw-text-4xl',
                                        !active && 'tw-text-gray-300 hover:tw-underline tw-underline-offset-[5px] md:tw-underline-offset-8 tw-decoration-[3px]'
                                    ]">{{ $tab }}</button>
                            </tab>
                        @endforeach
                    </div>

                    <transition enter-active-class="tw-transition tw-duration-150"
                        enter-class="tw-opacity-0" enter-to-class="tw-opacity-100"
                        leave-active-class="tw-transition tw-duration-150" leave-class="tw-opacity-100"
                        leave-to-class="tw-opacity-0" mode="out-in">
                        <a key="0" v-if="activeIndex === 0"
                            href="{{ route('frontend.collection.index') }}"
                            class="tw-hidden tw-border tw-border-gray-300 tw-px-4 tw-py-2 tw-text-sm tw-transition tw-duration-300 hover:tw-border-gray-400 hover:tw-bg-gray-300 sm:tw-inline-block">
                            {{ trans('home.latest_content.collections.button') }}
                            <i class="fa icon-arrow-right tw-ml-1 tw-mr-2"></i>
                        </a>
                        <a key="1" v-if="activeIndex === 1"
                            href="{{ route('frontend.article.index') }}"
                            class="tw-hidden tw-border tw-border-gray-300 tw-px-4 tw-py-2 tw-text-sm tw-transition tw-duration-300 hover:tw-border-gray-400 hover:tw-bg-gray-300 sm:tw-inline-block">
                            {{ trans('home.latest_content.articles.button') }}
                            <i class="fa icon-arrow-right tw-ml-1 tw-mr-2"></i>
                        </a>
                    </transition>
                </div>
                <div class="tw-mt-6">
                    <tab-panel v-slot="{ active }" class="tw-relative">
                        <x-home.carousel v-bind:resize-once="active"
                            button-container-class="tw-h-48">
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
                                <p>
                                    {{ trans('home.latest_content.collections.promo_slide.line_1') }}
                                    <br />
                                    {{ trans('home.latest_content.collections.promo_slide.line_2', ['count' => $collectionsRemainingCount]) }}
                                </p>
                                <a class="tw-mt-5 tw-underline tw-decoration-gray-300 tw-decoration-[3px] tw-underline-offset-4 hover:tw-decoration-current hover:tw-transition-colors"
                                    href="{{ route('frontend.collection.index') }}">{{ trans('home.latest_content.collections.promo_slide.link') }}</a>
                            </div>
                        </x-home.carousel>
                    </tab-panel>

                    <tab-panel v-slot="{ active }" class="tw-relative">
                        <x-home.carousel v-bind:resize-once="active"
                            button-container-class="tw-h-48">
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
                                <p>
                                    {{ trans('home.latest_content.articles.promo_slide.line_1') }}
                                    <br />
                                    {{ trans('home.latest_content.articles.promo_slide.line_2', ['count' => $articlesRemainingCount]) }}
                                </p>
                                <a class="tw-mt-5 tw-underline tw-decoration-gray-300 tw-decoration-[3px] tw-underline-offset-4 hover:tw-decoration-current hover:tw-transition-colors"
                                    href="{{ route('frontend.article.index') }}">{{ trans('home.latest_content.articles.promo_slide.link') }}</a>
                            </div>
                        </x-home.carousel>
                    </tab-panel>
                </div>
            </tabs-controller>
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
                            <a href="{{ route('frontend.author.detail', $featuredAuthor) }}"
                                class="tw-mt-6 tw-inline-block tw-border tw-border-gray-300 tw-px-4 tw-py-2 tw-text-sm tw-transition tw-duration-300 hover:tw-border-gray-400 hover:tw-bg-white hover:tw-text-gray-800">
                                {!! trans('home.featured_author.button_author', ['count' => $featuredAuthor->items_count]) !!}
                                <i class="fa icon-arrow-right tw-ml-2"></i>
                            </a>
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
