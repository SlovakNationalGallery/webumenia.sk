<script>
export default {
    data() {
        return {
            openedFilter: null,
        }
    },
    methods: {
        toggleSelect(filterName) {
            this.openedFilter = filterName === this.openedFilter ? null : filterName
        },
        closeOpenedFilter() {
            this.openedFilter = null
        },
    },
    render() {
        return this.$scopedSlots.default({
            openedFilter: this.openedFilter,
            toggleSelect: this.toggleSelect,
            closeOpenedFilter: this.closeOpenedFilter,
        })
    },
}
</script>
