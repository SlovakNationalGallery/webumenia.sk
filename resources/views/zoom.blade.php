<!DOCTYPE html>
<html lang="{{ LaravelLocalization::getCurrentLocale() }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport"
        content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <meta name="author" content="lab.SNG">
    <meta name="robots" content="noindex, nofollow">

    <title>
        {!! $item->title !!} | {{ trans('zoom.title') }}
    </title>

    <!--  favicons-->
    @include('includes.favicons')
    <!--  /favicons-->
    <!--  Open Graph protocol -->
    @include('includes.og_tags')
    <!--  Open Graph protocol -->

    <link rel="stylesheet"
        href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" type="text/css" href="{{ mix('/css/app-tailwind.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ mix('/css/style.css') }}" />
</head>

<body class="tailwind-rules">
    <div id="app">
        <zoom-viewer v-cloak class="tw-h-screen tw-overflow-hidden tw-outline-none"
            :tile-sources={{ Js::from($fullIIPImgURLs) }} :initial-index="{{ $index }}"
            v-slot=" { thumbnailUrls, page, methods, showControls, sequenceMode }">

            <div
                class="tw-pointer-events-none tw-absolute tw-inset-0 tw-flex tw-flex-col md:tw-flex-row">

                {{-- Controls --}}
                <div
                    class="tw-flex tw-h-full tw-flex-col tw-justify-between tw-p-4 md:tw-w-full md:tw-p-6">

                    {{-- Top buttons --}}
                    <div class="tw-flex tw-justify-between">

                        {{-- Back button --}}
                        <a class="tw-pointer-events-auto tw-flex tw-h-10 tw-items-center tw-justify-center tw-border tw-border-gray-300 tw-bg-white tw-px-4 tw-text-sm tw-uppercase tw-tracking-widest tw-opacity-70 tw-transition hover:tw-border-gray-400 hover:tw-bg-white hover:tw-text-gray-800 hover:tw-opacity-90"
                            href={{ $item->getUrl() }}>
                            <i class="fa fa-arrow-left tw-mr-2 -tw-ml-1.5 tw-align-[-0.05rem]"></i>
                            {{ trans('general.back') }}
                        </a>

                        {{-- Control buttons --}}
                        <Transition enter-class="tw-opacity-0"
                            enter-active-class="tw-transition-opacity tw-duration-300"
                            leave-active-class="tw-transition-opacity tw-duration-1000"
                            leave-to-class="tw-opacity-0">

                            <div v-if="showControls"
                                class="tw-hidden tw-space-x-1.5 tw-text-lg md:tw-block">
                                <button v-on:click="methods.zoomIn"
                                    class="tw-pointer-events-auto tw-h-10 tw-w-10 tw-bg-white tw-opacity-70 tw-transition-opacity hover:tw-opacity-90 active:tw-bg-white disabled:tw-opacity-30">
                                    <i class="fa fa-plus"></i>
                                </button>
                                <button v-on:click="methods.zoomOut"
                                    class="tw-pointer-events-auto tw-h-10 tw-w-10 tw-bg-white tw-opacity-70 tw-transition-opacity hover:tw-opacity-90 active:tw-bg-white disabled:tw-opacity-30">
                                    <i class="fa fa-minus"></i>
                                </button>
                                <button v-if="sequenceMode" v-on:click="methods.previousPage"
                                    :disabled="page === 0"
                                    class="tw-pointer-events-auto tw-h-10 tw-w-10 tw-bg-white tw-opacity-70 tw-transition-opacity hover:tw-opacity-90 active:tw-bg-white disabled:tw-opacity-30">
                                    <i class="fa fa-arrow-up"></i>
                                </button>
                                <button v-if="sequenceMode" v-on:click="methods.nextPage"
                                    :disabled="page === thumbnailUrls.length - 1"
                                    class="tw-pointer-events-auto tw-h-10 tw-w-10 tw-bg-white tw-opacity-70 tw-transition-opacity hover:tw-opacity-90 active:tw-bg-white disabled:tw-opacity-30">
                                    <i class="fa fa-arrow-down"></i>
                                </button>
                            </div>

                        </Transition>
                    </div>

                    <div class="tw-flex tw-justify-end">
                        {{-- Copyright indicator --}}
                        <div
                            class="tw-pointer-events-auto tw-flex tw-cursor-pointer tw-items-center tw-bg-white tw-px-4 tw-py-2.5 tw-text-xs tw-opacity-50 tw-transition-opacity hover:tw-opacity-90 md:tw-text-sm">
                            @if ($item->isFree())
                                <img alt="Creative Commons License"
                                    class="tw-mr-1.5 tw-h-3 tw-opacity-50"
                                    src="/images/license/zero.svg">
                                {{ trans('general.public_domain') }}
                            @else
                                &copy; {{ $item->gallery }}
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Reference strip --}}
                <Transition
                    enter-class="tw--mt-20 tw-translate-y-full md:tw-mt-auto md:tw--ml-32 md:tw-translate-y-0 md:tw-translate-x-full"
                    enter-active-class="tw-transition-all tw-duration-300"
                    leave-active-class="tw-transition-all tw-duration-1000"
                    leave-to-class="tw--mt-20 tw-translate-y-full md:tw-mt-auto md:tw--ml-32 md:tw-translate-y-0 md:tw-translate-x-full">

                    <div v-dragscroll v-show="showControls" v-if="sequenceMode"
                        class="tw-pointer-events-auto tw-flex tw-h-20 tw-flex-shrink-0 tw-overflow-auto tw-bg-white tw-bg-opacity-70 md:tw-h-full md:tw-w-32 md:tw-flex-col">
                        <img v-for="src, index in thumbnailUrls" :key="index" :src="src"
                            v-on:click="methods.setPage(index)"
                            :class="['tw-h-full md:tw-h-auto tw-p-2 md:tw-px-4 tw-border tw-cursor-pointer tw-transition-colors tw-border-sky-300', page === index ? 'tw-border-opacity-100' : 'tw-border-transparent hover:tw-border-sky-300/30']" />
                    </div>
                </Transition>
            </div>

            {{-- Page indicator --}}
            <Transition enter-class="tw-opacity-0"
                enter-active-class="tw-transition-opacity tw-duration-300"
                leave-active-class="tw-transition-opacity tw-duration-1000"
                leave-to-class="tw-opacity-0">

                <div v-if="sequenceMode && showControls"
                    class="tw-pointer-events-none tw-absolute tw-inset-x-0 tw-bottom-6 tw-hidden tw-justify-center md:tw-flex">
                    <div
                        class="tw-pointer-events-auto tw-bg-white tw-px-4 tw-py-2 tw-opacity-70 tw-transition-opacity hover:tw-opacity-90">
                        @{{ page + 1 }} / @{{ thumbnailUrls.length }}
                    </div>
                </div>
            </Transition>
        </zoom-viewer>
    </div>

    <script type="text/javascript" src="{{ mix('/js/manifest.js') }}"></script>
    <script type="text/javascript" src="{{ mix('/js/vendor.js') }}"></script>
    <script type="text/javascript" src="{{ mix('/js/zoom.js') }}"></script>
</body>

</html>
