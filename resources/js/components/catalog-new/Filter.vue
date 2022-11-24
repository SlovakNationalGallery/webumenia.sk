<template>
    <div class="tw-relative">
        <slot />
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
        selectedValues() {
            return this.$route.query
        },
        filters() {
            return {
                authors: {
                    list: this.authors.map((author) => ({
                        ...author,
                        checked: this.isSelectedMultiSelect('authors', author.name),
                    })),
                },
                someOtherFilter: { list: [] },
            }
        },
    },
    methods: {
        isSelectedMultiSelect(filterName, name) {
            const urlQuery = this.$route.query
            return urlQuery[filterName] && urlQuery[filterName].includes(name)
        },
        toggleIsExtendedOpen() {
            this.isExtendedOpen = !this.isExtendedOpen
        },
        clearSelection(filterName) {
            const { [filterName]: removedFilterName, ...queryWithoutFilterName } = this.$route.query  
            this.$router.push({
                query: queryWithoutFilterName,
            })
            this.filters[filterName].search = null
            this.openedFilter = null
        },
        setOpenedFilter(name) {
            this.openedFilter = name
        },
        handleMultiSelectChange(filterName, selectedValues) {
            const urlQuery = this.$route.query
            this.$router.push({
                query: {
                    ...urlQuery,
                    [filterName]: selectedValues
                },
            })
        },
        closeOpenedFilter() {
            this.openedFilter = null
        },
        toggleSelect(filterName) {
            this.openedFilter = filterName === this.openedFilter ? null : filterName
        },
    },
    provide() {
        return {
            filterController: this,
        }
    },
}
</script>
