<script>
import { createPopper } from '@popperjs/core'

export default {
    props: {
        name: String,
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
        const button = document.getElementById(`button-${this.name}`)
        const body = document.getElementById(`body-${this.name}`)
        this.popper = createPopper(button, body, {
            placement: 'bottom-start',
        })
    },
    updated() {
        this.popper.update()
    },
    inject: ['popoverGroupControllerData', 'togglePopover', 'closeOpenedPopover'],
    render() {
        return this.$scopedSlots.default({
            name: this.name,
            isOpen: this.isOpen,
            togglePopover: this.togglePopover,
            closeOpenedPopover: this.closeOpenedPopover,
        })
    },
}
</script>
