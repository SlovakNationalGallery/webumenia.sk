<template>
    <div class="tw-relative">
        <slot :filters="filters" />
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
            currentRoute: window.location.href,
            openedFilter: null,
            isExtendedOpen: true,
            filters: {
                authors: {
                    list: this.authors.map((author) => ({
                        ...author,
                        checked: this.isSelectedMultiSelect('authors', author.name),
                    })),
                },
                someOtherFilter: { list: [] },
            },
        }
    },
    methods: {
        isSelectedMultiSelect(filterName, name) {
            const urlQuery = this.getUrlQuery()
            return urlQuery[filterName] && urlQuery[filterName].includes(name)
        },
        toggleIsExtendedOpen() {
            this.isExtendedOpen = !this.isExtendedOpen
        },
        clearSelection(filterName) {
            this.filters[filterName].list.map((el) => (el.checked = false))
            this.filters[filterName].search = null
            this.openedFilter = null
            this.urlQuery[filterName] = []
        },
        setOpenedFilter(name) {
            this.openedFilter = name
        },
        getUrlQuery() {
            const url = new URL(window.location.href)
            const query = {}
            url.searchParams.forEach((value, key) => {
                query[key] ? query[key].push(value) : (query[key] = [value])
            })
            return query
        },
        updateUrlQuery(query) {
            const url = new URL(window.location.href)
            Object.keys(query).map((key) => {
                url.searchParams.delete(key)
                query[key].map((value) => url.searchParams.append(key, value))
            })
            url.searchParams.delete('page')
            window.history.pushState(null, null, url)
        },
        handleChangeMultiSelect(filterName, value, selected) {
            const urlQuery = this.getUrlQuery()
            const filterNameVals = urlQuery[filterName] || []

            this.updateUrlQuery({
                ...urlQuery,
                [filterName]: selected
                    ? [...filterNameVals, value]
                    : filterNameVals.filter((filterNameVal) => value !== filterNameVal),
            })

            this.filters.authors = {
                list: this.authors.map((author) => ({
                    ...author,
                    checked: this.isSelectedMultiSelect('authors', author.name),
                })),
            }
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
