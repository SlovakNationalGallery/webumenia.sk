<script>
export default {
    props: {
        options: Array,
        sort: String,
        handleSortChange: Function,
    },
    computed: {
        selectedOptionValue() {
            const selectedOption = this.options.find((sortItem) => this.sort === sortItem.value)
            return selectedOption ? selectedOption.text : 'poslednej zmeny'
        },
        selectableOptions() {
            return this.options.filter((sortItem) => this.sort !== sortItem.value)
        },
    },
    methods: {
        onSortChange(sortValue) {
            this.handleSortChange(sortValue)
        },
    },
    render() {
        return this.$scopedSlots.default({
            selectableOptions: this.selectableOptions,
            onSortChange: this.onSortChange,
            selectedOptionValue: this.selectedOptionValue,
        })
    },
}
</script>
