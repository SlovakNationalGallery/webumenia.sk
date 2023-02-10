<script>
export default {
    data() {
        return {
            openedPopover: null,
        }
    },
    methods: {
        togglePopover(filterName) {
            this.openedPopover = filterName === this.openedPopover ? null : filterName
        },
        closeOpenedPopover() {
            this.openedPopover = null
        },
    },
    render() {
        return this.$scopedSlots.default({
            openedPopover: this.openedPopover,
            togglePopover: this.togglePopover,
            closeOpenedPopover: this.closeOpenedPopover,
        })
    },
}
</script>
