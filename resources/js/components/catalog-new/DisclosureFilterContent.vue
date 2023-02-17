<template>
    <div v-if="isOpen" class="bg-white tw-h-screen">
        <div class="tw-ml-4 tw-mr-6 tw-mb-5 tw-mt-6">
            <button @click="closeOpenedPopover()">
                <i class="fa fa-chevron-left tw-text-gray-800"></i>
            </button>
            <slot name="header-label"></slot>
            <button @click="handleClose">
                <i class="fa fa-close"></i>
            </button>
        </div>
        <slot name="body"></slot>
    </div>
</template>

<script>
export default {
    props: {
        name: String,
    },
    methods: {
        handleClose() {
            this.closeOpenedPopover()
            this.$emit('close')
        },
    },
    computed: {
        isOpen() {
            return this.popoverGroupControllerData.openedPopover === this.name
        },
    },
    inject: ['popoverGroupControllerData', 'closeOpenedPopover'],
}
</script>
