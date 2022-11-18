<template>
    <div
        :class="{
            'tw-hidden': !this.controller.isExtendedOpen,
        }"
        class="md:tw-hidden tw-flex tw-h-full tw-w-full tw-flex-col tw-fixed tw-top-0 tw-z-30"
    >
        <div class="tw-min-w-full tw-h-10 tw-shrink-0 tw-opacity-40 tw-left-0 tw-bg-gray-800" />
        <div class="tw-w-full tw-flex tw-flex-1 tw-overflow-auto tw-bg-white tw-flex-col">
            <div class="tw-ml-4 tw-mr-6 tw-mb-5 tw-mt-6">
                <div class="tw-flex tw-justify-between">
                    <div
                        v-if="!this.controller.openedFilter"
                        class="tw-flex tw-space-x-3 tw-items-center"
                    >
                        <span>Filter diel</span>
                    </div>
                    <div v-else>
                        <button @click="controller.setOpenedFilter(null)">
                            <i class="fa fa-chevron-left tw-text-gray-800" />
                        </button>
                        <span>{{ this.controller.openedFilter }}</span>
                    </div>
                    <button @click="controller.toggleIsExtendedOpen()">
                        <i class="fa fa-close" />
                    </button>
                </div>
            </div>
            <div v-if="this.controller.openedFilter" class="tw-px-4 tw-flex tw-flex-1 tw-min-h-0">
                <Options :filterName="this.controller.openedFilter" :placeholder="placeholder" />
            </div>
            <div class="tw-w-full tw-flex-1 tw-flex tw-flex-col tw-overflow-auto tw-min-h-0" v-else>
                <button
                    @click="controller.setOpenedFilter('authors')"
                    class="tw-w-full tw-border-b tw-font-medium tw-border-gray-200 tw-px-4 tw-py-5"
                >
                    <div class="tw-flex tw-justify-between">
                        <span>{{ 'authors' }}</span>
                        <i class="fa fa-caret-right" />
                    </div>
                </button>
                <button
                    @click="controller.setOpenedFilter('someOtherFilter')"
                    class="tw-w-full tw-border-b tw-font-medium tw-border-gray-200 tw-px-4 tw-py-5"
                >
                    <div class="tw-flex tw-justify-between">
                        <span>{{ 'someOtherFilter' }}</span>
                        <i class="fa fa-caret-right" />
                    </div>
                </button>
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

export default {
    props: {
        filters: Object,
        placeholder: String,
    },
    inject: {
        controller: {
            from: 'filterController',
        },
    },
    components: { Options },
}
</script>
