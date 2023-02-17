<template>
    <div>
        <button
            class="tw-border-b tw-w-full tw-border-gray-300 tw-bg-white tw-py-3.5 tw-px-4 tw-text-lg tw-font-bold hover:tw-border-gray-800"
            :class="{
                'tw-border-gray-800': active,
            }"
            @click="togglePopover(name)"
        >
            <div class="tw-flex">
                <slot></slot>
                <div class="tw-flex tw-ml-auto tw-items-center tw-pl-4">
                    <i class="fa fa-caret-right"></i>
                </div>
            </div>
        </button>
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
    props: {
        name: String,
        active: Boolean,
    },
    inject: ['togglePopover', 'closeOpenedPopover'],
}
</script>
