<template>
    <div class="tw-pb-2">
        <button
            class="tw-border tw-border-gray-300 tw-bg-white tw-py-3.5 tw-px-4 tw-text-lg tw-font-bold hover:tw-border-gray-800"
            :class="{
                'tw-border-gray-800': active,
                'tw-border-gray-800 tw-border-2': isOpen,
            }"
            @click="togglePopover(name)"
        >
            <div class="tw-flex">
                <slot name="popover-label"></slot>
                <div class="tw-flex tw-items-center tw-pl-4">
                    <svg
                        v-if="isOpen"
                        class="tw-fill-sky-400 tw-w-4 tw-h-4"
                        xmlns="http://www.w3.org/2000/svg"
                        viewBox="0 0 256 256"
                    >
                        <path
                            d="M216.49,168.49a12,12,0,0,1-17,0L128,97,56.49,168.49a12,12,0,0,1-17-17l80-80a12,12,0,0,1,17,0l80,80A12,12,0,0,1,216.49,168.49Z"
                        ></path>
                    </svg>
                    <svg
                        v-else
                        xmlns="http://www.w3.org/2000/svg"
                        class="tw-w-4 tw-h-4 tw-fill-current"
                        viewBox="0 0 256 256"
                    >
                        <path
                            d="M216.49,104.49l-80,80a12,12,0,0,1-17,0l-80-80a12,12,0,0,1,17-17L128,159l71.51-71.52a12,12,0,0,1,17,17Z"
                        ></path>
                    </svg>
                </div>
            </div>
        </button>
        <div @click.stop v-if="isOpen" v-on-clickaway="closeOpenedPopover">
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
    },
    computed: {
        isOpen() {
            return this.popoverGroupControllerData.openedPopover === this.name
        },
    },
    inject: ['popoverGroupControllerData', 'togglePopover', 'closeOpenedPopover'],
}
</script>
