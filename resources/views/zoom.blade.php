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
        {{-- @include('components.zoomviewer', [
      'item' => $item,
      'index' => $index,
      'fullIIPImgURLs' => $fullIIPImgURLs,
      ]) --}}




        @php
            $fullIIPImgURLs = [...$fullIIPImgURLs, ...$fullIIPImgURLs, ...$fullIIPImgURLs];
        @endphp

        <zoom-viewer class="tw-h-screen tw-overflow-hidden tw-bg-green-300"
            :tile-sources={{ Js::from($fullIIPImgURLs) }}
            v-slot="{ thumbnailUrls, page, methods }">

            <div class="tw-absolute tw-inset-0 tw-flex">

                {{-- Controls --}}
                <div class="tw-flex tw-w-full tw-flex-col tw-justify-between tw-p-6">

                    {{-- Top buttons --}}
                    <div class="tw-flex tw-justify-between">

                        {{-- Back button --}}
                        <a class="tw-group tw-flex tw-items-center tw-justify-center tw-border tw-border-gray-300 tw-bg-white tw-px-4 tw-text-sm tw-uppercase tw-tracking-widest tw-opacity-70 tw-transition hover:tw-border-gray-400 hover:tw-bg-white hover:tw-text-gray-800 hover:tw-opacity-90"
                            href={{ $item->getUrl() }}>
                            <i
                                class="fa fa-arrow-left tw-mr-2 -tw-ml-1.5 tw-align-[-0.05rem] tw-transition-transform group-hover:tw--translate-x-0.5"></i>
                            {{ trans('general.back') }}
                        </a>

                        {{-- Control buttons --}}
                        <div class="tw-space-x-1.5 tw-text-lg">
                            <button v-on:click="methods.zoomIn"
                                class="tw-h-10 tw-w-10 tw-bg-white tw-opacity-70 tw-transition hover:tw-bg-white hover:tw-text-gray-800 hover:tw-opacity-90 disabled:tw-opacity-30">
                                <i class="fa fa-plus"></i>
                            </button>
                            <button v-on:click="methods.zoomOut"
                                class="tw-h-10 tw-w-10 tw-bg-white tw-opacity-70 tw-transition hover:tw-bg-white hover:tw-text-gray-800 hover:tw-opacity-90 disabled:tw-opacity-30">
                                <i class="fa fa-minus"></i>
                            </button>
                            <button v-on:click="methods.previousPage" :disabled="page === 0"
                                class="tw-h-10 tw-w-10 tw-bg-white tw-opacity-70 tw-transition hover:tw-bg-white hover:tw-text-gray-800 hover:tw-opacity-90 disabled:tw-opacity-30">
                                <i class="fa fa-arrow-up"></i>
                            </button>
                            <button v-on:click="methods.nextPage"
                                :disabled="page === thumbnailUrls.length - 1"
                                class="tw-h-10 tw-w-10 tw-bg-white tw-opacity-70 tw-transition hover:tw-bg-white hover:tw-text-gray-800 hover:tw-opacity-90 disabled:tw-opacity-30">
                                <i class="fa fa-arrow-down"></i>
                            </button>
                        </div>
                    </div>

                    {{-- Page indicator --}}
                    <div class="tw-hidden tw-justify-center md:tw-flex">
                        <div
                            class="tw-bg-white tw-px-4 tw-py-2 tw-opacity-70 tw-transition-opacity hover:tw-opacity-90">
                            @{{ page + 1 }} / @{{ thumbnailUrls.length }}
                        </div>
                    </div>
                </div>

                {{-- Reference strip --}}
                <div v-dragscroll
                    class="tw-flex tw-h-24 tw-flex-shrink-0 tw-overflow-auto tw-bg-white tw-bg-opacity-70 md:tw-h-full md:tw-w-32 md:tw-flex-col">
                    <img v-for="src, index in thumbnailUrls" :key="index" :src="src"
                        v-on:click="methods.setPage(index)"
                        :class="['tw-h-full md:tw-h-auto tw-p-2 md:tw-px-4 tw-border tw-cursor-pointer tw-transition-colors tw-border-sky-300', page === index ? 'tw-border-opacity-100' : 'tw-border-transparent hover:tw-border-sky-300/30']" />
                </div>
            </div>
        </zoom-viewer>
    </div>

    <script type="text/javascript" src="{{ mix('/js/manifest.js') }}"></script>
    <script type="text/javascript" src="{{ mix('/js/vendor.js') }}"></script>
    <script type="text/javascript" src="{{ mix('/js/zoom.js') }}"></script>
</body>

</html>
