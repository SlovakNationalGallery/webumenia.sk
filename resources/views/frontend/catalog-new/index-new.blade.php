@extends('layouts.master')

@section('content')
    <section class="tailwind-rules">
        <filter-new :authors="{{ $authors }}">
            <div class="tw-flex tw-space-x-3 tw-overflow-x-auto tw-bg-gray-200 tw-p-16 md:tw-pb-2">
                <div>
                    <filter-new-custom-select :filter-name="'authors'"
                        placeholder="Napíšte meno autora / autorky">
                </div>
                <div>
                    <filter-new-custom-select :filter-name="'someOtherFilter'"
                        placeholder="Napíšte meno autora / autorky">
                </div>
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
            <div
                class="tw-invisible tw-bg-gray-200 tw-px-16 tw-pb-16 md:tw-visible">
                <div>
                    <filter-new-selected-labels />
                </div>
            </div>
            <filter-new-mobile-custom-select placeholder="Simple dummy text" />
        </filter-new>
    </section>

@stop
