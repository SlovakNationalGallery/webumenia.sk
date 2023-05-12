<template>
    <div class="tw-pb-2">
        <div
            class="tw-border tw-border-transparent"
            :class="{
                'tw-border-gray-800': isOpen,
            }"
        >
            <button
                ref="button"
                class="tw-border tw-border-gray-300 tw-bg-white tw-py-2.5 tw-px-4 tw-text-lg tw-font-bold hover:tw-border-gray-800"
                :class="{
                    'tw-border-gray-800': active || isOpen,
                }"
                @click="togglePopover(name)"
            >
                <div class="tw-flex">
                    <slot name="popover-label"></slot>
                    <div class="tw-flex tw-items-center tw-pl-4">
                        <slot v-if="isOpen" name="opened-icon"> </slot>
                        <slot v-else name="closed-icon"> </slot>
                    </div>
                </div>
            </button>
        </div>
        <div ref="body" class="tw-z-50">
            <div @click.stop v-if="isOpen" v-on-clickaway="closeOpenedPopover" id="popover">
                <slot name="body"></slot>
            </div>
        </div>
    </div>
</template>

<script>
import { directive as onClickaway } from 'vue-clickaway'
import { createPopper } from '@popperjs/core'

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
    data() {
        return {
            popper: null,
        }
    },
    mounted() {
        const button = this.$refs.button
        const body = this.$refs.body
        this.popper = createPopper(button, body, {
            placement: 'bottom-start',
        })
    },
    updated() {
        this.popper.update()
    },
    inject: ['popoverGroupControllerData', 'togglePopover', 'closeOpenedPopover'],
}
</script>
