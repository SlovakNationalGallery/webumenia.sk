@extends('layouts.master')

@section('content')
    <section class="tailwind-rules">
        <filter-new :authors="{{ $authors }}" v-slot="{ isExtendedOpen }">
            <div class="tw-bg-gray-200 tw-p-16 md:tw-pb-0">
                <div class="tw-flex tw-gap-x-3 tw-overflow-x-auto md:tw-flex-wrap">
                    <filter-new-custom-select name="authors"
                        placeholder="Napíšte meno autora / autorky">
                    </filter-new-custom-select>
                    <filter-new-custom-select name="someOtherFilter"
                        placeholder="Napíšte meno autora / autorky">
                    </filter-new-custom-select>
                    <filter-new-custom-select name="authors" v-if="isExtendedOpen"
                        placeholder="Napíšte meno autora / autorky">
                    </filter-new-custom-select>
                    <filter-new-custom-select name="authors" v-if="isExtendedOpen"
                        placeholder="Napíšte meno autora / autorky">
                    </filter-new-custom-select>
                    <filter-new-custom-select name="authors" v-if="isExtendedOpen"
                        placeholder="Napíšte meno autora / autorky">
                    </filter-new-custom-select>
                    <filter-show-more class="tw-hidden md:tw-block">
                    </filter-show-more>
                </div>
                <filter-show-more class="tw-visible tw-pt-4 md:tw-hidden" />
            </div>
            <div
                class="tw-invisible tw-space-x-3 tw-bg-gray-200 tw-px-16 tw-pt-4 tw-pb-5 md:tw-visible md:tw-flex">
                <filter-new-custom-checkbox title="Len s obrázkom" name="has_image">
                </filter-new-custom-checkbox>
                <filter-new-custom-checkbox title="Len so zoomom" name="has_iip">
                </filter-new-custom-checkbox>
                <filter-new-custom-checkbox title="Len voľné" name="is_free">
                </filter-new-custom-checkbox>
                <filter-new-custom-checkbox title="Len s textom" name="has_text">
                </filter-new-custom-checkbox>
            </div>
            <div class="tw-invisible tw-bg-gray-200 tw-px-16 tw-pb-16 md:tw-visible">
                <filter-new-selected-labels>
                </filter-new-selected-labels>
            </div>
            <filter-new-sort />
            <filter-new-mobile-custom-select placeholder="Simple dummy text" />
        </filter-new>
    </section>

@stop
