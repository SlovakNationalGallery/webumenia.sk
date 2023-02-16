@extends('layouts.master')

@section('content')
    <section class="tailwind-rules">
        <filter-new-items-controller :authors="{{ $authors }}"
            v-slot="{ isExtendedOpen, toggleIsExtendedOpen, handleMultiSelectChange, selectedOptionsAsLabels, handleSortChange, handleColorChange, handleYearRangeChange, handleCheckboxChange, clearFilterSelection, clearAllSelections, removeSelection, query, filters }">
            <div class="tw-relative">
                <div class="tw-bg-gray-200 tw-p-16 md:tw-pb-0">
                    <filter-new-popover-group-controller>
                        <div class="tw-flex tw-gap-x-3 tw-overflow-x-auto md:tw-flex-wrap">
                            <filter-new-popover name="authors">
                                <template #popover-label>
                                    <filter-new-custom-select-popover-label name="authors"
                                        :selected-values="query['authors']">
                                    </filter-new-custom-select-popover-label>
                                </template>
                                <template #body>
                                    <div
                                        class="tw-absolute tw-top-36 tw-z-10 tw-flex tw-h-[30rem] tw-w-[20rem] tw-flex-col tw-items-start tw-border-2 tw-border-gray-800 tw-bg-white tw-p-6">
                                        <filter-new-options filter-name="authors"
                                            placeholder="Napíšte meno autora / autorky"
                                            @change="handleMultiSelectChange"
                                            :selected-values="query['authors']"
                                            :filter="filters['authors']">
                                        </filter-new-options>
                                        <button
                                            class="tw-mb-6 tw-mt-5 tw-border tw-border-gray-300 tw-bg-white tw-px-4 tw-py-1.5 tw-text-sm tw-font-normal hover:tw-border-gray-800"
                                            @click="clearFilterSelection('authors')">
                                            <div class="tw-flex">
                                                <div class="tw-flex tw-items-center tw-pr-2">
                                                    <i class="fa fa-rotate-left"></i>
                                                </div>
                                                <span>zrušiť výber</span>
                                            </div>
                                        </button>
                                    </div>
                                </template>
                            </filter-new-popover>
                            <filter-new-popover v-if="isExtendedOpen" name="color">
                                <template #popover-label>
                                    <span>color</span>
                                </template>
                                <template #body>
                                    <div
                                        class="tw-absolute tw-left-0 tw-top-36 tw-z-10 tw-w-screen tw-px-16">
                                        <div
                                            class="tw-border-2 tw-border-gray-800 tw-bg-white tw-px-8 tw-py-16">
                                            <filter-new-color-slider :color="query['color']"
                                                @change="handleColorChange">
                                            </filter-new-color-slider>
                                        </div>
                                    </div>
                                </template>
                            </filter-new-popover>
                            <filter-new-popover name="yearRange" v-if="isExtendedOpen">
                                <template #popover-label>
                                    <span>year range</span>
                                </template>
                                <template #body>
                                    <div
                                        class="tw-absolute tw-left-0 tw-top-36 tw-z-10 tw-w-screen tw-px-16">
                                        <div class="tw-border-2 tw-border-gray-800 tw-bg-white tw-p-6">
                                            <filter-new-year-slider
                                                :default-from="query['yearRange']['from']"
                                                :default-to="query['yearRange']['to']"
                                                @change="handleYearRangeChange"
                                                :min="filters['yearRange']['min']"
                                                :max="filters['yearRange']['max']"
                                                >
                                            </filter-new-year-slider>
                                        </div>
                                    </div>
                                </template>
                            </filter-new-popover>
                            <filter-show-more class="tw-hidden md:tw-block"
                                :is-extended-open="isExtendedOpen"
                                :toggle-is-extended-open="toggleIsExtendedOpen">
                            </filter-show-more>
                        </div>
                    </filter-new-popover-group-controller>
                    <filter-show-more class="tw-visible tw-pt-4 md:tw-hidden"
                        :is-extended-open="isExtendedOpen"
                        :toggle-is-extended-open="toggleIsExtendedOpen">
                    </filter-show-more>
                </div>
                {{-- <filter-new-mobile-custom-select :is-extended-open="isExtendedOpen"
                    :opened-popover="openedPopover" :handle-multi-select-change="handleMultiSelectChange"
                    :toggle-is-extended-open="toggleIsExtendedOpen"
                    :handle-checkbox-change="handleCheckboxChange"
                    :clear-filter-selection="clearFilterSelection" :toggle-select="toggleSelect"
                    :query="query" :filters="filters" v-if="isExtendedOpen"
                    placeholder="Simple dummy text">
                </filter-new-mobile-custom-select> --}}
                <div
                    class="tw-invisible tw-space-x-3 tw-bg-gray-200 tw-px-16 tw-pt-4 tw-pb-5 md:tw-visible md:tw-flex">
                    <filter-new-custom-checkbox @change="handleCheckboxChange"
                        :checked="Boolean(query['has_image'])" title="Len s obrázkom" name="has_image"
                        id="has_image_desktop">
                    </filter-new-custom-checkbox>
                    <filter-new-custom-checkbox @change="handleCheckboxChange"
                        :checked="Boolean(query['has_iip'])" title="Len so zoomom" name="has_iip"
                        id="has_iip_desktop">
                    </filter-new-custom-checkbox>
                    <filter-new-custom-checkbox @change="handleCheckboxChange"
                        :checked="Boolean(query['is_free'])" title="Len voľné" name="is_free"
                        id="is_free_desktop">
                    </filter-new-custom-checkbox>
                    <filter-new-custom-checkbox @change="handleCheckboxChange"
                        :checked="Boolean(query['has_text'])" title="Len s textom" name="has_text"
                        id="has_text_desktop">
                    </filter-new-custom-checkbox>
                </div>
                <div class="tw-invisible tw-bg-gray-200 tw-px-16 tw-pb-16 md:tw-visible">
                    <filter-new-selected-labels :remove-selection="removeSelection"
                        :clear-all-selections="clearAllSelections"
                        :selected-options-as-labels="selectedOptionsAsLabels" :selected-values="query">
                    </filter-new-selected-labels>
                </div>
                <filter-new-sort :sort="query.sort" :handle-sort-change="handleSortChange" :options="[
                                    {
                                        value: null,
                                        text: 'podľa poslednej zmeny',
                                    },
                                    {
                                        value: 'created_at',
                                        text: 'dátumu pridania',
                                    },
                                    {
                                        value: 'title',
                                        text: 'názvu',
                                    },
                                    {
                                        value: 'author',
                                        text: 'autora',
                                    },
                                    {
                                        value: 'newest',
                                        text: 'dotovania - od najnovšieho',
                                    },
                                    {
                                        value: 'oldest',
                                        text: 'dotovania - od najstaršieho',
                                    },
                                    {
                                        value: 'view_count',
                                        text: 'počtu videní',
                                    },
                                    {
                                        value: 'random',
                                        text: 'náhodného poradia',
                                    },
                                ]">
                </filter-new-sort>
            </div>
        </filter-new-items-controller>
    </section>

@stop
