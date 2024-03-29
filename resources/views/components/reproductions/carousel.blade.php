@php
$options = [
    'cellAlign' => 'left',
    'contain' => true,
    'pageDots' => false,
    'prevNextButtons' => false,
    'freeScroll' => true,
    'imagesLoaded' => true,
];
@endphp

<div class="tw-w-full">
    <flickity v-bind:options={{ Js::from($options) }}
        viewport-class="tw-overflow-visible lg:tw-overflow-hidden"
        {{ $attributes->merge(['class' => 'tw-relative']) }}>
        {{ $slot }}
        <template v-slot:custom-ui="{ next, previous, selectedIndex, slides }">
            <div class="tw-pointer-events-none tw-absolute tw-inset-y-0 tw--inset-x-5">
                <div class="tw-flex tw-h-48 tw-items-center tw-justify-between">
                    <button v-on:click="previous" v-bind:disabled="selectedIndex === 0"
                        class="tw-pointer-events-auto tw-flex tw-h-10 tw-w-10 tw-items-center tw-justify-center tw-rounded-full tw-bg-gray-300 tw-bg-opacity-60 tw-text-xl tw-transition hover:tw-bg-opacity-90 disabled:tw-opacity-30 disabled:hover:tw-bg-opacity-60">
                        <i class="fa icon-arrow-left"></i>
                    </button>
                    <button v-on:click="next" v-bind:disabled="selectedIndex === slides.length - 1"
                        class="tw-pointer-events-auto tw-flex tw-h-10 tw-w-10 tw-items-center tw-justify-center tw-rounded-full tw-bg-gray-300 tw-bg-opacity-60 tw-text-xl tw-transition hover:tw-bg-opacity-90 disabled:tw-opacity-30 disabled:hover:tw-bg-opacity-60">
                        <i class="fa icon-arrow-right"></i>
                    </button>
                </div>
            </div>
        </template>
    </flickity>
</div>
