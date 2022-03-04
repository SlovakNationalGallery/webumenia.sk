@props(['imagesLoaded' => false])

@php
$options = [
    'cellAlign' => 'left',
    'contain' => true,
    'pageDots' => false,
    'prevNextButtons' => false,
    'freeScroll' => true,
    'imagesLoaded' => $imagesLoaded,
];
@endphp

<flickity
    {{ $attributes->merge([
        'class' => 'tw-relative',
        'v-bind:options' => Illuminate\Support\Js::from($options),
    ]) }}>
    {{ $slot }}
    <template v-slot:custom-ui="{ next, previous, selectedIndex, slides }">
        <div
            class="tw-absolute tw-inset-y-0 tw-inset-x-4 md:tw--inset-x-7 tw-flex tw-items-center tw-justify-between tw-pointer-events-none">
            <button v-on:click="previous" v-bind:disabled="selectedIndex === 0"
                class="tw-pointer-events-auto tw-rounded-full tw-bg-gray-300 tw-bg-opacity-60 hover:tw-bg-opacity-90 disabled:hover:tw-bg-opacity-60 disabled:tw-opacity-30 tw-transition tw-w-10 tw-h-10 sm:tw-w-14 sm:tw-h-14 tw-flex tw-items-center tw-justify-center tw-text-xl">
                <i class="fa icon-arrow-left"></i>
            </button>
            <button v-on:click="next" v-bind:disabled="selectedIndex === slides.length - 1"
                class="tw-pointer-events-auto tw-rounded-full tw-bg-gray-300 tw-bg-opacity-60 hover:tw-bg-opacity-90 disabled:hover:tw-bg-opacity-60 disabled:tw-opacity-30 tw-transition tw-w-10 tw-h-10 sm:tw-w-14 sm:tw-h-14 tw-flex tw-items-center tw-justify-center tw-text-xl">
                <i class="fa icon-arrow-right"></i>
            </button>
        </div>
    </template>
</flickity>
