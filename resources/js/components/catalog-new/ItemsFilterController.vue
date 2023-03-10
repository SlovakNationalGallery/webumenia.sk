<script>
import qs from 'qs'
import axios from 'axios'

function getParsedUrl() {
    return qs.parse(window.location.search, {
        arrayFormat: 'bracket',
        ignoreQueryPrefix: true,
    })
}

function stringifyUrl({ url, params }) {
    const { filter, size, terms, yearRange } = params
    const { yearFrom, yearTo, author, color, is_free, has_image, has_iip, has_text, sort } =
        filter || {}

    const newQuery = {
        filter: {
            date_earliest: { lte: yearTo },
            date_latest: { gte: yearFrom },
            author: author,
            color: color,
            is_free: is_free,
            has_image: has_image,
            has_iip: has_iip,
            has_text: has_text,
        },
        sort: {
            [sort]: SORT_DIRECTIONS[sort],
        },
        terms,
        size,
        ...yearRange,
    }
    return url + '?' + qs.stringify(newQuery, { skipNulls: true, arrayFormat: 'brackets' })
}

const PAGE_SIZE = 30
const AGGREGATIONS_SIZE = 1000
const SINGLE_ITEM_FILTERS = ['color', 'yearFrom', 'yearTo']
const SORT_DIRECTIONS = {
    date_earliest: 'desc',
    date_latest: 'asc',
    created_at: 'asc',
    author: 'asc',
    title: 'asc',
    view_count: 'desc',
    random: 'asc',
}
const EMPTY_QUERY = {
    author: [],
    color: null,
    yearFrom: null,
    yearTo: null,
}
const AGGREGATION_TERMS = {
    author: 'author',
}

export default {
    data() {
        return {
            isExtendedOpen: true,
            isFetching: false,
            artworks: [],
            filters: {},
            query: { ...EMPTY_QUERY, ...getParsedUrl().filter },
        }
    },
    async created() {
        this.fetchData()
    },
    computed: {
        selectedOptionsAsLabels() {
            return Object.entries(this.query)
                .filter(([filterName, _]) =>
                    ['author', 'color', 'yearFrom', 'yearTo'].includes(filterName)
                )
                .map(([filterName, filterValues]) => {
                    if (SINGLE_ITEM_FILTERS.includes(filterName) && filterValues) {
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
                [filterName]: EMPTY_QUERY[filterName],
            }
        },
        clearAllSelections() {
            this.query = { ...EMPTY_QUERY }
        },
        handleColorChange(color) {
            this.query = {
                ...this.query,
                color: color,
            }
        },
        handleYearRangeChange(yearRangeValue) {
            this.query = {
                ...this.query,
                yearFrom: yearRangeValue.from,
                yearTo: yearRangeValue.to,
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
            if (SINGLE_ITEM_FILTERS.includes(name)) {
                this.query = {
                    ...this.query,
                    [name]: EMPTY_QUERY[name],
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
                //TODO: Year range from BE
                const filters = await axios
                    .get(
                        stringifyUrl({
                            url: '/api/v1/items/aggregations',
                            params: {
                                filter: this.query,
                                terms: AGGREGATION_TERMS,
                                size: AGGREGATION_SIZE,
                            },
                        })
                    )
                    .then(({ data }) => data)

                this.filters = {
                    ...this.filters,
                    ...filters,
                }

                this.artworks = await axios
                    .get(
                        stringifyUrl({
                            url: '/api/v1/items',
                            params: {
                                filter: this.query,
                                size: PAGE_SIZE,
                            },
                        })
                    )
                    .then(({ data }) => data)
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
            const newUrl = stringifyUrl({
                url: window.location.pathname,
                params: { filter: { ...newQuery } },
            })

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
            artworks: this.artworks,
            toggleIsExtendedOpen: this.toggleIsExtendedOpen,
            handleSortChange: this.handleSortChange,
            handleYearRangeChange: this.handleYearRangeChange,
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
