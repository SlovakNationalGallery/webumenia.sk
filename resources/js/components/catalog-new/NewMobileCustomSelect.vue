<template>
    <div class="md:tw-hidden tw-flex tw-h-full tw-w-full tw-flex-col tw-fixed tw-top-0 tw-z-30">
        <div class="tw-min-w-full tw-h-10 tw-shrink-0 tw-opacity-40 tw-left-0 tw-bg-gray-800" />
        <div class="tw-w-full tw-flex tw-flex-1 tw-overflow-auto tw-bg-white tw-flex-col">
            <div class="tw-ml-4 tw-mr-6 tw-mb-5 tw-mt-6">
                <div class="tw-flex tw-justify-between">
                    <div v-if="!openedFilter" class="tw-flex tw-space-x-3 tw-items-center">
                        <span>Filter diel</span>
                    </div>
                    <div v-else>
                        <button @click="toggleSelect('authors')">
                            <i class="fa fa-chevron-left tw-text-gray-800"></i>
                        </button>
                        <span>{{ openedFilter }}</span>
                    </div>
                    <button @click="toggleIsExtendedOpen">
                        <i class="fa fa-close"></i>
                    </button>
                </div>
            </div>
            <div v-if="openedFilter" class="tw-px-4 tw-flex tw-flex-1 tw-min-h-0">
                <Options
                    placeholder=""
                    :filterName="openedFilter"
                    :handleMultiSelectChange="handleMultiSelectChange"
                    :selectedValues="selectedValues"
                    :filter="filters[openedFilter]"
                />
            </div>
            <div class="tw-w-full tw-flex-1 tw-flex tw-flex-col tw-overflow-auto tw-min-h-0" v-else>
                <button
                    @click="toggleSelect('authors')"
                    class="tw-w-full tw-border-b tw-font-medium tw-border-gray-200 tw-px-4 tw-py-5"
                >
                    <div class="tw-flex tw-justify-between">
                        <span>{{ 'authors' }}</span>
                        <i class="fa fa-caret-right"></i>
                    </div>
                </button>
                <div class="tw-py-2">
                    <NewCustomCheckbox
                        :handleCheckboxChange="handleCheckboxChange"
                        :checked="Boolean(query['has_iip'])"
                        title="Len s obrázkom"
                        name="has_image"
                        id="has_image_mobile"
                    />
                    <NewCustomCheckbox
                        :handleCheckboxChange="handleCheckboxChange"
                        :checked="Boolean(query['has_iip'])"
                        title="Len so zoomom"
                        name="has_iip"
                        id="has_iip_mobile"
                    />
                    <NewCustomCheckbox
                        :handleCheckboxChange="handleCheckboxChange"
                        :checked="Boolean(query['is_free'])"
                        title="Len voľné"
                        name="is_free"
                        id="is_free_mobile"
                    />
                    <NewCustomCheckbox
                        :handleCheckboxChange="handleCheckboxChange"
                        :checked="Boolean(query['has_text'])"
                        title="Len s textom"
                        name="has_text"
                        id="has_text_mobile"
                    />
                </div>
            </div>
            <div
                class="tw-w-full tw-bg-white tw-shadow-lg tw-flex tw-pb-6 tw-drop-shadow-[0_-2px_13px_rgba(0,0,0,0.1)]"
            >
                <button class="tw-bg-sky-300 tw-m-4 tw-w-full tw-p-4">zobraziť výsledky</button>
            </div>
        </div>
    </div>
</template>

<script>
import Options from './Options.vue'
import NewCustomCheckbox from './NewCustomCheckbox.vue'

export default {
    props: {
        filters: Object,
        placeholder: String,
        isExtendedOpen: Boolean,
        openedFilter: String,
        toggleIsExtendedOpen: Function,
        toggleSelect: Function,
        handleMultiSelectChange: Function,
        handleCheckboxChange: Function,
        query: Object,
        filters: Object,
    },
    components: { Options, NewCustomCheckbox },
}
</script>
