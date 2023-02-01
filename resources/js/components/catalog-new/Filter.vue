<template>
    <div class="tw-relative">
        <slot :isExtendedOpen="isExtendedOpen" />
    </div>
</template>

<script>
import NewCustomSelect from './NewCustomSelect.vue'
import NewMobileCustomSelect from './NewMobileCustomSelect.vue'

import queryString from 'query-string'
//TODO: import axios from 'axios'

function getParsedUrl() {
    return queryString.parseUrl(window.location.href, {
        arrayFormat: 'bracket',
    })
}

function stringifyUrl({ url, query }) {
    return queryString.stringifyUrl(
        { url, query },
        {
            arrayFormat: 'bracket',
        }
    )
}

export default {
    props: {
        //TODO: remove once we have api
        authors: Array,
    },
    components: { NewCustomSelect, NewMobileCustomSelect },
    data() {
        return {
            openedFilter: null,
            isExtendedOpen: true,
            isFetching: false,
            artworks: [],
            filters: {},
            query: {
                authors: [],
                ...getParsedUrl().query,
            },
        }
    },
    async created() {
        this.fetchData()
    },
    computed: {
        selectedOptionsAsLabels() {
            return Object.entries(this.query)
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
    },
    methods: {
        toggleIsExtendedOpen() {
            this.isExtendedOpen = !this.isExtendedOpen
        },
        clearSelection(filterName) {
            this.query = {
                ...this.query,
                [filterName]: undefined,
            }
            this.openedFilter = null
        },
        clearAllSelections() {
            this.query = {}
        },
        setOpenedFilter(name) {
            this.openedFilter = name
        },
        handleSortChange(sortValue) {
            this.query = {
                ...this.query,
                sort: sortValue || undefined,
            }
        },
        handleCheckboxChange(checkboxName, selected) {
            this.query = {
                ...this.query,
                [checkboxName]: selected || undefined,
            }
        },
        handleMultiSelectChange(filterName, selectedValues) {
            this.query = {
                ...this.query,
                [filterName]: selectedValues,
            }
        },
        closeOpenedFilter() {
            this.openedFilter = null
        },
        toggleSelect(filterName) {
            this.openedFilter = filterName === this.openedFilter ? null : filterName
        },
        async fetchData() {
            this.isFetching = true

            try {
                this.filters['authors'] = this.authors
                // TODO: Fetch options
                // TODO: Fetch artworks
                this.isFetching = false
            } catch (e) {
                this.isFetching = false
                throw e
            }
        },
    },
    watch: {
        query(newQuery) {
            this.fetchData()

            const { url } = getParsedUrl()

            const newUrl = queryString.stringifyUrl(
                {
                    url,
                    query: { ...newQuery },
                },
                {
                    arrayFormat: 'bracket',
                }
            )

            window.history.replaceState(
                newUrl,
                '', // unused param
                newUrl
            )
        },
    },
    provide() {
        const data = {}

        Object.defineProperty(data, 'selectedValues', {
            enumerable: true,
            get: () => this.query,
        })

        Object.defineProperty(data, 'openedFilter', {
            enumerable: true,
            get: () => this.openedFilter,
        })

        Object.defineProperty(data, 'selectedOptionsAsLabels', {
            enumerable: true,
            get: () => this.selectedOptionsAsLabels,
        })

        Object.defineProperty(data, 'filters', {
            enumerable: true,
            get: () => this.filters,
        })

        return {
            filterController: {
                handleCheckboxChange: this.handleCheckboxChange,
                handleSortChange: this.handleSortChange,
                handleMultiSelectChange: this.handleMultiSelectChange,
                toggleIsExtendedOpen: this.toggleIsExtendedOpen,
                toggleSelect: this.toggleSelect,
                closeOpenedFilter: this.closeOpenedFilter,
                clearSelection: this.clearSelection,
                clearAllSelections: this.clearAllSelections,
                data,
            },
        }
    },
}
</script>
