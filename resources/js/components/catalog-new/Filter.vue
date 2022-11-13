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
                path: 'katalog-new',
                query: queryWithoutFilterName,
            })
            this.filters[filterName].search = null
            this.openedFilter = null
        },
        setOpenedFilter(name) {
            this.openedFilter = name
        },
        handleChangeMultiSelect(filterName, value, selected) {
            const urlQuery = this.$route.query
            const filterNameVals = urlQuery[filterName] || []

            this.$router.push({
                path: 'katalog-new',
                query: {
                    ...urlQuery,
                    [filterName]: selected
                        ? [...filterNameVals, value]
                        : filterNameVals.filter((filterNameVal) => value !== filterNameVal),
                },
            })
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
