<script>
export default {
    data() {
        return {
            openedFilter: null,
        }
    },
    methods: {
        togglePopover(filterName) {
            this.openedFilter = filterName === this.openedFilter ? null : filterName
        },
        closeOpenedPopover() {
            this.openedFilter = null
        },
    },
    render() {
        return this.$scopedSlots.default({
            openedFilter: this.openedFilter,
            togglePopover: this.togglePopover,
            closeOpenedPopover: this.closeOpenedPopover,
        })
    },
}
</script>
