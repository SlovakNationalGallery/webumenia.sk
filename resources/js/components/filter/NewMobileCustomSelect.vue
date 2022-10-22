<template>
    <div class="md:tw-hidden">
        <div
            class="tw-min-w-full tw-top-0 tw-opacity-40 tw-left-0 tw-fixed tw-z-20 tw-min-h-screen tw-bg-gray-800"
        />
        <div
            class="tw-fixed tw-w-full tw-h-[calc(100%-11.5rem)] tw-overflow-auto tw-top-10 tw-pt-6 tw-z-30 tw-bg-white tw-flex tw-flex-col tw-mt-8"
        >
            <div class="tw-ml-4 tw-mr-6 tw-mb-5">
                <div class="tw-flex tw-justify-between">
                    <div v-if="opened" class="tw-flex tw-space-x-3 tw-items-center">
                        <font-awesome-icon
                            icon="fa-solid fa-chevron-left"
                            class="tw-text-gray-800"
                        />
                        <span>autor/autorka</span>
                    </div>
                    <div v-else>Filter diel</div>

                    <font-awesome-icon icon="fa-solid fa-xmark" class="tw-text-gray-800" />
                </div>
            </div>
            <div v-if="opened">Options will go here :)</div>
            <div v-else>
                <button
                    v-for="i in 15"
                    class="tw-w-full tw-border-b tw-font-medium tw-border-gray-200 tw-px-4 tw-py-5"
                >
                    <div class="tw-flex tw-justify-between">
                        <span> autor / autorka </span>
                        <font-awesome-icon
                            icon="fa-solid fa-caret-right"
                            class="tw-text-gray-800"
                        />
                    </div>
                </button>
            </div>
            <div
                class="tw-fixed tw-bottom-0 tw-w-full tw-bg-white tw-shadow-lg tw-flex tw-pb-6 tw-drop-shadow-[0_-2px_13px_rgba(0,0,0,0.1)]"
            >
                <button class="tw-bg-sky-300 tw-m-4 tw-w-full tw-p-4">zobraziť výsledky</button>
            </div>
        </div>
    </div>
</template>

<script>
//import Options from './Options.vue'
import '@fortawesome/vue-fontawesome'

export default {
    props: {
        name: String,
        placeholder: String,
        active: Boolean,
        opened: Boolean,
    },
    // components: { Options },
}
</script>
