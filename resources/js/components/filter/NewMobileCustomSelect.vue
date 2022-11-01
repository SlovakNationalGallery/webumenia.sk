<template>
    <div class="md:tw-hidden tw-flex tw-h-full tw-w-full tw-flex-col tw-fixed tw-top-0 tw-z-30">
        <div class="tw-min-w-full tw-h-10 tw-shrink-0 tw-opacity-40 tw-left-0 tw-bg-gray-800" />
        <div class="tw-w-full tw-flex tw-flex-1 tw-overflow-auto tw-bg-white tw-flex-col">
            <div class="tw-ml-4 tw-mr-6 tw-mb-5 tw-mt-6">
                <div class="tw-flex tw-justify-between">
                    <div v-if="opened" class="tw-flex tw-space-x-3 tw-items-center">
                        <i class="fa fa-chevron-left tw-text-gray-800" />
                        <span>autor/autorka</span>
                    </div>
                    <div v-else>Filter diel</div>
                    <i class="fa fa-close" />
                </div>
            </div>
            <div v-if="opened" class="tw-px-4 tw-flex tw-flex-1 tw-min-h-0">
                <Options :options="mockOptions" :placeholder="placeholder" />
            </div>
            <div class="tw-w-full tw-flex-1 tw-flex tw-flex-col tw-overflow-auto tw-min-h-0" v-else>
                <button
                    v-for="i in 15"
                    class="tw-w-full tw-border-b tw-font-medium tw-border-gray-200 tw-px-4 tw-py-5"
                >
                    <div class="tw-flex tw-justify-between">
                        <span> autor / autorka </span>
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
import mockOptions from './mock.json'
import Options from './Options.vue'

export default {
    props: {
        name: String,
        placeholder: String,
        active: Boolean,
        opened: Boolean,
    },
    data() {
        return {
            mockOptions: mockOptions,
        }
    },
    components: { Options },
}
</script>
