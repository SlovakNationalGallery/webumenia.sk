<script>
import { formatNum } from "../../formatters"

export default {
    props: ['options', 'selected', 'formatter'],
    data() {
        return {
            search: '',
        }
    },
    computed: {
        filteredOptions() {
            // TODO better matching algorithm?
            const query = this.search.toLowerCase()
            const optionsWithSelected = [
                ...this.options.map((option) => ({
                    ...option,
                    checked: this.selected.includes(option.value),
                })),
                ...this.selected
                    .filter((queryItem) =>
                        this.options.every((option) => option.value !== queryItem)
                    )
                    .map((selected) => ({ value: selected, count: 0, checked: true })),
            ]

            return optionsWithSelected.filter((option) =>
                option.value.toLowerCase().includes(query)
            )
        },
    },
    render() {
        return this.$scopedSlots.default({
            search: this.search,
            onSearchInput: (e) => (this.search = e.target.value),
            options: this.filteredOptions,
            count: formatNum(this.filteredOptions.length),
            formatter: this.formatter,
        })
    },
}
</script>
