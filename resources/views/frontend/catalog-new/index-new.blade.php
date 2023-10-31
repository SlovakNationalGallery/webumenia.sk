@extends('layouts.master')

@section('content')
    <section class="tailwind-rules tw-break-keep" v-cloak>
        <filter-new-items-controller locale="{{ app()->getLocale() }}"
            v-slot="{ loadMore, hasError, hasFilterOptions, isFetchingArtworks, handleSelectRandomly, handleMultiSelectChange, selectedOptionsAsLabels, handleSortChange, handleColorChange, handleYearRangeChange, handleCheckboxChange, clearFilterSelection, clearAllSelections, removeSelection, query, page,  aggregations, artworks, last_page, artworks_total }">
            <div class="tw-relative">
                <div class="tw-relative tw-min-h-[calc(100vh-14rem)]">
                    <div class="tw-bg-gray-200">
                        <div
                            class="tw-mx-auto tw-max-w-screen-2xl tw-py-6 tw-pl-4 md:tw-p-6 md:tw-px-8 md:tw-pt-12 md:tw-pb-0">
                            {{-- Desktop filter --}}
                            <filter-new-popover.group-controller>
                                <toggle-controller v-slot="tc">
                                    <div
                                        class="tw-hidden tw-gap-x-3 tw-gap-y-2 tw-overflow-x-auto md:tw-flex md:tw-flex-wrap md:tw-overflow-visible">
                                        <x-filter.search_popover v-if="hasFilterOptions('author')"
                                            v-bind:is-active="query.author && query.author.length > 0"
                                            name="author">
                                            @slot('popover_label')
                                                <filter-new-custom-select-popover-label
                                                    name="{{ trans('item.author') }}"
                                                    v-bind:selected-values="query.author">
                                                </filter-new-custom-select-popover-label>
                                            @endslot
                                            @slot('body')
                                                <div
                                                    class="tw-mt-4 tw-flex tw-h-[30rem] tw-w-[20rem] tw-flex-col tw-items-start tw-border-2 tw-border-gray-800 tw-bg-white tw-p-6">
                                                    <x-filter.search_options
                                                        search-placeholder="{{ utrans('item.filter.placeholder.name_human') }}"
                                                        v-bind:options="aggregations.author"
                                                        v-bind:selected="query.author"
                                                        v-on:change="e => handleMultiSelectChange('author', e)"
                                                        v-on:reset="clearFilterSelection('author')">
                                                        @slot('label')
                                                            <catalog.author-formatter
                                                                v-bind:value="option.value">
                                                            </catalog.author-formatter>
                                                        @endslot
                                                    </x-filter.search_options>
                                                </div>
                                            @endslot
                                        </x-filter.search_popover>
                                        <x-filter.search_popover v-if="hasFilterOptions('work_type')"
                                            v-bind:is-active="query.work_type.length > 0"
                                            name="work_type">
                                            @slot('popover_label')
                                                <filter-new-custom-select-popover-label
                                                    name="{{ trans('item.work_type') }}"
                                                    v-bind:selected-values="query.work_type">
                                                </filter-new-custom-select-popover-label>
                                            @endslot
                                            @slot('body')
                                                <div
                                                    class="tw-mt-4 tw-flex tw-h-[30rem] tw-w-[20rem] tw-flex-col tw-items-start tw-border-2 tw-border-gray-800 tw-bg-white tw-p-6">
                                                    <x-filter.search_options
                                                        search-placeholder="{{ utrans('item.filter.placeholder.term') }}"
                                                        v-bind:options="aggregations.work_type"
                                                        v-bind:selected="query.work_type"
                                                        v-on:change="e => handleMultiSelectChange('work_type', e)"
                                                        v-on:reset="clearFilterSelection('work_type')" />
                                                </div>
                                            @endslot
                                        </x-filter.search_popover>
                                        <x-filter.search_popover v-if="hasFilterOptions('tag')"
                                            v-bind:is-active="query.tag.length > 0" name="tag">
                                            @slot('popover_label')
                                                <filter-new-custom-select-popover-label
                                                    name="{{ trans('item.tag') }}"
                                                    v-bind:selected-values="query.tag">
                                                </filter-new-custom-select-popover-label>
                                            @endslot
                                            @slot('body')
                                                <div
                                                    class="tw-mt-4 tw-flex tw-h-[30rem] tw-w-[20rem] tw-flex-col tw-items-start tw-border-2 tw-border-gray-800 tw-bg-white tw-p-6">
                                                    <x-filter.search_options
                                                        search-placeholder="{{ utrans('item.filter.placeholder.name_object') }}"
                                                        v-bind:options="aggregations.tag"
                                                        v-bind:selected="query.tag"
                                                        v-on:change="e => handleMultiSelectChange('tag', e)"
                                                        v-on:reset="clearFilterSelection('tag')" />
                                                </div>
                                            @endslot
                                        </x-filter.search_popover>
                                        <x-filter.search_popover v-if="hasFilterOptions('topic')"
                                            v-bind:is-active="query.topic.length > 0" name="topic">
                                            @slot('popover_label')
                                                <filter-new-custom-select-popover-label
                                                    name="{{ trans('item.topic') }}"
                                                    v-bind:selected-values="query.topic">
                                                </filter-new-custom-select-popover-label>
                                            @endslot
                                            @slot('body')
                                                <div
                                                    class="tw-mt-4 tw-flex tw-h-[30rem] tw-w-[20rem] tw-flex-col tw-items-start tw-border-2 tw-border-gray-800 tw-bg-white tw-p-6">
                                                    <x-filter.search_options
                                                        search-placeholder="{{ utrans('item.filter.placeholder.name_object') }}"
                                                        v-bind:options="aggregations.topic"
                                                        v-bind:selected="query.topic"
                                                        v-on:change="e => handleMultiSelectChange('topic', e)"
                                                        v-on:reset="clearFilterSelection('topic')" />
                                                </div>
                                            @endslot
                                        </x-filter.search_popover>
                                        <x-filter.search_popover v-if="hasFilterOptions('gallery')"
                                            v-bind:is-active="query.gallery.length > 0" name="gallery">
                                            @slot('popover_label')
                                                <filter-new-custom-select-popover-label
                                                    name="{{ trans('item.gallery') }}"
                                                    v-bind:selected-values="query.gallery">
                                                </filter-new-custom-select-popover-label>
                                            @endslot
                                            @slot('body')
                                                <div
                                                    class="tw-mt-4 tw-flex tw-h-[30rem] tw-w-[20rem] tw-flex-col tw-items-start tw-border-2 tw-border-gray-800 tw-bg-white tw-p-6">
                                                    <x-filter.search_options
                                                        search-placeholder="{{ utrans('item.filter.placeholder.name_object') }}"
                                                        v-bind:options="aggregations.gallery"
                                                        v-bind:selected="query.gallery"
                                                        v-on:change="e => handleMultiSelectChange('gallery', e)"
                                                        v-on:reset="clearFilterSelection('gallery')" />
                                                </div>
                                            @endslot
                                        </x-filter.search_popover>
                                        <x-filter.search_popover
                                            v-if="hasFilterOptions('technique') && tc.isOn"
                                            v-bind:is-active="query.technique.length > 0"
                                            name="technique">
                                            @slot('popover_label')
                                                <filter-new-custom-select-popover-label
                                                    name="{{ trans('item.technique') }}"
                                                    v-bind:selected-values="query.technique">
                                                </filter-new-custom-select-popover-label>
                                            @endslot
                                            @slot('body')
                                                <div
                                                    class="tw-mt-4 tw-flex tw-h-[30rem] tw-w-[20rem] tw-flex-col tw-items-start tw-border-2 tw-border-gray-800 tw-bg-white tw-p-6">
                                                    <x-filter.search_options
                                                        search-placeholder="{{ utrans('item.filter.placeholder.name_object') }}"
                                                        v-bind:options="aggregations.technique"
                                                        v-bind:selected="query.technique"
                                                        v-on:change="e => handleMultiSelectChange('technique', e)"
                                                        v-on:reset="clearFilterSelection('technique')" />
                                                </div>
                                            @endslot
                                        </x-filter.search_popover>
                                        <x-filter.search_popover
                                            v-if="hasFilterOptions('medium') && tc.isOn"
                                            v-bind:is-active="query.medium.length > 0" name="medium">
                                            @slot('popover_label')
                                                <filter-new-custom-select-popover-label
                                                    name="{{ trans('item.medium') }}"
                                                    v-bind:selected-values="query.medium">
                                                </filter-new-custom-select-popover-label>
                                            @endslot
                                            @slot('body')
                                                <div
                                                    class="tw-mt-4 tw-flex tw-h-[30rem] tw-w-[20rem] tw-flex-col tw-items-start tw-border-2 tw-border-gray-800 tw-bg-white tw-p-6">
                                                    <x-filter.search_options
                                                        search-placeholder="{{ utrans('item.filter.placeholder.name_object') }}"
                                                        v-bind:options="aggregations.medium"
                                                        v-bind:selected="query.medium"
                                                        v-on:change="e => handleMultiSelectChange('medium', e)"
                                                        v-on:reset="clearFilterSelection('medium')" />
                                                </div>
                                            @endslot
                                        </x-filter.search_popover>
                                        <x-filter.search_popover v-bind:is-active="query.color"
                                            v-if="tc.isOn" name="color">
                                            @slot('popover_label')
                                                <div
                                                    class="tw-flex tw-items-center tw-gap-2 tw-text-sm tw-font-semibold md:tw-text-base">
                                                    {{ trans('item.filter.color') }}<div
                                                        v-if="query.color"
                                                        class="tw-inline-block tw-h-4 tw-w-4"
                                                        v-bind:style="{'background': `#${query.color}`}">
                                                    </div>
                                                </div>
                                            @endslot
                                            @slot('body')
                                                <div class="tw-mt-4 tw-w-screen tw-px-16">
                                                    <div
                                                        class="tw-border-2 tw-border-gray-800 tw-bg-white tw-p-6 tw-pt-4">
                                                        <div v-if="query.color"
                                                            class="tw-flex tw-justify-end">
                                                            <x-filter.reset_button
                                                                v-on:click="handleColorChange(null)"
                                                                class="tw-mb-2">
                                                                {{ trans('item.filter.clear') }}
                                                            </x-filter.reset_button>
                                                        </div>
                                                        <filter-new-color-slider
                                                            v-bind:default-color="query.color"
                                                            v-on:change="handleColorChange">
                                                        </filter-new-color-slider>
                                                    </div>
                                                </div>
                                            @endslot
                                        </x-filter.search_popover>
                                        <x-filter.search_popover
                                            v-if="hasFilterOptions('object_type') && tc.isOn"
                                            v-bind:is-active="query.object_type.length > 0"
                                            name="object_type">
                                            @slot('popover_label')
                                                <filter-new-custom-select-popover-label
                                                    name="{{ trans('item.object_type') }}"
                                                    v-bind:selected-values="query.object_type">
                                                </filter-new-custom-select-popover-label>
                                            @endslot
                                            @slot('body')
                                                <div
                                                    class="tw-mt-4 tw-flex tw-h-[30rem] tw-w-[20rem] tw-flex-col tw-items-start tw-border-2 tw-border-gray-800 tw-bg-white tw-p-6">
                                                    <x-filter.search_options
                                                        search-placeholder="{{ utrans('item.filter.placeholder.term') }}"
                                                        v-bind:options="aggregations.object_type"
                                                        v-bind:selected="query.object_type"
                                                        v-on:change="e => handleMultiSelectChange('object_type', e)"
                                                        v-on:reset="clearFilterSelection('object_type')" />
                                                </div>
                                            @endslot
                                        </x-filter.search_popover>
                                        <x-filter.search_popover v-bind:is-active="query.yearRange"
                                            v-if="tc.isOn" name="yearRange">
                                            @slot('popover_label')
                                                <div class="tw-text-sm tw-font-semibold md:tw-text-base">
                                                    {{ trans('item.filter.year') }}<span
                                                        class="tw-ml-2"
                                                        v-if="query.yearRange">(@{{ query.yearRange.from }}
                                                        - @{{ query.yearRange.to }})</div>
                                            @endslot
                                            @slot('body')
                                                <div class="tw-mt-4">
                                                    <div
                                                        class="tw-w-[28rem] tw-border-2 tw-border-gray-800 tw-bg-white tw-p-6 tw-pt-4">
                                                        <div v-if="query.yearRange"
                                                            class="tw-flex tw-justify-end">
                                                            <x-filter.reset_button
                                                                v-on:click="handleYearRangeChange(null)"
                                                                class="tw-mb-3">
                                                                {{ trans('item.filter.clear') }}
                                                            </x-filter.reset_button>
                                                        </div>
                                                        <filter-new-year-slider
                                                            v-bind:default-from="query.yearRange?.from"
                                                            v-bind:default-to="query.yearRange?.to"
                                                            v-bind:min="aggregations.date_earliest - 5"
                                                            v-bind:max="Math.min(aggregations.date_latest + 5, new Date().getFullYear())"
                                                            v-on:change="handleYearRangeChange">
                                                        </filter-new-year-slider>
                                                    </div>
                                                </div>
                                            @endslot
                                        </x-filter.search_popover>
                                        <div class="tw-flex tw-gap-1 tw-pb-2">
                                            <div class="tw-border tw-border-transparent">
                                                <button v-on:click="tc.toggle"
                                                    class="tw-flex tw-w-full tw-items-center tw-justify-center tw-border tw-border-gray-300 tw-py-2.5 tw-px-4 tw-text-base tw-font-semibold hover:tw-border-gray-800">
                                                    <div class="tw-flex tw-items-center tw-pr-4">
                                                        <x-icons.minus v-if="tc.isOn"
                                                            class="tw-h-6 tw-w-6 tw-fill-current">
                                                        </x-icons.minus>
                                                        <x-icons.sliders-horizontal
                                                            class="tw-h-6 tw-w-6 tw-fill-current"
                                                            v-else>
                                                        </x-icons.sliders-horizontal>
                                                    </div>
                                                    <span
                                                        v-if="tc.isOn">{{ trans('item.filter.hide_extended') }}</span>
                                                    <span v-else>
                                                        {{ trans('item.filter.show_extended') }}</span>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </toggle-controller>
                            </filter-new-popover.group-controller>
                            {{-- Mobile Filter --}}
                            <filter-disclosure-controller v-slot="dc">
                                <div class="tw-relative md:tw-hidden">
                                    <div class="tw-flex tw-gap-x-3 tw-overflow-x-auto tw-pb-4 tw-pr-4">
                                        <x-filter.disclosure_button v-on:click="dc.goTo('author')">
                                            <filter-new-custom-select-popover-label
                                                name="{{ trans('item.author') }}"
                                                v-bind:selected-values="query.author">
                                            </filter-new-custom-select-popover-label>
                                        </x-filter.disclosure_button>
                                        <x-filter.disclosure_button v-on:click="dc.goTo('work_type')">
                                            <filter-new-custom-select-popover-label
                                                name="{{ trans('item.work_type') }}"
                                                v-bind:selected-values="query.work_type">
                                            </filter-new-custom-select-popover-label>
                                        </x-filter.disclosure_button>
                                        <x-filter.disclosure_button v-on:click="dc.goTo('tag')">
                                            <filter-new-custom-select-popover-label
                                                name="{{ trans('item.tag') }}"
                                                v-bind:selected-values="query.tag">
                                            </filter-new-custom-select-popover-label>
                                        </x-filter.disclosure_button>
                                        <x-filter.disclosure_button v-on:click="dc.goTo('topic')">
                                            <filter-new-custom-select-popover-label
                                                name="{{ trans('item.topic') }}"
                                                v-bind:selected-values="query.topic">
                                            </filter-new-custom-select-popover-label>
                                        </x-filter.disclosure_button>
                                        <x-filter.disclosure_button v-on:click="dc.goTo('gallery')">
                                            <filter-new-custom-select-popover-label
                                                name="{{ trans('item.gallery') }}"
                                                v-bind:selected-values="query.gallery">
                                            </filter-new-custom-select-popover-label>
                                        </x-filter.disclosure_button>
                                    </div>
                                    <div class="tw-flex tw-pr-4">
                                        <button v-on:click="dc.goTo('index')"
                                            class="tw-w-full tw-border tw-border-gray-300 tw-py-2 tw-px-3 tw-font-medium hover:tw-border-gray-800">
                                            <div class="tw-flex tw-justify-center tw-gap-1">
                                                <x-icons.sliders-horizontal
                                                    class="tw-h-6 tw-w-6 tw-fill-current">
                                                </x-icons.sliders-horizontal>
                                                <span
                                                    class="tw-font-semibold">{{ trans('item.filter.extended_filter') }}</span>
                                            </div>
                                        </button>
                                    </div>
                                    <transition enter-from-class="tw-opacity-0"
                                        leave-to-class="tw-opacity-0"
                                        enter-active-class="tw-transition tw-duration-100"
                                        leave-active-class="tw-transition tw-duration-100">
                                        <x-filter.disclosure_modal v-if="dc.view !== null"
                                            v-on:close="dc.close">
                                            @slot('body')
                                                <div class="tw-h-full tw-bg-white">
                                                    <transition mode="out-in"
                                                        enter-from-class="tw-opacity-0"
                                                        leave-to-class="tw-opacity-0"
                                                        enter-active-class="tw-transition tw-duration-100"
                                                        leave-active-class="tw-transition tw-duration-100">
                                                        <x-filter.disclosure_view v-if="dc.view === 'index'"
                                                            v-on:close="dc.close">
                                                            @slot('header')
                                                                <span
                                                                    class="tw-text-lg tw-font-semibold">{{ utrans('item.filter.title') }}</span>
                                                            @endslot
                                                            @slot('reset_button')
                                                                <x-filter.reset_button class="tw-mr-3"
                                                                    v-if="selectedOptionsAsLabels.length"
                                                                    v-on:click="clearAllSelections">
                                                                    {{ trans('item.filter.clear_all') }}
                                                                </x-filter.reset_button>
                                                            @endslot
                                                            @slot('body')
                                                                <div
                                                                    class="tw-flex tw-h-[calc(100vh-15rem)] tw-flex-col tw-overflow-auto">
                                                                    <x-filter.disclosure_list_button
                                                                        v-on:click="dc.goTo('author')">
                                                                        <filter-new-custom-select-popover-label
                                                                            name="{{ trans('item.author') }}"
                                                                            v-bind:selected-values="query.author">
                                                                        </filter-new-custom-select-popover-label>
                                                                    </x-filter.disclosure_list_button>
                                                                    <x-filter.disclosure_list_button
                                                                        v-on:click="dc.goTo('work_type')">
                                                                        <filter-new-custom-select-popover-label
                                                                            name="{{ trans('item.work_type') }}"
                                                                            v-bind:selected-values="query.work_type">
                                                                        </filter-new-custom-select-popover-label>
                                                                    </x-filter.disclosure_list_button>
                                                                    <x-filter.disclosure_list_button
                                                                        v-on:click="dc.goTo('tag')">
                                                                        <filter-new-custom-select-popover-label
                                                                            name="{{ trans('item.tag') }}"
                                                                            v-bind:selected-values="query.tag">
                                                                        </filter-new-custom-select-popover-label>
                                                                    </x-filter.disclosure_list_button>
                                                                    <x-filter.disclosure_list_button
                                                                        v-on:click="dc.goTo('topic')">
                                                                        <filter-new-custom-select-popover-label
                                                                            name="{{ trans('item.topic') }}"
                                                                            v-bind:selected-values="query.topic">
                                                                        </filter-new-custom-select-popover-label>
                                                                    </x-filter.disclosure_list_button>
                                                                    <x-filter.disclosure_list_button
                                                                        v-on:click="dc.goTo('gallery')">
                                                                        <filter-new-custom-select-popover-label
                                                                            name="{{ trans('item.gallery') }}"
                                                                            v-bind:selected-values="query.gallery">
                                                                        </filter-new-custom-select-popover-label>
                                                                    </x-filter.disclosure_list_button>
                                                                    <x-filter.disclosure_list_button
                                                                        v-on:click="dc.goTo('technique')">
                                                                        <filter-new-custom-select-popover-label
                                                                            name="{{ trans('item.technique') }}"
                                                                            v-bind:selected-values="query.technique">
                                                                        </filter-new-custom-select-popover-label>
                                                                    </x-filter.disclosure_list_button>
                                                                    <x-filter.disclosure_list_button
                                                                        v-on:click="dc.goTo('medium')">
                                                                        <filter-new-custom-select-popover-label
                                                                            name="{{ trans('item.medium') }}"
                                                                            v-bind:selected-values="query.medium">
                                                                        </filter-new-custom-select-popover-label>
                                                                    </x-filter.disclosure_list_button>
                                                                    <x-filter.disclosure_list_button
                                                                        v-on:click="dc.goTo('object_type')">
                                                                        <filter-new-custom-select-popover-label
                                                                            name="{{ trans('item.object_type') }}"
                                                                            v-bind:selected-values="query.object_type">
                                                                        </filter-new-custom-select-popover-label>
                                                                    </x-filter.disclosure_list_button>
                                                                    <x-filter.disclosure_inline_list_button>
                                                                        @slot('header')
                                                                            <div
                                                                                class="tw-font-sm tw-font-semibold">
                                                                                {{ trans('item.filter.year') }}<span
                                                                                    class="tw-ml-2"
                                                                                    v-if="query.yearRange">(@{{ query.yearRange.from }}
                                                                                    - @{{ query.yearRange.to }})</div>
                                                                        @endslot
                                                                        @slot('body')
                                                                            <filter-new-year-slider
                                                                                class="tw-px-4"
                                                                                v-bind:default-from="query.yearRange?.from"
                                                                                v-bind:default-to="query.yearRange?.to"
                                                                                v-bind:min="aggregations.date_earliest - 5"
                                                                                v-bind:max="Math.min(aggregations.date_latest + 5, new Date().getFullYear())"
                                                                                v-on:change="handleYearRangeChange">
                                                                            </filter-new-year-slider>
                                                                            <div v-if="query.yearRange"
                                                                                class="tw-flex tw-justify-center">
                                                                                <x-filter.reset_button
                                                                                    v-on:click="handleYearRangeChange(null)"
                                                                                    class="tw-mt-2.5">
                                                                                    {{ trans('item.filter.clear') }}
                                                                                </x-filter.reset_button>
                                                                            </div>
                                                                        @endslot
                                                                    </x-filter.disclosure_inline_list_button>
                                                                    <x-filter.disclosure_inline_list_button>
                                                                        @slot('header')
                                                                            <div
                                                                                class="tw-font-sm tw-flex tw-items-center tw-gap-2 tw-font-semibold">
                                                                                {{ trans('item.filter.color') }}
                                                                                <div v-if="query.color"
                                                                                    class="tw-inline-block tw-h-4 tw-w-4"
                                                                                    v-bind:style="{'background': `#${query.color}`}">
                                                                                </div>
                                                                            </div>
                                                                        @endslot
                                                                        @slot('body')
                                                                            <filter-new-color-slider
                                                                                class="tw-px-4"
                                                                                v-bind:default-color="query.color"
                                                                                v-on:change="handleColorChange">
                                                                            </filter-new-color-slider>
                                                                            <div v-if="query.color"
                                                                                class="tw-flex tw-justify-center">
                                                                                <x-filter.reset_button
                                                                                    v-on:click="handleColorChange(null)"
                                                                                    class="tw-mt-4">
                                                                                    {{ trans('item.filter.clear') }}
                                                                                </x-filter.reset_button>
                                                                            </div>
                                                                        @endslot
                                                                    </x-filter.disclosure_inline_list_button>
                                                                    <filter-new-custom-checkbox
                                                                        class="tw-pt-2"
                                                                        v-on:change="handleCheckboxChange"
                                                                        v-bind:checked="Boolean(query.has_image)"
                                                                        title="{{ utrans('item.filter.has_image') }}"
                                                                        name="has_image" id="has_image_desktop">
                                                                    </filter-new-custom-checkbox>
                                                                    <filter-new-custom-checkbox
                                                                        v-on:change="handleCheckboxChange"
                                                                        v-bind:checked="Boolean(query.has_iip)"
                                                                        title="{{ utrans('item.filter.has_iip') }}"
                                                                        name="has_iip" id="has_iip_desktop">
                                                                    </filter-new-custom-checkbox>
                                                                    <filter-new-custom-checkbox
                                                                        v-on:change="handleCheckboxChange"
                                                                        v-bind:checked="Boolean(query.is_free)"
                                                                        title="{{ utrans('item.filter.is_free') }}"
                                                                        name="is_free" id="is_free_desktop">
                                                                    </filter-new-custom-checkbox>
                                                                    <filter-new-custom-checkbox
                                                                        class="tw-pb-2"
                                                                        v-on:change="handleCheckboxChange"
                                                                        v-bind:checked="Boolean(query.has_text)"
                                                                        title="{{ utrans('item.filter.has_text') }}"
                                                                        name="has_text" id="has_text_desktop">
                                                                    </filter-new-custom-checkbox>
                                                                </div>
                                                            @endslot
                                                        </x-filter.disclosure_view>
                                                        <x-filter.disclosure_view
                                                            v-if="dc.view === 'author'"
                                                            v-on:close="dc.close">
                                                            @slot('header')
                                                                <x-filter.view_header_button
                                                                    v-on:click="dc.goTo('index')">
                                                                    <filter-new-custom-select-popover-label
                                                                        name="{{ trans('item.author') }}"
                                                                        v-bind:selected-values="query.author">
                                                                    </filter-new-custom-select-popover-label>
                                                                </x-filter.view_header_button>
                                                            @endslot
                                                            @slot('reset_button')
                                                                <x-filter.reset_button class="tw-mr-3"
                                                                    v-if="query.author.length"
                                                                    v-on:click="clearFilterSelection('author')">
                                                                    {{ trans('item.filter.clear') }}
                                                                </x-filter.reset_button>
                                                            @endslot
                                                            @slot('body')
                                                                <div
                                                                    class="tw-inset-x-0 tw-box-border tw-flex tw-min-h-0 tw-flex-1 tw-flex-col tw-overflow-auto">
                                                                    <x-filter.search_options
                                                                        search-placeholder="{{ utrans('item.filter.placeholder.name_human') }}"
                                                                        v-bind:options="aggregations.author"
                                                                        v-bind:selected="query.author"
                                                                        v-on:change="e => handleMultiSelectChange('author', e)"
                                                                        v-on:reset="clearFilterSelection('author')">
                                                                        @slot('label')
                                                                            <catalog.author-formatter
                                                                                v-bind:value="option.value">
                                                                            </catalog.author-formatter>
                                                                        @endslot
                                                                    </x-filter.search_options>
                                                                </div>
                                                            @endslot
                                                        </x-filter.disclosure_view>
                                                        <x-filter.disclosure_view
                                                            v-if="dc.view === 'work_type'"
                                                            v-on:close="dc.close">
                                                            @slot('header')
                                                                <x-filter.view_header_button
                                                                    v-on:click="dc.goTo('index')">
                                                                    <filter-new-custom-select-popover-label
                                                                        name="{{ trans('item.work_type') }}"
                                                                        v-bind:selected-values="query.work_type">
                                                                    </filter-new-custom-select-popover-label>
                                                                </x-filter.view_header_button>
                                                            @endslot
                                                            @slot('reset_button')
                                                                <x-filter.reset_button class="tw-mr-3"
                                                                    v-if="query.work_type.length"
                                                                    v-on:click="clearFilterSelection('work_type')">
                                                                    {{ trans('item.filter.clear') }}
                                                                </x-filter.reset_button>
                                                            @endslot
                                                            @slot('body')
                                                                <div
                                                                    class="tw-inset-x-0 tw-box-border tw-flex tw-min-h-0 tw-flex-1 tw-flex-col tw-overflow-auto">
                                                                    <x-filter.search_options
                                                                        search-placeholder="{{ utrans('item.filter.placeholder.term') }}"
                                                                        v-bind:options="aggregations.work_type"
                                                                        v-bind:selected="query.work_type"
                                                                        v-on:change="e => handleMultiSelectChange('work_type', e)"
                                                                        v-on:reset="clearFilterSelection('work_type')">
                                                                    </x-filter.search_options>
                                                                </div>
                                                            @endslot
                                                        </x-filter.disclosure_view>
                                                        <x-filter.disclosure_view
                                                            v-if="dc.view === 'object_type'"
                                                            v-on:close="dc.close">
                                                            @slot('header')
                                                                <x-filter.view_header_button
                                                                    v-on:click="dc.goTo('index')">
                                                                    <filter-new-custom-select-popover-label
                                                                        name="object_type"
                                                                        v-bind:selected-values="query.object_type">
                                                                    </filter-new-custom-select-popover-label>
                                                                </x-filter.view_header_button>
                                                            @endslot
                                                            @slot('reset_button')
                                                                <x-filter.reset_button class="tw-mr-3"
                                                                    v-if="query.object_type.length"
                                                                    v-on:click="clearFilterSelection('object_type')">
                                                                    {{ trans('item.filter.clear') }}
                                                                </x-filter.reset_button>
                                                            @endslot
                                                            @slot('body')
                                                                <div
                                                                    class="tw-inset-x-0 tw-box-border tw-flex tw-min-h-0 tw-flex-1 tw-flex-col tw-overflow-auto">
                                                                    <x-filter.search_options
                                                                        search-placeholder="{{ utrans('item.filter.placeholder.term') }}"
                                                                        v-bind:options="aggregations.object_type"
                                                                        v-bind:selected="query.object_type"
                                                                        v-on:change="e => handleMultiSelectChange('object_type', e)"
                                                                        v-on:reset="clearFilterSelection('object_type')">
                                                                    </x-filter.search_options>
                                                                </div>
                                                            @endslot
                                                        </x-filter.disclosure_view>
                                                        <x-filter.disclosure_view v-if="dc.view === 'tag'"
                                                            v-on:close="dc.close">
                                                            @slot('header')
                                                                <x-filter.view_header_button
                                                                    v-on:click="dc.goTo('index')">
                                                                    <filter-new-custom-select-popover-label
                                                                        name="{{ trans('item.tag') }}"
                                                                        v-bind:selected-values="query.tag">
                                                                    </filter-new-custom-select-popover-label>
                                                                </x-filter.view_header_button>
                                                            @endslot
                                                            @slot('reset_button')
                                                                <x-filter.reset_button class="tw-mr-3"
                                                                    v-if="query.tag.length"
                                                                    v-on:click="clearFilterSelection('tag')">
                                                                    {{ trans('item.filter.clear') }}
                                                                </x-filter.reset_button>
                                                            @endslot
                                                            @slot('body')
                                                                <div
                                                                    class="tw-inset-x-0 tw-box-border tw-flex tw-min-h-0 tw-flex-1 tw-flex-col tw-overflow-auto">
                                                                    <x-filter.search_options
                                                                        search-placeholder="{{ utrans('item.filter.placeholder.name_object') }}"
                                                                        v-bind:options="aggregations.tag"
                                                                        v-bind:selected="query.tag"
                                                                        v-on:change="e => handleMultiSelectChange('tag', e)"
                                                                        v-on:reset="clearFilterSelection('tag')">
                                                                    </x-filter.search_options>
                                                                </div>
                                                            @endslot
                                                        </x-filter.disclosure_view>
                                                        <x-filter.disclosure_view
                                                            v-if="dc.view === 'gallery'"
                                                            v-on:close="dc.close">
                                                            @slot('header')
                                                                <x-filter.view_header_button
                                                                    v-on:click="dc.goTo('index')">
                                                                    <filter-new-custom-select-popover-label
                                                                        name="{{ trans('item.gallery') }}"
                                                                        v-bind:selected-values="query.gallery">
                                                                    </filter-new-custom-select-popover-label>
                                                                </x-filter.view_header_button>
                                                            @endslot
                                                            @slot('reset_button')
                                                                <x-filter.reset_button class="tw-mr-3"
                                                                    v-if="query.gallery.length"
                                                                    v-on:click="clearFilterSelection('gallery')">
                                                                    {{ trans('item.filter.clear') }}
                                                                </x-filter.reset_button>
                                                            @endslot
                                                            @slot('body')
                                                                <x-filter.search_options
                                                                    search-placeholder="{{ utrans('item.filter.placeholder.name_object') }}"
                                                                    v-bind:options="aggregations.gallery"
                                                                    v-bind:selected="query.gallery"
                                                                    v-on:change="e => handleMultiSelectChange('gallery', e)"
                                                                    v-on:reset="clearFilterSelection('gallery')">
                                                                </x-filter.search_options>
                                                            @endslot
                                                        </x-filter.disclosure_view>
                                                        <x-filter.disclosure_view
                                                            v-if="dc.view === 'technique'"
                                                            v-on:close="dc.close">
                                                            @slot('header')
                                                                <x-filter.view_header_button
                                                                    v-on:click="dc.goTo('index')">
                                                                    <filter-new-custom-select-popover-label
                                                                        name="{{ trans('item.technique') }}"
                                                                        v-bind:selected-values="query.technique">
                                                                    </filter-new-custom-select-popover-label>
                                                                </x-filter.view_header_button>
                                                            @endslot
                                                            @slot('reset_button')
                                                                <x-filter.reset_button class="tw-mr-3"
                                                                    v-if="query.technique.length"
                                                                    v-on:click="clearFilterSelection('technique')">
                                                                    {{ trans('item.filter.clear') }}
                                                                </x-filter.reset_button>
                                                            @endslot
                                                            @slot('body')
                                                                <div
                                                                    class="tw-inset-x-0 tw-box-border tw-flex tw-min-h-0 tw-flex-1 tw-flex-col tw-overflow-auto">
                                                                    <x-filter.search_options
                                                                        search-placeholder="{{ utrans('item.filter.placeholder.name_object') }}"
                                                                        v-bind:options="aggregations.technique"
                                                                        v-bind:selected="query.technique"
                                                                        v-on:change="e => handleMultiSelectChange('technique', e)"
                                                                        v-on:reset="clearFilterSelection('technique')">
                                                                    </x-filter.search_options>
                                                                </div>
                                                            @endslot
                                                        </x-filter.disclosure_view>
                                                        <x-filter.disclosure_view v-if="dc.view === 'topic'"
                                                            v-on:close="dc.close">
                                                            @slot('header')
                                                                <x-filter.view_header_button
                                                                    v-on:click="dc.goTo('index')">
                                                                    <filter-new-custom-select-popover-label
                                                                        name="{{ trans('item.topic') }}"
                                                                        v-bind:selected-values="query.topic">
                                                                    </filter-new-custom-select-popover-label>
                                                                </x-filter.view_header_button>
                                                            @endslot
                                                            @slot('reset_button')
                                                                <x-filter.reset_button class="tw-mr-3"
                                                                    v-if="query.topic.length"
                                                                    v-on:click="clearFilterSelection('topic')">
                                                                    {{ trans('item.filter.clear') }}
                                                                </x-filter.reset_button>
                                                            @endslot
                                                            @slot('body')
                                                                <div
                                                                    class="tw-inset-x-0 tw-box-border tw-flex tw-min-h-0 tw-flex-1 tw-flex-col tw-overflow-auto">
                                                                    <x-filter.search_options
                                                                        search-placeholder="{{ utrans('item.filter.placeholder.name_object') }}"
                                                                        v-bind:options="aggregations.topic"
                                                                        v-bind:selected="query.topic"
                                                                        v-on:change="e => handleMultiSelectChange('topic', e)"
                                                                        v-on:reset="clearFilterSelection('topic')">
                                                                    </x-filter.search_options>
                                                                </div>
                                                            @endslot
                                                        </x-filter.disclosure_view>
                                                        <x-filter.disclosure_view
                                                            v-if="dc.view === 'medium'"
                                                            v-on:close="dc.close">
                                                            @slot('header')
                                                                <x-filter.view_header_button
                                                                    v-on:click="dc.goTo('index')">
                                                                    <filter-new-custom-select-popover-label
                                                                        name="{{ trans('item.medium') }}"
                                                                        v-bind:selected-values="query.medium">
                                                                    </filter-new-custom-select-popover-label>
                                                                </x-filter.view_header_button>
                                                            @endslot
                                                            @slot('reset_button')
                                                                <x-filter.reset_button class="tw-mr-3"
                                                                    v-if="query.medium.length"
                                                                    v-on:click="clearFilterSelection('medium')">
                                                                    {{ trans('item.filter.clear') }}
                                                                </x-filter.reset_button>
                                                            @endslot
                                                            @slot('body')
                                                                <div
                                                                    class="tw-inset-x-0 tw-box-border tw-flex tw-min-h-0 tw-flex-1 tw-flex-col tw-overflow-auto">
                                                                    <x-filter.search_options
                                                                        search-placeholder="{{ utrans('item.filter.placeholder.name_object') }}"
                                                                        v-bind:options="aggregations.medium"
                                                                        v-bind:selected="query.medium"
                                                                        v-on:change="e => handleMultiSelectChange('medium', e)"
                                                                        v-on:reset="clearFilterSelection('medium')">
                                                                    </x-filter.search_options>
                                                                </div>
                                                            @endslot
                                                        </x-filter.disclosure_view>
                                                    </transition>
                                                </div>
                                            @endslot
                                            @slot('footer')
                                                <button class="tw-m-4 tw-w-full tw-bg-sky-300 tw-p-4"
                                                    v-on:click="dc.close">
                                                    {{ trans('item.filter.show_results') }}
                                                    <span class="tw-font-bold">
                                                        (<catalog.number-formatter
                                                            v-bind:value="artworks_total">
                                                        </catalog.number-formatter>)
                                                    </span>
                                                </button>
                                            @endslot
                                            </x-filter.disclosure-modal>
                                    </transition>
                                </div>
                            </filter-disclosure-controller>
                        </div>
                        <div
                            class="tw-mx-auto tw-hidden tw-max-w-screen-2xl tw-space-x-6 tw-bg-gray-200 tw-px-6 tw-pt-4 tw-pb-2 md:tw-flex md:tw-px-8">
                            <filter-new-custom-checkbox v-on:change="handleCheckboxChange"
                                v-bind:checked="Boolean(query.has_image)"
                                title="{{ utrans('item.filter.has_image') }}" name="has_image"
                                id="has_image_desktop">
                            </filter-new-custom-checkbox>
                            <filter-new-custom-checkbox v-on:change="handleCheckboxChange"
                                v-bind:checked="Boolean(query.has_iip)"
                                title="{{ utrans('item.filter.has_iip') }}" name="has_iip"
                                id="has_iip_desktop">
                            </filter-new-custom-checkbox>
                            <filter-new-custom-checkbox v-on:change="handleCheckboxChange"
                                v-bind:checked="Boolean(query.is_free)"
                                title="{{ utrans('item.filter.is_free') }}" name="is_free"
                                id="is_free_desktop">
                            </filter-new-custom-checkbox>
                            <filter-new-custom-checkbox v-on:change="handleCheckboxChange"
                                v-bind:checked="Boolean(query.has_text)"
                                title="{{ utrans('item.filter.has_text') }}" name="has_text"
                                id="has_text_desktop">
                            </filter-new-custom-checkbox>
                        </div>
                        {{-- Selected labels --}}
                        <div
                            class="tw-mx-auto tw-hidden tw-h-8 tw-max-w-screen-2xl tw-bg-gray-200 tw-px-6 tw-pb-16 md:tw-block md:tw-px-8">
                            <div class="tw-flex tw-w-full tw-space-x-3 tw-overflow-x-auto">
                                <transition-group enter-from-class="tw-opacity-0"
                                    leave-to-class="tw-opacity-0"
                                    enter-active-class="tw-transition tw-duration-100"
                                    leave-active-class="tw-transition tw-duration-100"
                                    class="tw-flex tw-space-x-3 tw-pb-4">
                                    <button
                                        class="tw-flex tw-items-center tw-whitespace-nowrap tw-bg-gray-300 tw-py-1 tw-px-1.5"
                                        v-for="option in selectedOptionsAsLabels"
                                        v-on:click="removeSelection(option)">
                                        <span v-if="option.filterName === 'color'"
                                            class="tw-flex tw-items-center tw-pr-1.5 tw-text-xs tw-font-semibold tw-uppercase">
                                            <div class="tw-mr-1.5 tw-inline-block tw-h-4 tw-w-4"
                                                v-bind:style="{ 'background-color': `#${option.value}`, 'border-radius': '30px' }">
                                            </div>
                                            @{{ option.value }}
                                        </span>
                                        <span v-else-if="option.filterName === 'yearRange'"
                                            class="tw-pr-1.5 tw-text-xs tw-font-semibold">@{{ option.value.from }}
                                            -
                                            @{{ option.value.to }}</span>
                                        <span v-else-if="option.filterName ==='author'"
                                            class="tw-pr-1.5 tw-text-xs tw-font-semibold">
                                            <catalog.author-formatter v-bind:value="option.value">
                                            </catalog.author-formatter>
                                        </span>
                                        <span v-else class="tw-pr-1.5 tw-text-xs tw-font-semibold">
                                            @{{ option.value }}
                                        </span>
                                        <svg xmlns="http://www.w3.org/2000/svg"
                                            class="tw-h-4 tw-w-4 tw-fill-current" viewBox="0 0 256 256">
                                            <path
                                                d="M202.83,197.17a4,4,0,0,1-5.66,5.66L128,133.66,58.83,202.83a4,4,0,0,1-5.66-5.66L122.34,128,53.17,58.83a4,4,0,0,1,5.66-5.66L128,122.34l69.17-69.17a4,4,0,1,1,5.66,5.66L133.66,128Z">
                                            </path>
                                        </svg>
                                    </button>
                                </transition-group>
                                <transition enter-from-class="tw-opacity-0"
                                    leave-to-class="tw-opacity-0"
                                    enter-active-class="tw-transition tw-duration-100"
                                    leave-active-class="tw-transition tw-duration-100"
                                    class="mb-4">
                                    <x-filter.reset_button v-if="selectedOptionsAsLabels.length"
                                        v-on:click="clearAllSelections" sm>
                                        <span
                                            class="tw-whitespace-nowrap">{{ trans('item.filter.clear') }}
                                        </span>
                                    </x-filter.reset_button>
                                </transition>
                            </div>
                        </div>
                    </div>
                    <div class="tw-mx-auto tw-min-h-screen tw-max-w-screen-2xl tw-px-4 md:tw-px-8 md:tw-py-10"
                        v-if="artworks.length || !isFetchingArtworks">
                        <div v-if="hasError"
                            class="tw-flex tw-w-full tw-flex-col tw-items-center tw-justify-center tw-py-40 tw-text-lg">
                            <span>{{ utrans('item.filter.something_went_wrong') }}.</span>
                            <reload-controller v-slot="rc">
                                <button class="tw-inline-block tw-underline"
                                    v-on:click="rc.reload">{{ trans('item.filter.refresh_page') }}</button>
                            </reload-controller>
                        </div>
                        <div v-else-if="artworks.length === 0"
                            class="tw-flex tw-w-full tw-flex-col tw-items-center tw-justify-center tw-py-40 tw-text-lg">
                            <div class="tw-w-72">
                                <lottie-player autoplay loop mode="normal"
                                    src="{{ asset('animations/empty.json') }}">
                                </lottie-player>
                            </div>
                            <span
                                class="tw-mt-10">{{ utrans('item.filter.nothing_found') }}</span>
                            <button v-on:click="handleSelectRandomly"
                                class="tw-font-bold tw-underline tw-underline-offset-8">
                                {{ utrans('item.filter.try_also') }}
                                {{ trans('item.filter.random_search') }}</a>
                        </div>
                        <div v-else>
                            <div class="tw-px-2 tw-py-6 md:tw-px-0 md:tw-pb-8 md:tw-pt-0">
                                <span class="tw-font-semibold">
                                    <span v-if="artworks_total === 1"><span
                                            class="tw-capitalize">{{ trans_choice('item.filter.displaying', 1) }}
                                        </span><span class="tw-font-bold">1</span>
                                        {{ trans_choice('item.filter.artworks_sorted_by', 1) }}</span>
                                    <span v-else-if="artworks_total < 5"><span
                                            class="tw-capitalize">{{ trans_choice('item.filter.displaying', 4) }}</span>
                                        <span class="tw-font-bold">
                                            <catalog.number-formatter v-bind:value="artworks_total">
                                            </catalog.number-formatter>
                                        </span>
                                        {{ trans_choice('item.filter.artworks_sorted_by', 4) }}</span>
                                    <span v-else><span
                                            class="tw-capitalize">{{ trans_choice('item.filter.displaying', 5) }}</span>
                                        <span class="tw-font-bold">
                                            <catalog.number-formatter v-bind:value="artworks_total">
                                            </catalog.number-formatter>
                                        </span>
                                        {{ trans_choice('item.filter.artworks_sorted_by', 5) }}</span>
                                    <span class="tw-font-semibold">
                                        <div class="tw-z-10 tw-inline-block">
                                            <filter-new-popover.group-controller>
                                                <filter-popover-controller name="sort">
                                                    <template #button="pc">
                                                        <button id="button-sort"
                                                            class="tw-font-bold tw-underline tw-decoration-2 tw-underline-offset-4"
                                                            v-on:click="pc.togglePopover('sort')">
                                                            <transition
                                                                enter-from-class="tw-opacity-0"
                                                                leave-to-class="tw-opacity-0"
                                                                enter-active-class="tw-duration-50 tw-transition"
                                                                leave-active-class="tw-duration-50 tw-transition"
                                                                mode="out-in">
                                                                <span
                                                                    v-if="query.sort === 'created_at' ">{{ trans('sortable.created_at') }}</span>
                                                                <span
                                                                    v-else-if="query.sort === 'title' ">{{ trans('sortable.title') }}</span>
                                                                <span
                                                                    v-else-if="query.sort === 'author' ">{{ trans('sortable.author') }}</span>
                                                                <span
                                                                    v-else-if="query.sort === 'date_earliest' ">{{ trans('sortable.oldest') }}</span>
                                                                <span
                                                                    v-else-if="query.sort === 'date_latest' ">{{ trans('sortable.newest') }}</span>
                                                                <span
                                                                    v-else-if="query.sort === 'view_count' ">{{ trans('sortable.view_count') }}</span>
                                                                <span
                                                                    v-else-if="query.sort === 'random' ">{{ trans('sortable.random') }}</span>
                                                                <span
                                                                    v-else-if="query.sort === 'updated_at'">{{ trans('sortable.updated_at') }}</span>
                                                                <span
                                                                    v-else>{{ trans('sortable.relevance') }}</span>
                                                            </transition>
                                                            <x-icons.caret-down
                                                                class="tw-inline tw-h-4 tw-w-4 tw-fill-current">
                                                            </x-icons.caret-down>
                                                        </button>

                                                    </template>
                                                    <template #body="pc">
                                                        <transition enter-from-class="tw-opacity-0"
                                                            leave-to-class="tw-opacity-0"
                                                            enter-active-class="tw-transition tw-duration-100"
                                                            leave-active-class="tw-transition tw-duration-100">
                                                            <div v-if="pc.isOpen"
                                                                v-on-clickaway="pc.closeOpenedPopover"
                                                                class="tw-w-80 tw-border-2 tw-border-gray-800 tw-bg-white tw-p-4">

                                                                <ul>
                                                                    <li class="tw-cursor-pointer tw-py-0.5 tw-pl-2 hover:tw-bg-gray-200"
                                                                        v-on:click="handleSortChange('created_at');pc.closeOpenedPopover()"
                                                                        v-if="query.sort !== 'created_at'">
                                                                        {{ trans('sortable.created_at') }}
                                                                    </li>
                                                                    <li class="tw-cursor-pointer tw-py-0.5 tw-pl-2 hover:tw-bg-gray-200"
                                                                        v-on:click="handleSortChange('title');pc.closeOpenedPopover()"
                                                                        v-if="query.sort !== 'title'">
                                                                        {{ trans('sortable.title') }}
                                                                    </li>
                                                                    <li class="tw-cursor-pointer tw-py-0.5 tw-pl-2 hover:tw-bg-gray-200"
                                                                        v-on:click="handleSortChange('author');pc.closeOpenedPopover()"
                                                                        v-if="query.sort !== 'author'">
                                                                        {{ trans('sortable.author') }}
                                                                    </li>
                                                                    <li class="tw-cursor-pointer tw-py-0.5 tw-pl-2 hover:tw-bg-gray-200"
                                                                        v-on:click="handleSortChange('date_earliest');pc.closeOpenedPopover()"
                                                                        v-if="query.sort !== 'date_earliest'">
                                                                        {{ trans('sortable.oldest') }}
                                                                    </li>
                                                                    <li class="tw-cursor-pointer tw-py-0.5 tw-pl-2 hover:tw-bg-gray-200"
                                                                        v-on:click="handleSortChange('date_latest');pc.closeOpenedPopover()"
                                                                        v-if="query.sort !== 'date_latest'">
                                                                        {{ trans('sortable.newest') }}
                                                                    </li>
                                                                    <li class="tw-cursor-pointer tw-py-0.5 tw-pl-2 hover:tw-bg-gray-200"
                                                                        v-on:click="handleSortChange('view_count');pc.closeOpenedPopover()"
                                                                        v-if="query.sort !== 'view_count'">
                                                                        {{ trans('sortable.view_count') }}
                                                                    </li>
                                                                    <li class="tw-cursor-pointer tw-py-0.5 tw-pl-2 hover:tw-bg-gray-200"
                                                                        v-on:click="handleSortChange('random');pc.closeOpenedPopover()"
                                                                        v-if="query.sort !== 'random'">
                                                                        {{ trans('sortable.random') }}
                                                                    </li>
                                                                    <li class="tw-cursor-pointer tw-py-0.5 tw-pl-2 hover:tw-bg-gray-200"
                                                                        v-on:click="handleSortChange('updated_at');pc.closeOpenedPopover()"
                                                                        v-if="query.sort !== 'updated_at'">
                                                                        {{ trans('sortable.updated_at') }}
                                                                    </li>
                                                                    <li class="tw-cursor-pointer tw-py-0.5 tw-pl-2 hover:tw-bg-gray-200"
                                                                        v-on:click="handleSortChange(null);pc.closeOpenedPopover()"
                                                                        v-if="query.sort">
                                                                        {{ trans('sortable.relevance') }}
                                                                    </li>
                                                                </ul>
                                                            </div>
                                                        </transition>
                                                    </template>
                                                </filter-popover-controller>
                                            </filter-new-popover.group-controller>
                                        </div>
                                    </span>
                                    <span>
                                        {{ utrans('item.filter.try_also') }}
                                        <button v-on:click="handleSelectRandomly"
                                            class="tw-font-bold tw-underline tw-decoration-2 tw-underline-offset-4">{{ trans('item.filter.random_search') }}</button>
                                    </span>
                                </span>
                            </div>
                            {{-- Artwork Masonry --}}
                            <div v-masonry transition-duration="0" item-selector=".item"
                                gutter=".gutter-sizer">
                                <div class="gutter-sizer md:tw-w-8 2xl:tw-w-16"></div>
                                <div v-masonry-tile
                                    class="item tw-w-full tw-p-2 tw-pb-4 md:tw-w-[calc(33.3333%-1.375rem)] md:tw-p-0 md:tw-pb-6 2xl:tw-w-[calc(33.3333%-2.75rem)] 2xl:tw-pb-10"
                                    v-for="artwork in artworks" v-bind:key="artwork.id">
                                    <div name="artwork-image">
                                        <catalog.artwork-image-controller v-slot="ic">
                                            <div>
                                                <a v-bind:href="$route('dielo', {id: artwork.id})">
                                                    <img v-bind:class="[{'tw-hidden': !ic.isLoaded }, 'tw-w-full']"
                                                        v-on:load="ic.onImgLoad"
                                                        v-bind:src="$route('dielo.nahlad', {id: artwork.id, width: 220})"
                                                        v-bind:srcset="`${$route('dielo.nahlad', {id: artwork.id, width: 600})} 600w, ${$route('dielo.nahlad', {id: artwork.id, width: 220})} 220w, ${$route('dielo.nahlad', {id: artwork.id, width: 300})} 300w, ${$route('dielo.nahlad', {id: artwork.id, width: 600})} 600w, ${$route('dielo.nahlad', {id: artwork.id, width: 800})} 800w`"
                                                        sizes="(max-width: 768px) 250vw, 100vw">
                                                </a>
                                                <div v-bind:class="[{'tw-hidden': ic.isLoaded }, 'tw-w-full tw-saturate-50 tw-bg-gray-300 tw-flex tw-items-center tw-justify-center']"
                                                    v-bind:style="{'aspect-ratio': artwork.content.image_ratio || 7/8, 'background-color': artwork.content.hsl[0] ? `hsl(${artwork.content.hsl[0].h}, ${artwork.content.hsl[0].s}%, ${artwork.content.hsl[0].l}%)` : undefined}">
                                                </div>
                                            </div>
                                        </catalog.artwork-image-controller>
                                        <div class="tw-mt-6 tw-flex tw-gap-3">
                                            <div class="tw-flex tw-grow tw-flex-col">
                                                <a v-bind:href="$route('dielo', {id: artwork.id})"
                                                    class="tw-pb-1.5 tw-text-lg tw-font-light tw-italic">@{{ artwork.content.authors_formatted.join(', ') }}</a>
                                                <a v-bind:href="$route('dielo', {id: artwork.id})"
                                                    class="tw-pb-2 tw-text-lg">@{{ artwork.content.title }}</a>
                                                <a v-bind:href="$route('dielo', {id: artwork.id})"
                                                    class="tw-text-base tw-font-light">@{{ artwork.content.dating }}</a>
                                            </div>
                                            <div class="tw-flex tw-items-start tw-gap-4">
                                                <user-collections-store
                                                    v-slot="{ toggleItem, hasItem }">
                                                    <button v-on:click="toggleItem(artwork.id)">
                                                        <svg v-if="hasItem(artwork.id)"
                                                            xmlns="http://www.w3.org/2000/svg"
                                                            class="tw-h-5 tw-w-5 tw-fill-current"
                                                            viewBox="0 0 256 256">
                                                            <path
                                                                d="M234.5,114.38l-45.1,39.36,13.51,58.6a16,16,0,0,1-23.84,17.34l-51.11-31-51,31a16,16,0,0,1-23.84-17.34L66.61,153.8,21.5,114.38a16,16,0,0,1,9.11-28.06l59.46-5.15,23.21-55.36a15.95,15.95,0,0,1,29.44,0h0L166,81.17l59.44,5.15a16,16,0,0,1,9.11,28.06Z">
                                                            </path>
                                                        </svg>
                                                        <svg v-else xmlns="http://www.w3.org/2000/svg"
                                                            class="tw-h-5 tw-w-5 tw-fill-current"
                                                            viewBox="0 0 256 256">
                                                            <path
                                                                d="M243,96.05a20,20,0,0,0-17.26-13.72l-57-4.93-22.3-53.14h0a20,20,0,0,0-36.82,0L87.29,77.4l-57,4.93A20,20,0,0,0,18.87,117.4l43.32,37.8-13,56.24A20,20,0,0,0,79,233.1l49-29.76,49,29.76a20,20,0,0,0,29.8-21.66l-13-56.24,43.32-37.8A20,20,0,0,0,243,96.05Zm-66.75,42.62a20,20,0,0,0-6.35,19.63l11.39,49.32-42.94-26.08a19.9,19.9,0,0,0-20.7,0L74.71,207.62,86.1,158.3a20,20,0,0,0-6.35-19.63L41.66,105.44,91.8,101.1a19.92,19.92,0,0,0,16.69-12.19L128,42.42l19.51,46.49A19.92,19.92,0,0,0,164.2,101.1l50.14,4.34Z">
                                                            </path>
                                                        </svg>
                                                    </button>
                                                </user-collections-store>
                                                <a v-if="artwork.content.has_iip"
                                                    v-bind:href="$route('item.zoom', {id: artwork.id})">
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
                                v-bind:page="page" v-on:loadmore="loadMore"
                                v-bind:is-loading="isFetchingArtworks">
                                <template #loading-message>
                                    <div class="tw-flex tw-justify-center">
                                        <div
                                            class="tw-border tw-border-gray-400 tw-py-2.5 tw-px-8 tw-text-sm hover:tw-border-gray-700">
                                            {{ trans('item.filter.loading') }}
                                        </div>
                                    </div>
                                </template>
                                <template #load-more-button>
                                    <div class="tw-flex tw-justify-center">
                                        <button v-if="page === 1" v-on:click="loadMore"
                                            class="tw-border tw-border-gray-400 tw-py-2.5 tw-px-8 tw-text-sm hover:tw-border-gray-700">
                                            {{ trans('general.filter.show_more') }}
                                        </button>
                                    </div>
                                </template>
                            </catalog.infinite-scroll>
                            <div class="tw-mt-10 tw-flex tw-justify-center tw-text-sm" v-else>
                                {{ trans('katalog.catalog_finished') }}  
                            </div>
                        </div>
                    </div>
                </div>
        </filter-new-items-controller>
    </section>
@stop
