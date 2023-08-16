<template>
    <div ref="button">
        <slot
            name="button"
            :popoverName="name"
            :isOpen="isOpen"
            :togglePopover="togglePopover"
        ></slot>
    </div>
    <transition
        enter-active-class="duration-300 ease-out"
        enter-from-class="transform opacity-0"
        enter-to-class="opacity-100"
        leave-active-class="duration-200 ease-in"
        leave-from-class="opacity-100"
        leave-to-class="transform opacity-0"
    >
        <div ref="body" class="tw-z-10">
            <slot
                name="body"
                :isOpen="this.isOpen"
                :closeOpenedPopover="this.closeOpenedPopover"
            ></slot>
        </div>
    </transition>
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
