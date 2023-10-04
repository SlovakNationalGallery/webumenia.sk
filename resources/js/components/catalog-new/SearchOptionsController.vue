<script>
import { matchSorter } from 'match-sorter'

export default {
    props: ['options', 'selected'],
    data() {
        return {
            search: '',
        }
    },
    computed: {
        filteredOptions() {
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
            return matchSorter(optionsWithSelected, this.search, {
                keys: [(option) => option.value.replace(/^([^,]*),\s*(.*)$/, '$2 $1')],
            })
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
