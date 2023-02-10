<script>
export default {
    data() {
        return {
            provided: {
                openedPopover: null,
            },
        }
    },
    methods: {
        togglePopover(filterName) {
            this.provided.openedPopover =
                filterName === this.provided.openedPopover ? null : filterName
        },
        closeOpenedPopover() {
            this.provided.openedPopover = null
        },
    },
    provide() {
        return {
            popoverGroupControllerData: this.provided,
            togglePopover: this.togglePopover,
            closeOpenedPopover: this.closeOpenedPopover,
        }
    },
    render() {
        return this.$scopedSlots.default({})
    }
}
</script>
