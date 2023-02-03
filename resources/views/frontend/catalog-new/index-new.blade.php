@extends('layouts.master')

@section('content')
    <section class="tailwind-rules">
        <filter-new :authors="{{ $authors }}"
            v-slot="{ isExtendedOpen, openedFilter, handleMultiSelectChange, selectedOptionsAsLabels, toggleIsExtendedOpen, handleSortChange, handleCheckboxChange, clearFilterSelection, toggleSelect, closeOpenedFilter, clearAllSelections, removeSelection, query, filters }">
            <div class="tw-relative">
                <div class="tw-bg-gray-200 tw-p-16 md:tw-pb-0">
                    <div class="tw-flex tw-gap-x-3 tw-overflow-x-auto md:tw-flex-wrap">
                        <filter-new-custom-select :close-opened-filter="closeOpenedFilter"
                            :opened-filter="openedFilter"
                            :handle-multi-select-change="handleMultiSelectChange"
                            :clear-filter-selection="clearFilterSelection" :filter="filters['authors']"
                            :toggle-select="toggleSelect" :selected-values="query['authors']"
                            name="authors" placeholder="Napíšte meno autora / autorky">
                        </filter-new-custom-select>
                        <filter-new-custom-select :close-opened-filter="closeOpenedFilter"
                            :opened-filter="openedFilter"
                            :handle-multi-select-change="handleMultiSelectChange"
                            :clear-filter-selection="clearFilterSelection" :filter="filters['authors']"
                            :toggle-select="toggleSelect" :selected-values="query['authors']"
                            name="authors" v-if="isExtendedOpen"
                            placeholder="Napíšte meno autora / autorky">
                        </filter-new-custom-select>
                        <filter-show-more class="tw-hidden md:tw-block"
                            :is-extended-open="isExtendedOpen"
                            :toggle-is-extended-open="toggleIsExtendedOpen">
                        </filter-show-more>
                    </div>
                    <filter-show-more class="tw-visible tw-pt-4 md:tw-hidden"
                        :is-extended-open="isExtendedOpen"
                        :toggle-is-extended-open="toggleIsExtendedOpen">
                    </filter-show-more>
                </div>
                <filter-new-mobile-custom-select :is-extended-open="isExtendedOpen"
                    :opened-filter="openedFilter" :handle-multi-select-change="handleMultiSelectChange"
                    :toggle-is-extended-open="toggleIsExtendedOpen"
                    :handle-checkbox-change="handleCheckboxChange"
                    :clear-filter-selection="clearFilterSelection" :toggle-select="toggleSelect"
                    :query="query" :filters="filters" v-if="isExtendedOpen"
                    placeholder="Simple dummy text">
                </filter-new-mobile-custom-select>
                <div
                    class="tw-invisible tw-space-x-3 tw-bg-gray-200 tw-px-16 tw-pt-4 tw-pb-5 md:tw-visible md:tw-flex">
                    <filter-new-custom-checkbox :handle-checkbox-change="handleCheckboxChange"
                        :checkbox-value="Boolean(query['has_image'])" title="Len s obrázkom"
                        name="has_image">
                    </filter-new-custom-checkbox>
                    <filter-new-custom-checkbox :handle-checkbox-change="handleCheckboxChange"
                        :checkbox-value="Boolean(query['has_iip'])" title="Len so zoomom"
                        name="has_iip">
                    </filter-new-custom-checkbox>
                    <filter-new-custom-checkbox :handle-checkbox-change="handleCheckboxChange"
                        :checkbox-value="Boolean(query['is_free'])" title="Len voľné" name="is_free">
                    </filter-new-custom-checkbox>
                    <filter-new-custom-checkbox :handle-checkbox-change="handleCheckboxChange"
                        :checkbox-value="Boolean(query['has_text'])" title="Len s textom"
                        name="has_text">
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
                                                            ]" />
            </div>
        </filter-new>
    </section>

@stop
