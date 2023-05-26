<template>
    <div ref="button">
        <slot
            name="button"
            :popoverName="name"
            :isOpen="isOpen"
            :togglePopover="togglePopover"
        ></slot>
    </div>
    <div ref="body" class="tw-z-10">
        <slot
            name="body"
            :isOpen="this.isOpen"
            :closeOpenedPopover="this.closeOpenedPopover"
        ></slot>
    </div>
</template>
<script>
import { createPopper } from '@popperjs/core'
export default {
    props: {
        name: String,
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
    computed: {
        isOpen() {
            return this.popoverGroupControllerData.openedPopover === this.name
        },
    },
    inject: ['popoverGroupControllerData', 'togglePopover', 'closeOpenedPopover'],
}
</script>
