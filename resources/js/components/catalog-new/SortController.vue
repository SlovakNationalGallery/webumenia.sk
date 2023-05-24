<script>
export default {
    props: {
        options: Array,
        sort: String,
        handleSortChange: Function,
    },
    data() {
        return {
            isOpen: false,
        }
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
        toggleIsOpen() {
            this.isOpen = !this.isOpen
        },
        onSortChange(sortValue) {
            this.toggleIsOpen()
            this.handleSortChange(sortValue)
        },
    },
    render() {
        return this.$scopedSlots.default({
            isOpen: this.isOpen,
            toggleIsOpen: this.toggleIsOpen,
            selectableOptions: this.selectableOptions,
            onSortChange: this.onSortChange,
            selectedOptionValue: this.selectedOptionValue,
        })
    },
}
</script>
