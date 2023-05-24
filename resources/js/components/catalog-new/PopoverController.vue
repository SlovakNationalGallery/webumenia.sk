<script>
export default {
    props: {
        name: String,
        isActive: Boolean,
    },
    computed: {
        isOpen() {
            return this.popoverGroupControllerData.openedPopover === this.name
        },
    },
    inject: ['popoverGroupControllerData', 'togglePopover', 'closeOpenedPopover'],
    render() {
        return this.$scopedSlots.default({
            name: this.name,
            isOpen: this.isOpen,
            togglePopover: this.togglePopover,
            closeOpenedPopover: this.closeOpenedPopover,
            isActive: this.isActive
        })
    },
}
</script>
