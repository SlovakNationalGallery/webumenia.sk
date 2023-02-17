<template>
    <div
        class="tw-fixed tw-right-0 tw-top-0 tw-z-30 tw-flex tw-h-full tw-w-full tw-flex-col md:tw-hidden"
    >
        <div
            @click="handleClose"
            class="tw-left-0 tw-h-10 tw-min-w-full tw-shrink-0 tw-bg-gray-800 tw-opacity-40"
        ></div>
        <slot name="filter-content" v-if="popoverGroupControllerData.openedPopover"></slot>
        <slot name="filter-list" v-else></slot>
        <div
            class="tw-flex tw-w-full tw-bg-white tw-pb-6 tw-shadow-lg tw-drop-shadow-[0_-2px_13px_rgba(0,0,0,0.1)]"
        >
        <button class="tw-m-4 tw-w-full tw-bg-sky-300 tw-p-4" @click="handleClose">
            zobraziť výsledky
        </button>
        </div>
    </div>
</template>

<script>
export default {
    methods: {
        handleClose() {
            this.closeOpenedPopover()
            this.$emit('close')
        },
    },
    inject: ['closeOpenedPopover', 'popoverGroupControllerData'],
}
</script>
