@extends('layouts.master')

@section('content')
    <section class="tailwind-rules">
        <filter-new :authors="{{ $authors }}" v-slot="{ isExtendedOpen }">
            <div class="tw-bg-gray-200 tw-p-16 md:tw-pb-0">
                <div class="tw-flex tw-gap-x-3 tw-overflow-x-auto md:tw-flex-wrap">
                    <div>
                        <filter-new-custom-select :filter-name="'authors'"
                            placeholder="Napíšte meno autora / autorky">
                    </div>
                    <div>
                        <filter-new-custom-select :filter-name="'someOtherFilter'"
                            placeholder="Napíšte meno autora / autorky">
                    </div>
                    <div v-if="isExtendedOpen">
                        <filter-new-custom-select :filter-name="'authors'"
                            placeholder="Napíšte meno autora / autorky">
                    </div>
                    <div v-if="isExtendedOpen">
                        <filter-new-custom-select :filter-name="'authors'"
                            placeholder="Napíšte meno autora / autorky">
                    </div>
                    <div v-if="isExtendedOpen">
                        <filter-new-custom-select :filter-name="'authors'"
                            placeholder="Napíšte meno autora / autorky">
                    </div>
                    <filter-show-more class="tw-hidden md:tw-block"/>
                </div>
                <filter-show-more class="tw-visible md:tw-hidden tw-pt-4"/>
            </div>
            <div
                class="tw-invisible tw-space-x-3 tw-bg-gray-200 tw-px-16 tw-pt-4 tw-pb-5 md:tw-visible md:tw-flex">
                <div>
                    <filter-new-custom-checkbox :checkbox-name="'has_image'" />
                </div>
                <div>
                    <filter-new-custom-checkbox :checkbox-name="'has_iip'" />
                </div>
                <div>
                    <filter-new-custom-checkbox :checkbox-name="'is_free'" />
                </div>
                <div>
                    <filter-new-custom-checkbox :checkbox-name="'has_text'" />
                </div>
            </div>
            <div class="tw-invisible tw-bg-gray-200 tw-px-16 tw-pb-16 md:tw-visible">
                <div>
                    <filter-new-selected-labels />
                </div>
            </div>
            <filter-new-mobile-custom-select placeholder="Simple dummy text" />
        </filter-new>
    </section>

@stop
