<template>
    <div class="tw-relative">
        <slot :isExtendedOpen="isExtendedOpen" />
    </div>
</template>

<script>
import NewCustomSelect from './NewCustomSelect.vue'
import NewMobileCustomSelect from './NewMobileCustomSelect.vue'

export default {
    props: {
        authors: Array,
    },
    components: { NewCustomSelect, NewMobileCustomSelect },
    data() {
        return {
            openedFilter: null,
            isExtendedOpen: true,
        }
    },
    computed: {
        selectedOptionsAsLabels() {
            return Object.entries(this.selectedValues)
                .filter(([filterName, _]) => Object.keys(this.filters).includes(filterName))
                .map(([filterName, filterValues]) =>
                    (filterValues || []).map((filterValue) => ({
                        value: filterValue,
                        filterName,
                        type: 'string',
                    }))
                )
                .flat()
        },
        selectedValues() {
            return {
                ...this.$route.query,
                authors:
                    typeof this.$route.query.authors === 'string'
                        ? [this.$route.query.authors]
                        : this.$route.query.authors,
            }
        },
        sort() {
            return this.getSort()
        },
        filters() {
            return {
                authors: this.authors.map((author) => ({
                        ...author,
                    })),
                someOtherFilter: [],
            }
        },
    },
    methods: {
        getSort() {
            const urlQuery = this.$route.query
            return urlQuery["sort"] || "updated_at"
        },
        toggleIsExtendedOpen() {
            this.isExtendedOpen = !this.isExtendedOpen
        },
        clearSelection(filterName) {
            this.$router.push({
                query: {
                    ...this.$route.query,
                    [filterName]: undefined,
                },
            })
            this.openedFilter = null
        },
        clearAllSelections() {
            this.$router.push({
                query: {},
            })
        },
        setOpenedFilter(name) {
            this.openedFilter = name
        },
        handleSort(event) {
            const { "sort": sort, ...queryWithoutSort } =
                this.$route.query
            const value = event.target[event.target.selectedIndex].value
            this.$router.push({
                path: 'katalog-new',
                query: value === "updated_at"
                    ? { ...queryWithoutSort }
                    : {
                        ...queryWithoutSort,
                        sort: value
                    },
            })
            
        },
        handleCheckboxChange(checkboxName, selected) {
            this.$router.push({
                query: {
                    ...this.$route.query,
                    [checkboxName]: selected || undefined,
                },
            })
        },
        handleMultiSelectChange(filterName, selectedValues) {
            const urlQuery = this.$route.query
            this.$router.push({
                query: {
                    ...urlQuery,
                    [filterName]: selectedValues,
                },
            })
        },
        closeOpenedFilter() {
            this.openedFilter = null
        },
        toggleSelect(filterName) {
            this.openedFilter = filterName === this.openedFilter ? null : filterName
        },
        closeOpenedFilter() {
            this.openedFilter = null
        },
    },
    provide() {
        return {
            filterController: this,
        }
    },
}
</script>
