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
            urlQuery: {
                authors: [],
            },
            //this will be removed once we connect to BE
            filters: {
                authors: {
                    list: this.authors.map((author) => ({ ...author, checked: false })),
                    search: null,
                },
            },
        }
    },
    methods: {
        toggleIsExtendedOpen() {
            this.isExtendedOpen = !this.isExtendedOpen
        },
        clearSelection(filterName) {
            this.filters[filterName].list.map(el => el.checked = false)
            this.filters[filterName].search = null
            this.openedFilter = null

            this.urlQuery[filterName] = []
        },
        setOpenedFilter(name) {
            this.openedFilter = name
        },
        addParameterToUrlQuery(filterName, name, value) {
            const index = this.filters[filterName].list.findIndex((el) => el.name === name)
            this.filters[filterName].list[index].checked = value
            value
                ? this.urlQuery[filterName].push(name)
                : this.urlQuery[filterName].splice(this.urlQuery[filterName].indexOf(value), 1)
        },
        toggleSelect(filterName) {
            this.openedFilter = filterName === this.openedFilter ? null : filterName
        },
        updateUrlQuery() {
            const url = new URL(window.location.href)
            Object.keys(this.urlQuery).forEach((filterName) => {
                const filterValues = this.urlQuery[filterName]
                filterValues.length
                    ? url.searchParams.set(filterName, filterValues)
                    : url.searchParams.delete(filterName)
            })
            url.searchParams.delete('page')
            window.history.replaceState(null, null, url)
        },
    },
    watch: {
        urlQuery: {
            handler() {
                this.updateUrlQuery()
            },
            deep: true,
        },
    },
    provide() {
        return {
            filterController: this,
        }
    },
}
</script>
