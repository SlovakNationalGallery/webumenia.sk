@extends('layouts.master')

@section('content')
    <section class="tailwind-rules">
        <filter-new-items-controller :year-min="-475" :year-max="2023"
            v-slot="{ isExtendedOpen, toggleIsExtendedOpen, handleMultiSelectChange, selectedOptionsAsLabels, handleSortChange, handleColorChange, handleYearRangeChange, handleCheckboxChange, clearFilterSelection, clearAllSelections, removeSelection, query, filters, artworks }">
            <div class="tw-relative">
                <div class="tw-bg-gray-200 tw-py-6 tw-px-4 md:tw-p-16 md:tw-pb-0">
                    {{-- Desktop filter --}}
                    <filter-new-popover-group-controller>
                        <div class="tw-hidden tw-gap-x-3 tw-overflow-x-auto md:tw-flex md:tw-flex-wrap">
                            <filter-new-popover name="author">
                                <template #popover-label>
                                    <filter-new-custom-select-popover-label name="author"
                                        :selected-values="query['author']">
                                    </filter-new-custom-select-popover-label>
                                </template>
                                <template #body>
                                    <div
                                        class="tw-absolute tw-top-36 tw-z-10 tw-flex tw-h-[30rem] tw-w-[20rem] tw-flex-col tw-items-start tw-border-2 tw-border-gray-800 tw-bg-white tw-p-6">
                                        <filter-new-options filter-name="author"
                                            placeholder="Napíšte meno autora / autorky"
                                            @change="handleMultiSelectChange"
                                            :selected-values="query['author']"
                                            :filter="filters['author']">
                                        </filter-new-options>
                                        <button
                                            class="tw-mb-6 tw-mt-5 tw-flex tw-items-center tw-border tw-border-gray-300 tw-bg-white tw-px-4 tw-py-1.5 tw-text-sm tw-font-normal hover:tw-border-gray-800"
                                            @click="clearFilterSelection('author')">
                                            <svg class="tw-mr-1.5"
                                                xmlns="http://www.w3.org/2000/svg" width="16"
                                                height="16" fill="currentColor" viewBox="0 0 256 256">
                                                <path
                                                    d="M228,128a100,100,0,0,1-98.66,100H128a99.39,99.39,0,0,1-68.62-27.29,12,12,0,0,1,16.48-17.45,76,76,0,1,0-1.57-109c-.13.13-.25.25-.39.37L54.89,92H72a12,12,0,0,1,0,24H24a12,12,0,0,1-12-12V56a12,12,0,0,1,24,0V76.72L57.48,57.06A100,100,0,0,1,228,128Z">
                                                </path>
                                            </svg>
                                            <span>zrušiť výber</span>
                                        </button>
                                    </div>
                                </template>
                            </filter-new-popover>
                            <filter-new-popover v-if="isExtendedOpen" name="color">
                                <template #popover-label>
                                    <span
                                        class="tw-font-sm md:tw-font-base tw-font-semibold">color</span>
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
                                    <span class="tw-font-sm md:tw-font-base tw-font-semibold">year
                                        range</span>
                                </template>
                                <template #body>
                                    <div class="tw-absolute tw-top-36 tw-z-10">
                                        <div
                                            class="tw-w-[28rem] tw-border-2 tw-border-gray-800 tw-bg-white tw-p-6 tw-pt-4">
                                            <filter-new-year-slider
                                                :default-from="Number(query.yearFrom)"
                                                :default-to="Number(query.yearTo)"
                                                @change="handleYearRangeChange" :min="filters.yearMin"
                                                :max="filters.yearMax">
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
                    {{-- Mobile Filter --}}
                    <filter-disclosure-controller v-slot="dc">
                        <div class="tw-relative md:tw-hidden">
                            <div class="tw-flex tw-gap-x-3 tw-overflow-x-auto">
                                <filter-disclosure-button name="author" @click="dc.goTo('author')">
                                    <filter-new-custom-select-popover-label name="author"
                                        :selected-values="query['author']">
                                    </filter-new-custom-select-popover-label>
                                </filter-disclosure-button>
                            </div>
                            <div class="tw-min-w-max tw-pt-4">
                                <button @click="dc.goTo('index')"
                                    class="tw-w-full tw-border tw-border-gray-300 tw-py-2 tw-px-3 tw-font-medium hover:tw-border-gray-800">
                                    <div class="tw-flex tw-justify-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="tw-w-6 tw-h-6 tw-fill-current" viewBox="0 0 256 256">
                                            <path
                                                d="M40,92H70.06a36,36,0,0,0,67.88,0H216a12,12,0,0,0,0-24H137.94a36,36,0,0,0-67.88,0H40a12,12,0,0,0,0,24Zm64-24A12,12,0,1,1,92,80,12,12,0,0,1,104,68Zm112,96H201.94a36,36,0,0,0-67.88,0H40a12,12,0,0,0,0,24h94.06a36,36,0,0,0,67.88,0H216a12,12,0,0,0,0-24Zm-48,24a12,12,0,1,1,12-12A12,12,0,0,1,168,188Z">
                                            </path>
                                        </svg> <span class="tw-font-semibold">rozšírený filter</span>
                                    </div>
                                </button>
                            </div>
                            <filter-disclosure-modal v-if="dc.view !== null" @close="dc.close">
                                <template #body>
                                    <filter-disclosure-view v-if="dc.view === 'index'" @close="dc.close"
                                        @reset="clearAllSelections">
                                        <template #header>
                                            <span class="tw-text-lg tw-font-semibold">Filter diel</span>
                                        </template>
                                        <template #body>
                                            <filter-disclosure-list-button @click="dc.goTo('author')">
                                                <filter-new-custom-select-popover-label name="authors"
                                                    :selected-values="query['authors']">
                                                </filter-new-custom-select-popover-label>
                                            </filter-disclosure-list-button>
                                            <div
                                                class="tw-flex tw-min-h-0 tw-w-full tw-flex-1 tw-flex-col tw-overflow-auto tw-py-2">
                                                <filter-new-custom-checkbox @change="handleCheckboxChange"
                                                    :checked="Boolean(query['has_image'])"
                                                    title="Len s obrázkom" name="has_image"
                                                    id="has_image_desktop">
                                                </filter-new-custom-checkbox>
                                                <filter-new-custom-checkbox @change="handleCheckboxChange"
                                                    :checked="Boolean(query['has_iip'])"
                                                    title="Len so zoomom" name="has_iip"
                                                    id="has_iip_desktop">
                                                </filter-new-custom-checkbox>
                                                <filter-new-custom-checkbox @change="handleCheckboxChange"
                                                    :checked="Boolean(query['is_free'])" title="Len voľné"
                                                    name="is_free" id="is_free_desktop">
                                                </filter-new-custom-checkbox>
                                                <filter-new-custom-checkbox @change="handleCheckboxChange"
                                                    :checked="Boolean(query['has_text'])"
                                                    title="Len s textom" name="has_text"
                                                    id="has_text_desktop">
                                                </filter-new-custom-checkbox>
                                            </div>
                                    </template>
                                </filter-disclosure-view>
                                <filter-disclosure-view v-if="dc.view === 'author'" @close="dc.close"
                                    @reset="clearFilterSelection('author')">
                                    <template #header>
                                        <div class="tw-flex tw-items-center">
                                            <button @click="dc.goTo('index')" class="tw-pr-2">
                                                <svg xmlns="http://www.w3.org/2000/svg"
                                                    viewBox="0 0 256 256"
                                                    class="tw-h-6 tw-w-6 tw-fill-current">
                                                    <path
                                                        d="M168.49,199.51a12,12,0,0,1-17,17l-80-80a12,12,0,0,1,0-17l80-80a12,12,0,0,1,17,17L97,128Z">
                                                    </path>
                                                </svg> </button>
                                            <filter-new-custom-select-popover-label name="author"
                                                :selected-values="query['author']">
                                            </filter-new-custom-select-popover-label>
                                    </template>
                                    <template #body>
                                        <div
                                            class="tw-inset-x-0 tw-box-border tw-flex tw-min-h-0 tw-flex-1 tw-flex-col tw-overflow-auto">
                                            <filter-new-options filter-name="author"
                                                placeholder="Napíšte meno autora / autorky"
                                                @change="handleMultiSelectChange"
                                                :selected-values="query['author']"
                                                :filter="filters['author']">
                                            </filter-new-options>
                                        </div>
                                    </template>
                                </filter-disclosure-view>
                                </template #body>
                                <template #footer>
                                    <button class="tw-m-4 tw-w-full tw-bg-sky-300 tw-p-4"
                                        @click="dc.close">
                                        zobraziť výsledky <span
                                            class="tw-font-bold">(@{{ artworks.total }})</span>
                                    </button>
                                </template>
                            </filter-disclosure-modal>
                        </div>
                    </filter-disclosure-controller>
                </div>
                <div class="tw-hidden tw-space-x-6 tw-bg-gray-200 tw-px-16 tw-pt-4 tw-pb-5 md:tw-flex">
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
                <div class="tw-hidden tw-bg-gray-200 tw-px-16 tw-pb-16 md:tw-block">
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
                                        value: 'date_earliest',
                                        text: 'datovanie - od najnovšieho',
                                    },
                                    {
                                        value: 'date_latest',
                                        text: 'datovanie - od najstaršieho',
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
                    <span v-if="artworks.total === 1">Zobrazujem <span class="tw-font-bold">1</span>
                        dielo, zoradené podľa</span>
                    <span v-else-if="artworks.total < 5">Zobrazujem <span
                            class="tw-font-bold">@{{ artworks['total'] }}</span> diela, zoradené
                        podľa</span>
                    <span v-else>Zobrazujem <span class="tw-font-bold">@{{ artworks['total'] }}</span>
                        diel, zoradených
                        podľa</span>
                </filter-new-sort>
                <div v-for="artwork in artworks.data">
                    @{{ artwork.id }}
                </div>
            </div>
        </filter-new-items-controller>
    </section>

@stop
