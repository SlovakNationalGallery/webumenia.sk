<template>
    <div class="tw-min-w-max">
        <button
            class="tw-bg-white tw-border hover:tw-border-gray-800 tw-border-gray-300 tw-text-lg tw-font-bold tw-py-3.5 tw-px-4"
            :class="{
                'tw-border-gray-800': active,
                'tw-border-gray-800 tw-border-2': controller.openedFilter === filterName,
            }"
            @click="controller.toggleSelect(filterName)"
        >
            <div class="tw-flex">
                <span>{{ filterName }}</span>
                <div class="tw-pl-4 tw-flex tw-items-center">
                    <i
                        v-if="controller.openedFilter === filterName"
                        class="fa fa-caret-up tw-text-sky-400"
                    />
                    <i v-else class="fa fa-caret-down" />
                </div>
            </div>
        </button>
        <div
            v-if="controller.openedFilter === filterName"
            v-on-clickaway="controller.closeOpenedFilter"
            class="tw-w-[20rem] tw-h-[30rem] tw-flex tw-flex-col tw-border-2 tw-items-start tw-p-6 tw-bg-white tw-border-gray-800 tw-z-10 tw-absolute tw-top-36"
        >
            <Options :placeholder="placeholder" :filterName="filterName" />
            <button
                class="tw-bg-white tw-mb-6 tw-mt-5 tw-px-4 tw-font-normal tw-py-1.5 tw-text-sm tw-border tw-border-gray-300 hover:tw-border-gray-800"
                @click="controller.clearSelection(filterName)"
            >
                <div class="tw-flex">
                    <div class="tw-pr-2 tw-flex tw-items-center">
                        <i class="fa fa-rotate-left" />
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
        filterName: String,
        placeholder: String,
        active: Boolean,
    },
    inject: {
        controller: {
            from: 'filterController',
        },
    },
    components: { Options },
}
</script>
