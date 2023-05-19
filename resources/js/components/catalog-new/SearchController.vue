<script>
export default {
    props: ['options', 'selected'],
    data() {
        return {
            search: '',
        }
    },
    computed: {
        filteredOptions() {
            // TODO better matching algorithm?
            const query = this.search.toLowerCase()
            return this.options
                .filter((option) => option.value.toLowerCase().includes(query))
                .map((option) => ({
                    ...option,
                    checked: this.selected.includes(option.value),
                }))
        },
    },
    render() {
        return this.$scopedSlots.default({
            search: this.search,
            onSearchInput: (e) => (this.search = e.target.value),
            options: this.filteredOptions,
        })
    },
}
</script>
