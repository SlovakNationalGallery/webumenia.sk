<template>
    <div class="tw-min-w-max md:tw-pb-2">
        <button
            class="tw-bg-white tw-border hover:tw-border-gray-800 tw-border-gray-300 tw-text-lg tw-font-bold tw-py-3.5 tw-px-4"
            :class="{
                'tw-border-gray-800': active,
                'tw-border-gray-800 tw-border-2': controller.data.openedFilter === name,
            }"
            @click="controller.toggleSelect(name)"
        >
            <div class="tw-flex">
                <span
                    >{{ name }}
                    <span v-if="this.selectedCount" class="tw-font-bold"
                        >({{ this.selectedCount }})</span
                    >
                </span>
                <div class="tw-pl-4 tw-flex tw-items-center">
                    <i
                        v-if="controller.data.openedFilter === name"
                        class="fa fa-caret-up tw-text-sky-400"
                    ></i>
                    <i v-else class="fa fa-caret-down"></i>
                </div>
            </div>
        </button>
        <div
            @click.stop
            v-if="controller.data.openedFilter === name"
            v-on-clickaway="controller.closeOpenedFilter"
            class="tw-w-[20rem] tw-h-[30rem] tw-flex tw-flex-col tw-border-2 tw-items-start tw-p-6 tw-bg-white tw-border-gray-800 tw-z-10 tw-absolute tw-top-36"
        >
            <Options :placeholder="placeholder" :filterName="name" />
            <button
                class="tw-bg-white tw-mb-6 tw-mt-5 tw-px-4 tw-font-normal tw-py-1.5 tw-text-sm tw-border tw-border-gray-300 hover:tw-border-gray-800"
                @click="controller.clearFilterSelection(name)"
            >
                <div class="tw-flex">
                    <div class="tw-pr-2 tw-flex tw-items-center">
                        <i class="fa fa-rotate-left"></i>
                    </div>
                    <span>zrušiť výber</span>
                </div>
            </button>
        </div>
    </div>
</template>

<script>
import Options from './Options.vue'
import { directive as onClickaway } from 'vue-clickaway'

export default {
    directives: {
        onClickaway: onClickaway,
    },
    props: {
        name: String,
        placeholder: String,
        active: Boolean,
    },
    computed: {
        selectedCount() {
            const selectedValues = this.controller.data.selectedValues[this.name]
            return selectedValues ? selectedValues.length : 0
        },
    },
    inject: {
        controller: {
            from: 'filterController',
        },
    },
    components: { Options },
}
</script>
