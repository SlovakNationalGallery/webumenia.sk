<script>
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
            skipNull: true,
        }
    )
}

const singleItemFilters = ['color']
const emptyQuery = {
    authors: [],
    color: null,
}

export default {
    props: {
        //TODO: remove once we have api
        authors: Array,
    },
    data() {
        return {
            isExtendedOpen: true,
            isFetching: false,
            artworks: [],
            filters: {},
            query: { ...emptyQuery, ...getParsedUrl().query },
        }
    },
    async created() {
        this.fetchData()
    },
    computed: {
        selectedOptionsAsLabels() {
            return Object.entries(this.query)
                .filter(([filterName, _]) => ['authors', 'color'].includes(filterName))
                .map(([filterName, filterValues]) => {
                    if (singleItemFilters.includes(filterName) && filterValues) {
                        return {
                            value: filterValues,
                            filterName,
                        }
                    }
                    return (filterValues || []).map((filterValue) => ({
                        value: filterValue,
                        filterName,
                    }))
                })
                .flat()
        },
    },
    methods: {
        toggleIsExtendedOpen() {
            this.isExtendedOpen = !this.isExtendedOpen
        },
        clearFilterSelection(filterName) {
            this.query = {
                ...this.query,
                [filterName]: emptyQuery[filterName],
            }
        },
        clearAllSelections() {
            this.query = { ...emptyQuery }
        },
        handleColorChange(color) {
            this.query = {
                ...this.query,
                color: color,
            }
        },
        handleSortChange(sortValue) {
            this.query = {
                ...this.query,
                sort: sortValue,
            }
        },
        handleCheckboxChange(e) {
            const { name, checked } = e.target

            this.query = {
                ...this.query,
                [name]: checked || undefined,
            }
        },
        removeSelection({ filterName: name, value }) {
            if (singleItemFilters.includes(name)) {
                this.query = {
                    ...this.query,
                    [name]: undefined,
                }
                return
            }

            this.query = {
                ...this.query,
                [name]: this.query[name].filter((v) => v !== value),
            }
        },
        handleMultiSelectChange(e) {
            const { name, checked, value } = e.target
            if (checked) {
                // Add to query
                this.query = {
                    ...this.query,
                    [name]: [...this.query[name], value],
                }
                return
            }
            // Remove from query
            this.query = {
                ...this.query,
                [name]: this.query[name].filter((v) => v !== value),
            }
        },
        async fetchData() {
            this.isFetching = true

            try {
                this.filters = { ...this.filters, authors: this.authors }
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

            const newUrl = stringifyUrl({ url, query: { ...newQuery } })

            window.history.replaceState(
                newUrl,
                '', // unused param
                newUrl
            )
        },
    },
    render() {
        return this.$scopedSlots.default({
            isExtendedOpen: this.isExtendedOpen,
            query: this.query,
            filters: this.filters,
            toggleIsExtendedOpen: this.toggleIsExtendedOpen,
            handleSortChange: this.handleSortChange,
            handleCheckboxChange: this.handleCheckboxChange,
            handleMultiSelectChange: this.handleMultiSelectChange,
            handleColorChange: this.handleColorChange,
            selectedOptionsAsLabels: this.selectedOptionsAsLabels,
            clearAllSelections: this.clearAllSelections,
            clearFilterSelection: this.clearFilterSelection,
            removeSelection: this.removeSelection,
        })
    },
}
</script>
