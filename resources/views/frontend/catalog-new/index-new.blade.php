@extends('layouts.master')

@section('content')
    <section class="tailwind-rules">
        <filter-new-items-controller locale="{{ app()->getLocale() }}"
            v-slot="{ isExtendedOpen, loadMore, isFetchingArtworks, toggleIsExtendedOpen, handleSelectRandomly, handleMultiSelectChange, selectedOptionsAsLabels, handleSortChange, handleColorChange, handleYearRangeChange, handleCheckboxChange, clearFilterSelection, clearAllSelections, removeSelection, query, page,  aggregations, artworks, last_page, artworks_total }">
            <div class="tw-relative">
                <div class="tw-relative tw-min-h-[calc(100vh-14rem)]">
                    <div class="tw-bg-gray-200 tw-py-6 tw-px-4 md:tw-p-16 md:tw-pb-0">
                        {{-- Desktop filter --}}
                        <filter-new-popover.group-controller>
                            <div
                                class="tw-hidden tw-gap-x-3 tw-overflow-x-auto md:tw-flex md:tw-flex-wrap md:tw-overflow-visible">
                                <filter-new-popover name="author">
                                    <template #popover-label>
                                        <filter-new-custom-select-popover-label name="author"
                                            :selected-values="query['author']">
                                        </filter-new-custom-select-popover-label>
                                    </template>
                                    <template #body>
                                        <div
                                            class="tw-absolute tw-z-10 tw-mt-4 tw-flex tw-h-[30rem] tw-w-[20rem] tw-flex-col tw-items-start tw-border-2 tw-border-gray-800 tw-bg-white tw-p-6">
                                            <filter-new-options filter-name="author"
                                                placeholder="Napíšte meno autora / autorky"
                                                @change="handleMultiSelectChange"
                                                :selected-values="query['author']"
                                                :filter="aggregations['author']">
                                            </filter-new-options>
                                            <x-filter.reset_button
                                                class="tw-mb-6 tw-mt-5 tw-flex tw-items-center tw-border tw-border-gray-300 tw-bg-white tw-px-4 tw-py-1.5 tw-text-sm tw-font-normal hover:tw-border-gray-800"
                                                @click="clearFilterSelection('author')">
                                                zrušiť výber
                                            </x-filter.reset_button>
                                        </div>
                                    </template>
                                </filter-new-popover>
                                <filter-new-popover name="work_type">
                                    <template #popover-label>
                                        <filter-new-custom-select-popover-label name="work_type"
                                            :selected-values="query['work_type']">
                                        </filter-new-custom-select-popover-label>
                                    </template>
                                    <template #body>
                                        <div
                                            class="tw-absolute tw-z-10 tw-mt-4 tw-flex tw-h-[30rem] tw-w-[20rem] tw-flex-col tw-items-start tw-border-2 tw-border-gray-800 tw-bg-white tw-p-6">
                                            <filter-new-options filter-name="work_type"
                                                placeholder="Napíšte meno autora / autorky"
                                                @change="handleMultiSelectChange" :selected-values="query['work_type']"
                                                :filter="aggregations['work_type']">
                                            </filter-new-options>
                                            <x-filter.reset_button
                                                class="tw-mb-6 tw-mt-5 tw-flex tw-items-center tw-border tw-border-gray-300 tw-bg-white tw-px-4 tw-py-1.5 tw-text-sm tw-font-normal hover:tw-border-gray-800"
                                                @click="clearFilterSelection('work_type')">
                                                zrušiť výber
                                            </x-filter.reset_button>
                                        </div>
                                    </template>
                                </filter-new-popover>
                                <filter-new-popover name="object_type">
                                    <template #popover-label>
                                        <filter-new-custom-select-popover-label name="object_type"
                                            :selected-values="query['object_type']">
                                        </filter-new-custom-select-popover-label>
                                    </template>
                                    <template #body>
                                        <div
                                            class="tw-absolute tw-z-10 tw-mt-4 tw-flex tw-h-[30rem] tw-w-[20rem] tw-flex-col tw-items-start tw-border-2 tw-border-gray-800 tw-bg-white tw-p-6">
                                            <filter-new-options filter-name="object_type"
                                                placeholder="Napíšte meno autora / autorky"
                                                @change="handleMultiSelectChange" :selected-values="query['object_type']"
                                                :filter="aggregations['object_type']">
                                            </filter-new-options>
                                            <x-filter.reset_button
                                                class="tw-mb-6 tw-mt-5 tw-flex tw-items-center tw-border tw-border-gray-300 tw-bg-white tw-px-4 tw-py-1.5 tw-text-sm tw-font-normal hover:tw-border-gray-800"
                                                @click="clearFilterSelection('object_type')">
                                                zrušiť výber
                                            </x-filter.reset_button>
                                        </div>
                                    </template>
                                </filter-new-popover>
                                <filter-new-popover name="tag">
                                    <template #popover-label>
                                        <filter-new-custom-select-popover-label name="tag"
                                            :selected-values="query['tag']">
                                        </filter-new-custom-select-popover-label>
                                    </template>
                                    <template #body>
                                        <div
                                            class="tw-absolute tw-z-10 tw-mt-4 tw-flex tw-h-[30rem] tw-w-[20rem] tw-flex-col tw-items-start tw-border-2 tw-border-gray-800 tw-bg-white tw-p-6">
                                            <filter-new-options filter-name="tag"
                                                placeholder="Napíšte meno autora / autorky"
                                                @change="handleMultiSelectChange" :selected-values="query['tag']"
                                                :filter="aggregations['tag']">
                                            </filter-new-options>
                                            <x-filter.reset_button
                                                class="tw-mb-6 tw-mt-5 tw-flex tw-items-center tw-border tw-border-gray-300 tw-bg-white tw-px-4 tw-py-1.5 tw-text-sm tw-font-normal hover:tw-border-gray-800"
                                                @click="clearFilterSelection('tag')">
                                                zrušiť výber
                                            </x-filter.reset_button>
                                        </div>
                                    </template>
                                </filter-new-popover>
                                <filter-new-popover name="gallery">
                                    <template #popover-label>
                                        <filter-new-custom-select-popover-label name="gallery"
                                            :selected-values="query['gallery']">
                                        </filter-new-custom-select-popover-label>
                                    </template>
                                    <template #body>
                                        <div
                                            class="tw-absolute tw-z-10 tw-mt-4 tw-flex tw-h-[30rem] tw-w-[20rem] tw-flex-col tw-items-start tw-border-2 tw-border-gray-800 tw-bg-white tw-p-6">
                                            <filter-new-options filter-name="gallery"
                                                placeholder="Napíšte meno autora / autorky"
                                                @change="handleMultiSelectChange" :selected-values="query['gallery']"
                                                :filter="aggregations['gallery']">
                                            </filter-new-options>
                                            <x-filter.reset_button
                                                class="tw-mb-6 tw-mt-5 tw-flex tw-items-center tw-border tw-border-gray-300 tw-bg-white tw-px-4 tw-py-1.5 tw-text-sm tw-font-normal hover:tw-border-gray-800"
                                                @click="clearFilterSelection('gallery')">
                                                zrušiť výber
                                            </x-filter.reset_button>
                                        </div>
                                    </template>
                                </filter-new-popover>
                                <filter-new-popover v-if="isExtendedOpen" name="technique">
                                    <template #popover-label>
                                        <filter-new-custom-select-popover-label name="technique"
                                            :selected-values="query['technique']">
                                        </filter-new-custom-select-popover-label>
                                    </template>
                                    <template #body>
                                        <div
                                            class="tw-absolute tw-z-10 tw-mt-4 tw-flex tw-h-[30rem] tw-w-[20rem] tw-flex-col tw-items-start tw-border-2 tw-border-gray-800 tw-bg-white tw-p-6">
                                            <filter-new-options filter-name="technique"
                                                placeholder="Napíšte meno autora / autorky"
                                                @change="handleMultiSelectChange" :selected-values="query['technique']"
                                                :filter="aggregations['technique']">
                                            </filter-new-options>
                                            <x-filter.reset_button
                                                class="tw-mb-6 tw-mt-5 tw-flex tw-items-center tw-border tw-border-gray-300 tw-bg-white tw-px-4 tw-py-1.5 tw-text-sm tw-font-normal hover:tw-border-gray-800"
                                                @click="clearFilterSelection('technique')">
                                                zrušiť výber
                                            </x-filter.reset_button>
                                        </div>
                                    </template>
                                </filter-new-popover>
                                <filter-new-popover v-if="isExtendedOpen" name="topic">
                                    <template #popover-label>
                                        <filter-new-custom-select-popover-label name="topic"
                                            :selected-values="query['topic']">
                                        </filter-new-custom-select-popover-label>
                                    </template>
                                    <template #body>
                                        <div
                                            class="tw-absolute tw-z-10 tw-mt-4 tw-flex tw-h-[30rem] tw-w-[20rem] tw-flex-col tw-items-start tw-border-2 tw-border-gray-800 tw-bg-white tw-p-6">
                                            <filter-new-options filter-name="topic"
                                                placeholder="Napíšte meno autora / autorky"
                                                @change="handleMultiSelectChange" :selected-values="query['topic']"
                                                :filter="aggregations['topic']">
                                            </filter-new-options>
                                            <x-filter.reset_button
                                                class="tw-mb-6 tw-mt-5 tw-flex tw-items-center tw-border tw-border-gray-300 tw-bg-white tw-px-4 tw-py-1.5 tw-text-sm tw-font-normal hover:tw-border-gray-800"
                                                @click="clearFilterSelection('topic')">
                                                zrušiť výber
                                            </x-filter.reset_button>
                                        </div>
                                    </template>
                                </filter-new-popover>
                                <filter-new-popover v-if="isExtendedOpen" name="medium">
                                    <template #popover-label>
                                        <filter-new-custom-select-popover-label name="medium"
                                            :selected-values="query['medium']">
                                        </filter-new-custom-select-popover-label>
                                    </template>
                                    <template #body>
                                        <div
                                            class="tw-absolute tw-z-10 tw-mt-4 tw-flex tw-h-[30rem] tw-w-[20rem] tw-flex-col tw-items-start tw-border-2 tw-border-gray-800 tw-bg-white tw-p-6">
                                            <filter-new-options filter-name="medium"
                                                placeholder="Napíšte meno autora / autorky"
                                                @change="handleMultiSelectChange" :selected-values="query['medium']"
                                                :filter="aggregations['medium']">
                                            </filter-new-options>
                                            <x-filter.reset_button
                                                class="tw-mb-6 tw-mt-5 tw-flex tw-items-center tw-border tw-border-gray-300 tw-bg-white tw-px-4 tw-py-1.5 tw-text-sm tw-font-normal hover:tw-border-gray-800"
                                                @click="clearFilterSelection('medium')">
                                                zrušiť výber
                                            </x-filter.reset_button>
                                        </div>
                                    </template>
                                </filter-new-popover>
                                <filter-new-popover v-if="isExtendedOpen" name="color">
                                    <template #popover-label>
                                        <div class="tw-flex tw-items-center tw-gap-2 tw-font-semibold">
                                            color<div v-if="query['color']"
                                                class="tw-inline-block tw-h-4 tw-w-4"
                                                :style="{'background': `#${query['color']}`}">
                                            </div>
                                        </div>
                                    </template>
                                    <template #body>
                                        <div
                                            class="tw-absolute tw-left-0 tw-top-36 tw-z-10 tw-w-screen tw-px-16">
                                            <div
                                                class="tw-border-2 tw-border-gray-800 tw-bg-white tw-p-6 tw-pt-4">
                                                <div v-if="query.color" class="tw-flex tw-justify-end">
                                                    <x-filter.reset_button
                                                        @click="handleColorChange(null)"
                                                        class="tw-mb-2 tw-flex tw-items-center tw-border tw-border-gray-300 tw-py-1 tw-px-1.5 tw-text-sm">
                                                        resetovať
                                                    </x-filter.reset_button>
                                                </div>
                                                <filter-new-color-slider :default-color="query['color']"
                                                    @change="handleColorChange">
                                                </filter-new-color-slider>
                                            </div>
                                        </div>
                                    </template>
                                </filter-new-popover>
                                <filter-new-popover name="yearRange" v-if="isExtendedOpen">
                                    <template #popover-label>
                                        <div class="tw-font-sm tw-font-semibold">rok<span
                                                class="tw-ml-2"
                                                v-if="query.yearRange">(@{{ query.yearRange.from }}
                                                - @{{ query.yearRange.to }})</div>
                                    </template>
                                    <template #body>
                                        <div class="tw-absolute tw-z-10 tw-mt-4">
                                            <div
                                                class="tw-w-[28rem] tw-border-2 tw-border-gray-800 tw-bg-white tw-p-6 tw-pt-4">
                                                <div v-if="query.yearRange"
                                                    class="tw-flex tw-justify-end">
                                                    <x-filter.reset_button
                                                        @click="handleYearRangeChange(null)"
                                                        class="tw-mb-3 tw-flex tw-items-center tw-border tw-border-gray-300 tw-py-1 tw-px-1.5 tw-text-sm">
                                                        resetovať
                                                    </x-filter.reset_button>
                                                </div>
                                                <filter-new-year-slider :default-from="Number(query.yearRange?.from)"
                                                    :default-to="Number(query.yearRange?.to)" :min="{{ $yearLimits['min'] ?? 0 }}"
                                                    :max="{{ $yearLimits['max'] ?? now()->year }}" @change="handleYearRangeChange">
                                                </filter-new-year-slider>
                                            </div>
                                        </div>
                                    </template>
                                </filter-new-popover>
                                <filter-show-more class="tw-hidden md:tw-block" :is-extended-open="isExtendedOpen"
                                    :toggle-is-extended-open="toggleIsExtendedOpen">
                                </filter-show-more>
                            </div>
                        </filter-new-popover.group-controller>
                        {{-- Mobile Filter --}}
                        <filter-disclosure-controller v-slot="dc">
                            <div class="tw-relative md:tw-hidden">
                                <div class="tw-flex tw-gap-x-3 tw-overflow-x-auto">
                                    <x-filter.disclosure_button @click="dc.goTo('author')">
                                        <filter-new-custom-select-popover-label name="author"
                                            :selected-values="query['author']">
                                        </filter-new-custom-select-popover-label>
                                    </x-filter.disclosure_button>
                                    <x-filter.disclosure_button @click="dc.goTo('work_type')">
                                        <filter-new-custom-select-popover-label name="work_type"
                                            :selected-values="query['work_type']">
                                        </filter-new-custom-select-popover-label>
                                    </x-filter.disclosure_button>
                                    <x-filter.disclosure_button @click="dc.goTo('object_type')">
                                        <filter-new-custom-select-popover-label name="object_type"
                                            :selected-values="query['object_type']">
                                        </filter-new-custom-select-popover-label>
                                    </x-filter.disclosure_button>
                                    <x-filter.disclosure_button @click="dc.goTo('tag')">
                                        <filter-new-custom-select-popover-label name="tag"
                                            :selected-values="query['tag']">
                                        </filter-new-custom-select-popover-label>
                                    </x-filter.disclosure_button>
                                    <x-filter.disclosure_button @click="dc.goTo('gallery')">
                                        <filter-new-custom-select-popover-label name="gallery"
                                            :selected-values="query['gallery']">
                                        </filter-new-custom-select-popover-label>
                                    </x-filter.disclosure_button>
                                </div>
                                <div class="tw-min-w-max tw-pt-4">
                                    <button @click="dc.goTo('index')"
                                        class="tw-w-full tw-border tw-border-gray-300 tw-py-2 tw-px-3 tw-font-medium hover:tw-border-gray-800">
                                        <div class="tw-flex tw-justify-center">
                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                class="tw-h-6 tw-w-6 tw-fill-current"
                                                viewBox="0 0 256 256">
                                                <path
                                                    d="M40,92H70.06a36,36,0,0,0,67.88,0H216a12,12,0,0,0,0-24H137.94a36,36,0,0,0-67.88,0H40a12,12,0,0,0,0,24Zm64-24A12,12,0,1,1,92,80,12,12,0,0,1,104,68Zm112,96H201.94a36,36,0,0,0-67.88,0H40a12,12,0,0,0,0,24h94.06a36,36,0,0,0,67.88,0H216a12,12,0,0,0,0-24Zm-48,24a12,12,0,1,1,12-12A12,12,0,0,1,168,188Z">
                                                </path>
                                            </svg> <span class="tw-font-semibold">rozšírený
                                                filter</span>
                                        </div>
                                    </button>
                                </div>
                                <x-filter.disclosure_modal v-if="dc.view !== null" @close="dc.close">
                                    @slot('body')
                                        <x-filter.disclosure_view v-if="dc.view === 'index'"
                                            @close="dc.close">
                                            @slot('header')
                                                <span class="tw-text-lg tw-font-semibold">Filter diel</span>
                                            @endslot
                                            @slot('reset_button')
                                                <x-filter.reset_button
                                                    class="tw-mr-3 tw-flex tw-min-w-max tw-items-center tw-border tw-border-gray-300 tw-bg-white tw-px-1.5 tw-py-1 tw-text-sm tw-font-semibold hover:tw-border-gray-800"
                                                    v-if="selectedOptionsAsLabels.length"
                                                    @click="clearAllSelections">
                                                    zrušiť celý výber
                                                </x-filter.reset_button>
                                            @endslot
                                            @slot('body')
                                                <div
                                                    class="tw-flex tw-h-[calc(100vh-15rem)] tw-flex-col tw-overflow-auto">
                                                    <x-filter.disclosure_list_button @click="dc.goTo('author')">
                                                        <filter-new-custom-select-popover-label name="author"
                                                            :selected-values="query['author']">
                                                        </filter-new-custom-select-popover-label>
                                                    </x-filter.disclosure_list_button>
                                                    <x-filter.disclosure_list_button
                                                        @click="dc.goTo('work_type')">
                                                        <filter-new-custom-select-popover-label name="work_type"
                                                            :selected-values="query['work_type']">
                                                        </filter-new-custom-select-popover-label>
                                                    </x-filter.disclosure_list_button>
                                                    <x-filter.disclosure_list_button
                                                        @click="dc.goTo('object_type')">
                                                        <filter-new-custom-select-popover-label
                                                            name="object_type" :selected-values="query['object_type']">
                                                        </filter-new-custom-select-popover-label>
                                                    </x-filter.disclosure_list_button>
                                                    <x-filter.disclosure_list_button @click="dc.goTo('tag')">
                                                        <filter-new-custom-select-popover-label name="tag"
                                                            :selected-values="query['tag']">
                                                        </filter-new-custom-select-popover-label>
                                                    </x-filter.disclosure_list_button>
                                                    <x-filter.disclosure_list_button
                                                        @click="dc.goTo('gallery')">
                                                        <filter-new-custom-select-popover-label name="gallery"
                                                            :selected-values="query['gallery']">
                                                        </filter-new-custom-select-popover-label>
                                                    </x-filter.disclosure_list_button>
                                                    <x-filter.disclosure_list_button
                                                        @click="dc.goTo('technique')">
                                                        <filter-new-custom-select-popover-label name="technique"
                                                            :selected-values="query['technique']">
                                                        </filter-new-custom-select-popover-label>
                                                    </x-filter.disclosure_list_button>
                                                    <x-filter.disclosure_list_button @click="dc.goTo('topic')">
                                                        <filter-new-custom-select-popover-label name="topic"
                                                            :selected-values="query['topic']">
                                                        </filter-new-custom-select-popover-label>
                                                    </x-filter.disclosure_list_button>
                                                    <x-filter.disclosure_list_button @click="dc.goTo('medium')">
                                                        <filter-new-custom-select-popover-label name="medium"
                                                            :selected-values="query['medium']">
                                                        </filter-new-custom-select-popover-label>
                                                    </x-filter.disclosure_list_button>
                                                    <filter-disclosure-inline-list-button>
                                                        <template #header>
                                                            <div class="tw-font-sm tw-font-semibold">
                                                                rok<span class="tw-ml-2"
                                                                    v-if="query.yearRange">(@{{ query.yearRange.from }}
                                                                    - @{{ query.yearRange.to }})</div>
                                                        </template>
                                                        <template #filter-body>
                                                            <filter-new-year-slider :default-from="Number(query.yearRange?.from)"
                                                                :default-to="Number(query.yearRange?.to)" :min="{{ $yearLimits['min'] ?? 0 }}"
                                                                :max="{{ $yearLimits['max'] ?? now()->year }}"
                                                                @change="handleYearRangeChange">
                                                            </filter-new-year-slider>
                                                            <div v-if="query.yearRange"
                                                                class="tw-flex tw-justify-center">
                                                                <x-filter.reset_button
                                                                    @click="handleYearRangeChange(null)"
                                                                    class="tw-mt-2.5 tw-flex tw-items-center tw-border tw-border-gray-300 tw-py-1 tw-px-1.5 tw-text-sm">
                                                                    resetovať
                                                                </x-filter.reset_button>
                                                            </div>
                                                        </template>
                                                    </filter-disclosure-inline-list-button>
                                                    <filter-disclosure-inline-list-button>
                                                        <template #header>
                                                            <div
                                                                class="tw-font-sm tw-flex tw-items-center tw-gap-2 tw-font-semibold">
                                                                color<div v-if="query['color']"
                                                                    class="tw-inline-block tw-h-4 tw-w-4"
                                                                    :style="{'background': `#${query['color']}`}">
                                                                </div>
                                                            </div>
                                                        </template>
                                                        <template #filter-body>
                                                            <filter-new-color-slider :default-color="query['color']"
                                                                @change="handleColorChange">
                                                            </filter-new-color-slider>
                                                            <div v-if="query.color"
                                                                class="tw-flex tw-justify-center">
                                                                <x-filter.reset_button
                                                                    @click="handleColorChange(null)"
                                                                    class="tw-mt-4 tw-flex tw-items-center tw-border tw-border-gray-300 tw-py-1 tw-px-1.5 tw-text-sm">
                                                                    resetovať
                                                                </x-filter.reset_button>
                                                            </div>
                                                        </template>
                                                    </filter-disclosure-inline-list-button>
                                                    <filter-new-custom-checkbox class="tw-pt-2"
                                                        @change="handleCheckboxChange" :checked="Boolean(query['has_image'])"
                                                        title="Len s obrázkom" name="has_image"
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
                                                    <filter-new-custom-checkbox class="tw-pb-2"
                                                        @change="handleCheckboxChange" :checked="Boolean(query['has_text'])"
                                                        title="Len s textom" name="has_text"
                                                        id="has_text_desktop">
                                                    </filter-new-custom-checkbox>
                                                </div>
                                            @endslot
                                        </x-filter.disclosure_view>
                                        <x-filter.disclosure_view v-if="dc.view === 'author'"
                                            @close="dc.close">
                                            @slot('header')
                                                <div class="tw-flex tw-items-center tw-py-0.5">
                                                    <x-filter.view_header_button @click="dc.goTo('index')">
                                                    </x-filter.view_header_button>
                                                    <filter-new-custom-select-popover-label name="author"
                                                        :selected-values="query['author']">
                                                    </filter-new-custom-select-popover-label>
                                                </div>
                                            @endslot
                                            @slot('reset_button')
                                                <x-filter.reset_button
                                                    class="tw-mr-3 tw-flex tw-min-w-max tw-items-center tw-border tw-border-gray-300 tw-bg-white tw-px-1.5 tw-py-1 tw-text-sm tw-font-semibold hover:tw-border-gray-800"
                                                    v-if="query.author.length"
                                                    @click="clearFilterSelection('author')">
                                                    zrušiť výber
                                                </x-filter.reset_button>
                                            @endslot
                                            @slot('body')
                                                <div
                                                    class="tw-inset-x-0 tw-box-border tw-flex tw-min-h-0 tw-flex-1 tw-flex-col tw-overflow-auto">
                                                    <filter-new-options filter-name="author"
                                                        placeholder="Napíšte meno autora / autorky"
                                                        @change="handleMultiSelectChange" :selected-values="query['author']"
                                                        :filter="aggregations['author']">
                                                    </filter-new-options>
                                                </div>
                                            @endslot
                                        </x-filter.disclosure_view>
                                        <x-filter.disclosure_view v-if="dc.view === 'work_type'"
                                            @close="dc.close">
                                            @slot('header')
                                                <div class="tw-flex tw-items-center tw-py-0.5">
                                                    <x-filter.view_header_button @click="dc.goTo('index')">
                                                    </x-filter.view_header_button>
                                                    <filter-new-custom-select-popover-label name="work_type"
                                                        :selected-values="query['work_type']">
                                                    </filter-new-custom-select-popover-label>
                                                </div>
                                            @endslot
                                            @slot('reset_button')
                                                <x-filter.reset_button
                                                    class="tw-mr-3 tw-flex tw-min-w-max tw-items-center tw-border tw-border-gray-300 tw-bg-white tw-px-1.5 tw-py-1 tw-text-sm tw-font-semibold hover:tw-border-gray-800"
                                                    v-if="query.work_type.length"
                                                    @click="clearFilterSelection('work_type')">
                                                    zrušiť výber
                                                </x-filter.reset_button>
                                            @endslot
                                            @slot('body')
                                                <div
                                                    class="tw-inset-x-0 tw-box-border tw-flex tw-min-h-0 tw-flex-1 tw-flex-col tw-overflow-auto">
                                                    <filter-new-options filter-name="work_type"
                                                        placeholder="Napíšte meno autora / autorky"
                                                        @change="handleMultiSelectChange" :selected-values="query['work_type']"
                                                        :filter="aggregations['work_type']">
                                                    </filter-new-options>
                                                </div>
                                            @endslot
                                        </x-filter.disclosure_view>
                                        <x-filter.disclosure_view v-if="dc.view === 'object_type'"
                                            @close="dc.close">
                                            @slot('header')
                                                <div class="tw-flex tw-items-center tw-py-0.5">
                                                    <x-filter.view_header_button @click="dc.goTo('index')">
                                                    </x-filter.view_header_button>
                                                    <filter-new-custom-select-popover-label name="object_type"
                                                        :selected-values="query['object_type']">
                                                    </filter-new-custom-select-popover-label>
                                                </div>
                                            @endslot
                                            @slot('reset_button')
                                                <x-filter.reset_button
                                                    class="tw-mr-3 tw-flex tw-min-w-max tw-items-center tw-border tw-border-gray-300 tw-bg-white tw-px-1.5 tw-py-1 tw-text-sm tw-font-semibold hover:tw-border-gray-800"
                                                    v-if="query.object_type.length"
                                                    @click="clearFilterSelection('object_type')">
                                                    zrušiť výber
                                                </x-filter.reset_button>
                                            @endslot
                                            @slot('body')
                                                <div
                                                    class="tw-inset-x-0 tw-box-border tw-flex tw-min-h-0 tw-flex-1 tw-flex-col tw-overflow-auto">
                                                    <filter-new-options filter-name="object_type"
                                                        placeholder="Napíšte meno autora / autorky"
                                                        @change="handleMultiSelectChange" :selected-values="query['object_type']"
                                                        :filter="aggregations['object_type']">
                                                    </filter-new-options>
                                                </div>
                                            @endslot
                                        </x-filter.disclosure_view>
                                        <x-filter.disclosure_view v-if="dc.view === 'tag'"
                                            @close="dc.close">
                                            @slot('header')
                                                <div class="tw-flex tw-items-center tw-py-0.5">
                                                    <x-filter.view_header_button @click="dc.goTo('index')">
                                                    </x-filter.view_header_button>
                                                    <filter-new-custom-select-popover-label name="tag"
                                                        :selected-values="query['tag']">
                                                    </filter-new-custom-select-popover-label>
                                                </div>
                                            @endslot
                                            @slot('reset_button')
                                                <x-filter.reset_button
                                                    class="tw-mr-3 tw-flex tw-min-w-max tw-items-center tw-border tw-border-gray-300 tw-bg-white tw-px-1.5 tw-py-1 tw-text-sm tw-font-semibold hover:tw-border-gray-800"
                                                    v-if="query.tag.length"
                                                    @click="clearFilterSelection('tag')">
                                                    zrušiť výber
                                                </x-filter.reset_button>
                                            @endslot
                                            @slot('body')
                                                <div
                                                    class="tw-inset-x-0 tw-box-border tw-flex tw-min-h-0 tw-flex-1 tw-flex-col tw-overflow-auto">
                                                    <filter-new-options filter-name="tag"
                                                        placeholder="Napíšte meno autora / autorky"
                                                        @change="handleMultiSelectChange" :selected-values="query['tag']"
                                                        :filter="aggregations['tag']">
                                                    </filter-new-options>
                                                </div>
                                            @endslot
                                        </x-filter.disclosure_view>
                                        <x-filter.disclosure_view v-if="dc.view === 'gallery'"
                                            @close="dc.close">
                                            @slot('header')
                                                <div class="tw-flex tw-items-center tw-py-0.5">
                                                    <x-filter.view_header_button @click="dc.goTo('index')">
                                                    </x-filter.view_header_button>
                                                    <filter-new-custom-select-popover-label name="gallery"
                                                        :selected-values="query['gallery']">
                                                    </filter-new-custom-select-popover-label>
                                                </div>
                                            @endslot
                                            @slot('reset_button')
                                                <x-filter.reset_button
                                                    class="tw-mr-3 tw-flex tw-min-w-max tw-items-center tw-border tw-border-gray-300 tw-bg-white tw-px-1.5 tw-py-1 tw-text-sm tw-font-semibold hover:tw-border-gray-800"
                                                    v-if="query.gallery.length"
                                                    @click="clearFilterSelection('gallery')">
                                                    zrušiť výber
                                                </x-filter.reset_button>
                                            @endslot
                                            @slot('body')
                                                <div
                                                    class="tw-inset-x-0 tw-box-border tw-flex tw-min-h-0 tw-flex-1 tw-flex-col tw-overflow-auto">
                                                    <filter-new-options filter-name="gallery"
                                                        placeholder="Napíšte meno autora / autorky"
                                                        @change="handleMultiSelectChange" :selected-values="query['gallery']"
                                                        :filter="aggregations['gallery']">
                                                    </filter-new-options>
                                                </div>
                                            @endslot
                                        </x-filter.disclosure_view>
                                        <x-filter.disclosure_view v-if="dc.view === 'technique'"
                                            @close="dc.close">
                                            @slot('header')
                                                <div class="tw-flex tw-items-center tw-py-0.5">
                                                    <x-filter.view_header_button @click="dc.goTo('index')">
                                                    </x-filter.view_header_button>
                                                    <filter-new-custom-select-popover-label name="technique"
                                                        :selected-values="query['technique']">
                                                    </filter-new-custom-select-popover-label>
                                                </div>
                                            @endslot
                                            @slot('reset_button')
                                                <x-filter.reset_button
                                                    class="tw-mr-3 tw-flex tw-min-w-max tw-items-center tw-border tw-border-gray-300 tw-bg-white tw-px-1.5 tw-py-1 tw-text-sm tw-font-semibold hover:tw-border-gray-800"
                                                    v-if="query.technique.length"
                                                    @click="clearFilterSelection('technique')">
                                                    zrušiť výber
                                                </x-filter.reset_button>
                                            @endslot
                                            @slot('body')
                                                <div
                                                    class="tw-inset-x-0 tw-box-border tw-flex tw-min-h-0 tw-flex-1 tw-flex-col tw-overflow-auto">
                                                    <filter-new-options filter-name="technique"
                                                        placeholder="Napíšte meno autora / autorky"
                                                        @change="handleMultiSelectChange" :selected-values="query['technique']"
                                                        :filter="aggregations['technique']">
                                                    </filter-new-options>
                                                </div>
                                            @endslot
                                        </x-filter.disclosure_view>
                                        <x-filter.disclosure_view v-if="dc.view === 'topic'"
                                            @close="dc.close">
                                            @slot('header')
                                                <div class="tw-flex tw-items-center tw-py-0.5">
                                                    <x-filter.view_header_button @click="dc.goTo('index')">
                                                    </x-filter.view_header_button>
                                                    <filter-new-custom-select-popover-label name="topic"
                                                        :selected-values="query['topic']">
                                                    </filter-new-custom-select-popover-label>
                                                </div>
                                            @endslot
                                            @slot('reset_button')
                                                <x-filter.reset_button
                                                    class="tw-mr-3 tw-flex tw-min-w-max tw-items-center tw-border tw-border-gray-300 tw-bg-white tw-px-1.5 tw-py-1 tw-text-sm tw-font-semibold hover:tw-border-gray-800"
                                                    v-if="query.topic.length"
                                                    @click="clearFilterSelection('topic')">
                                                    zrušiť výber
                                                </x-filter.reset_button>
                                            @endslot
                                            @slot('body')
                                                <div
                                                    class="tw-inset-x-0 tw-box-border tw-flex tw-min-h-0 tw-flex-1 tw-flex-col tw-overflow-auto">
                                                    <filter-new-options filter-name="topic"
                                                        placeholder="Napíšte meno autora / autorky"
                                                        @change="handleMultiSelectChange" :selected-values="query['topic']"
                                                        :filter="aggregations['topic']">
                                                    </filter-new-options>
                                                </div>
                                            @endslot
                                        </x-filter.disclosure_view>
                                        <x-filter.disclosure_view v-if="dc.view === 'medium'"
                                            @close="dc.close">
                                            @slot('header')
                                                <div class="tw-flex tw-items-center tw-py-0.5">
                                                    <x-filter.view_header_button @click="dc.goTo('index')">
                                                    </x-filter.view_header_button>
                                                    <filter-new-custom-select-popover-label name="medium"
                                                        :selected-values="query['medium']">
                                                    </filter-new-custom-select-popover-label>
                                                </div>
                                            @endslot
                                            @slot('reset_button')
                                                <x-filter.reset_button
                                                    class="tw-mr-3 tw-flex tw-min-w-max tw-items-center tw-border tw-border-gray-300 tw-bg-white tw-px-1.5 tw-py-1 tw-text-sm tw-font-semibold hover:tw-border-gray-800"
                                                    v-if="query.medium.length"
                                                    @click="clearFilterSelection('medium')">
                                                    zrušiť výber
                                                </x-filter.reset_button>
                                            @endslot
                                            @slot('body')
                                                <div
                                                    class="tw-inset-x-0 tw-box-border tw-flex tw-min-h-0 tw-flex-1 tw-flex-col tw-overflow-auto">
                                                    <filter-new-options filter-name="medium"
                                                        placeholder="Napíšte meno autora / autorky"
                                                        @change="handleMultiSelectChange" :selected-values="query['medium']"
                                                        :filter="aggregations['medium']">
                                                    </filter-new-options>
                                                </div>
                                            @endslot
                                        </x-filter.disclosure_view>
                                    @endslot
                                    @slot('footer')
                                        <button class="tw-m-4 tw-w-full tw-bg-sky-300 tw-p-4"
                                            @click="dc.close">
                                            zobraziť výsledky <span
                                                class="tw-font-bold">(@{{ artworks_total }})</span>
                                        </button>
                                    @endslot
                                    </x-filter.disclosure-modal>
                            </div>
                        </filter-disclosure-controller>
                    </div>
                    <div
                        class="tw-hidden tw-space-x-6 tw-bg-gray-200 tw-px-16 tw-pt-4 tw-pb-5 md:tw-flex">
                        <filter-new-custom-checkbox @change="handleCheckboxChange" :checked="Boolean(query['has_image'])"
                            title="Len s obrázkom" name="has_image" id="has_image_desktop">
                        </filter-new-custom-checkbox>
                        <filter-new-custom-checkbox @change="handleCheckboxChange" :checked="Boolean(query['has_iip'])"
                            title="Len so zoomom" name="has_iip" id="has_iip_desktop">
                        </filter-new-custom-checkbox>
                        <filter-new-custom-checkbox @change="handleCheckboxChange" :checked="Boolean(query['is_free'])"
                            title="Len voľné" name="is_free" id="is_free_desktop">
                        </filter-new-custom-checkbox>
                        <filter-new-custom-checkbox @change="handleCheckboxChange" :checked="Boolean(query['has_text'])"
                            title="Len s textom" name="has_text" id="has_text_desktop">
                        </filter-new-custom-checkbox>
                    </div>
                    {{-- Selected labels --}}
                    <div class="tw-hidden tw-bg-gray-200 tw-px-16 tw-pb-16 md:tw-block">
                        <div class="tw-flex tw-space-x-3 tw-overflow-x-auto">
                            <button
                                class="tw-flex tw-min-w-max tw-items-center tw-bg-gray-300 tw-py-1 tw-px-1.5"
                                v-for="option in selectedOptionsAsLabels"
                                @click="removeSelection(option)">
                                <span v-if="option.filterName === 'color'"
                                    class="tw-flex tw-items-center tw-pr-1.5 tw-text-xs tw-font-semibold tw-uppercase">
                                    <div class="tw-mr-1.5 tw-inline-block tw-h-4 tw-w-4"
                                        :style="{ 'background-color': `#${option.value}`, 'border-radius': '30px' }">
                                    </div>
                                    @{{ option.value }}
                                </span>
                                <span v-else-if="option.filterName === 'yearRange'"
                                    class="tw-pr-1.5 tw-text-xs tw-font-semibold">@{{ option.value.from }}
                                    -
                                    @{{ option.value.to }}</span>
                                <span v-else
                                    class="tw-pr-1.5 tw-text-xs tw-font-semibold">@{{ option.value }}</span>
                                <svg xmlns="http://www.w3.org/2000/svg"
                                    class="tw-h-4 tw-w-4 tw-fill-current" viewBox="0 0 256 256">
                                    <path
                                        d="M202.83,197.17a4,4,0,0,1-5.66,5.66L128,133.66,58.83,202.83a4,4,0,0,1-5.66-5.66L122.34,128,53.17,58.83a4,4,0,0,1,5.66-5.66L128,122.34l69.17-69.17a4,4,0,1,1,5.66,5.66L133.66,128Z">
                                    </path>
                                </svg>
                            </button>
                            <x-filter.reset_button v-if="selectedOptionsAsLabels.length"
                                class="tw-flex tw-min-w-max tw-items-center tw-border tw-border-gray-300 tw-bg-white tw-px-1.5 tw-py-1.5 tw-text-sm tw-font-normal tw-leading-none hover:tw-border-gray-800"
                                @click="clearAllSelections">
                                resetovať
                            </x-filter.reset_button>
                        </div>
                    </div>
                    <div class="tw-min-h-screen">
                        <div class="tw-px-4 md:tw-p-16">
                            <div v-if="artworks.length === 0"
                                class="tw-flex tw-w-full tw-flex-col tw-items-center tw-justify-center tw-py-40 tw-text-lg">
                                <div class="tw-w-72">
                                    <lottie-player autoplay loop mode="normal"
                                        src="{{ asset('animations/empty.json') }}">
                                    </lottie-player>
                                </div>
                                <span class="tw-mt-10">Uuups, nič sme nenašli :(</span>
                                <button @click="handleSelectRandomly"
                                    class="tw-font-bold tw-underline tw-underline-offset-8">skús náhodný
                                    výber</a>
                            </div>
                            <div v-else>
                                <div class="tw-px-4 md:tw-p-16">
                                    <filter-new-sort :sort="query . sort" :handle-sort-change="handleSortChange"
                                        :options="[{ value: null, text: 'poslednej zmeny'}, { value: 'created_at', text: 'dátumu pridania',},  { value: 'title', text: 'názvu', }, { value: 'author', text: 'autora', }, { value: 'date_earliest', text: 'datovanie - od najnovšieho', }, { value: 'date_latest', text: 'datovanie - od najstaršieho' }, { value: 'view_count', text: 'počtu videní' }, { value: 'random', text: 'náhodného poradia' }]">
                                        <template #artwork-counter>
                                            <span v-if="artworks_total === 1">Zobrazujem <span
                                                    class="tw-font-bold">1</span>
                                                dielo, zoradené podľa&nbsp</span>
                                            <span v-else-if="artworks_total < 5">Zobrazujem <span
                                                    class="tw-font-bold">@{{ artworks_total }}</span>
                                                diela,
                                                zoradené
                                                podľa&nbsp</span>
                                            <span v-else>Zobrazujem <span
                                                    class="tw-font-bold">@{{ artworks_total }}</span>
                                                diel, zoradených
                                                podľa&nbsp</span>
                                        </template>
                                        <template #random-select>
                                            <span>
                                                . Alebo skús aj
                                                <button @click="handleSelectRandomly"
                                                    class="tw-font-bold tw-underline tw-decoration-2 tw-underline-offset-4">náhodný
                                                    výber</button>
                                            </span>
                                        </template>
                                    </filter-new-sort>
                                </div>
                                {{-- Artwork Masonry --}}
                                <div v-masonry transition-duration="0" item-selector=".item">
                                    <div v-masonry-tile class="item tw-w-full tw-p-2 md:tw-w-1/3"
                                        v-for="artwork in artworks" :key="artwork . id">
                                        <div name="artwork-image">
                                            <catalog.artwork-image-controller v-slot="ic">
                                                <div>
                                                    <a :href="$route('dielo', {id: artwork.id})">
                                                        <img :class="[{'tw-hidden': !ic.isLoaded }, 'tw-w-full']" @load="ic.onImgLoad"
                                                            :src="$route('dielo.nahlad', {id: artwork.id, width: 220})" :srcset="`${$route('dielo.nahlad', {id: artwork.id, width: 600})} 600w, ${$route('dielo.nahlad', {id: artwork.id, width: 220})} 220w, ${$route('dielo.nahlad', {id: artwork.id, width: 300})} 300w, ${$route('dielo.nahlad', {id: artwork.id, width: 600})} 600w, ${$route('dielo.nahlad', {id: artwork.id, width: 800})} 800w`"
                                                            sizes="(max-width: 768px) 250vw, 100vw">
                                                    </a>
                                                    <div :class="[{'tw-hidden': ic.isLoaded }, 'tw-w-full tw-saturate-50 tw-bg-gray-300']" :style="{'aspect-ratio': artwork.content.image_ratio || 1, 'background-color': artwork.content.hsl[0] ? `hsl(${artwork.content.hsl[0].h}, ${artwork.content.hsl[0].s}%, ${artwork.content.hsl[0].l}%)` : undefined}">
                                                    </div>
                                                </div>
                                            </catalog.artwork-image-controller>
                                            <div class="tw-mt-6 tw-flex">
                                                <div class="tw-flex tw-grow tw-flex-col">
                                                    <a :href="$route('dielo', {id: artwork.id})"
                                                        class="tw-pb-2 tw-text-lg tw-font-light tw-italic tw-leading-5">@{{ artwork.content.author[0] }}</a>
                                                    <a :href="$route('dielo', {id: artwork.id})"
                                                        class="tw-pb-2 tw-text-lg tw-font-medium tw-leading-5">@{{ artwork.content.title }}</a>
                                                    <a :href="$route('dielo', {id: artwork.id})"
                                                        class="tw-pb-2 tw-text-base tw-font-normal tw-leading-5">@{{ artwork.content.dating }}</a>
                                                </div>
                                                <div class="tw-flex tw-items-start tw-gap-4">
                                                    <user-collections-store
                                                        v-slot="{ toggleItem, hasItem }">
                                                        <button @click="toggleItem(artwork.id)">
                                                            <svg v-if="hasItem(artwork.id)"
                                                                xmlns="http://www.w3.org/2000/svg"
                                                                class="tw-h-5 tw-w-5 tw-fill-current"
                                                                viewBox="0 0 256 256">
                                                                <path
                                                                    d="M234.5,114.38l-45.1,39.36,13.51,58.6a16,16,0,0,1-23.84,17.34l-51.11-31-51,31a16,16,0,0,1-23.84-17.34L66.61,153.8,21.5,114.38a16,16,0,0,1,9.11-28.06l59.46-5.15,23.21-55.36a15.95,15.95,0,0,1,29.44,0h0L166,81.17l59.44,5.15a16,16,0,0,1,9.11,28.06Z">
                                                                </path>
                                                            </svg>
                                                            <svg v-else
                                                                xmlns="http://www.w3.org/2000/svg"
                                                                class="tw-h-5 tw-w-5 tw-fill-current"
                                                                viewBox="0 0 256 256">
                                                                <path
                                                                    d="M243,96.05a20,20,0,0,0-17.26-13.72l-57-4.93-22.3-53.14h0a20,20,0,0,0-36.82,0L87.29,77.4l-57,4.93A20,20,0,0,0,18.87,117.4l43.32,37.8-13,56.24A20,20,0,0,0,79,233.1l49-29.76,49,29.76a20,20,0,0,0,29.8-21.66l-13-56.24,43.32-37.8A20,20,0,0,0,243,96.05Zm-66.75,42.62a20,20,0,0,0-6.35,19.63l11.39,49.32-42.94-26.08a19.9,19.9,0,0,0-20.7,0L74.71,207.62,86.1,158.3a20,20,0,0,0-6.35-19.63L41.66,105.44,91.8,101.1a19.92,19.92,0,0,0,16.69-12.19L128,42.42l19.51,46.49A19.92,19.92,0,0,0,164.2,101.1l50.14,4.34Z">
                                                                </path>
                                                            </svg>
                                                        </button>
                                                    </user-collections-store>
                                                    <a v-if="artwork.content.has_iip"
                                                        :href="$route('item.zoom', {id: artwork.id})">
                                                        <svg class="tw-h-5 tw-w-5 tw-fill-current"
                                                            xmlns="http://www.w3.org/2000/svg"
                                                            viewBox="0 0 256 256">
                                                            <path
                                                                d="M156,112a12,12,0,0,1-12,12H124v20a12,12,0,0,1-24,0V124H80a12,12,0,0,1,0-24h20V80a12,12,0,0,1,24,0v20h20A12,12,0,0,1,156,112Zm76.49,120.49a12,12,0,0,1-17,0L168,185a92.12,92.12,0,1,1,17-17l47.54,47.53A12,12,0,0,1,232.49,232.49ZM112,180a68,68,0,1,0-68-68A68.08,68.08,0,0,0,112,180Z">
                                                            </path>
                                                        </svg>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <catalog.infinite-scroll v-if="last_page > page" class="tw-mt-10"
                                    :page="page" @loadmore="loadMore" :is-loading="isFetchingArtworks">
                                    <template #loading-message>
                                        <div class="tw-flex tw-justify-center">
                                            <div
                                                class="tw-border tw-border-gray-400 tw-py-2.5 tw-px-8 tw-text-sm hover:tw-border-gray-700">
                                                loading...
                                            </div>
                                        </div>
                                    </template>
                                    <template #load-more-button>
                                        <div class="tw-flex tw-justify-center">
                                            <button v-if="page === 1" @click="loadMore"
                                                class="tw-border tw-border-gray-400 tw-py-2.5 tw-px-8 tw-text-sm hover:tw-border-gray-700">
                                                show more
                                            </button>
                                        </div>
                                    </template>
                                </catalog.infinite-scroll>
                                <div class="tw-mt-10 tw-flex tw-justify-center tw-text-sm" v-else>
                                    That's all Folks!
                                </div>
                            </div>
                        </div>
                    </div>
        </filter-new-items-controller>
    </section>

@stop
