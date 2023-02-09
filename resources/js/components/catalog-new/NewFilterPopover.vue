<template>
    <div class="tw-min-w-max md:tw-pb-2">
        <button
            class="tw-border tw-border-gray-300 tw-bg-white tw-py-3.5 tw-px-4 tw-text-lg tw-font-bold hover:tw-border-gray-800"
            :class="{
                'tw-border-gray-800': active,
                'tw-border-gray-800 tw-border-2': openedFilter === name,
            }"
            @click="toggleSelect(name)"
        >
            <div class="tw-flex">
                <slot name="popover-label"></slot>
                <div class="tw-flex tw-items-center tw-pl-4">
                    <i v-if="openedFilter === name" class="fa fa-caret-up tw-text-sky-400"></i>
                    <i v-else class="fa fa-caret-down"></i>
                </div>
            </div>
        </button>
        <div @click.stop v-if="openedFilter === name" v-on-clickaway="closeOpenedFilter">
            <slot name="body"></slot>
        </div>
    </div>
</template>

<script>
import { directive as onClickaway } from 'vue-clickaway'

export default {
    directives: {
        onClickaway: onClickaway,
    },
    props: {
        name: String,
        active: Boolean,
        openedFilter: String,
        toggleSelect: Function,
        closeOpenedFilter: Function,
    },
}
</script>
