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
    const { filter, size, terms, yearRange, page } = params
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
        page,
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
const AGGREGATIONS_TERMS = {
    author: 'author',
}

export default {
    data() {
        return {
            isExtendedOpen: true,
            isFetchingArtworks: false,
            artworks: [],
            aggregations: {},
            query: { ...EMPTY_QUERY, ...getParsedUrl().filter },
            page: null,
        }
    },
    async created() {
        this.fetchAggregations()
        this.fetchArtworks({ replaceArtworks: true })
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
        loadMore() {
            this.page = this.page ? this.page + 1 : 2
        },
        async fetchAggregations() {
            try {
                const aggregations = await axios
                    .get(
                        stringifyUrl({
                            url: '/api/v1/items/aggregations',
                            params: {
                                filter: this.query,
                                terms: AGGREGATIONS_TERMS,
                                size: AGGREGATIONS_SIZE,
                            },
                        })
                    )
                    .then(({ data }) => data)

                this.aggregations = {
                    ...this.aggregations,
                    ...aggregations,
                }
            } catch (e) {
                throw e
            }
        },
        async fetchArtworks({ append }) {
            this.isFetchingArtworks = true
            try {
                const fetchedArtworks = await axios
                    .get(
                        stringifyUrl({
                            url: '/api/v1/items',
                            params: {
                                filter: this.query,
                                size: PAGE_SIZE,
                                page: this.page,
                            },
                        })
                    )
                    .then(({ data }) => data)

                this.artworks = append
                    ? [...this.artworks, ...fetchedArtworks.data]
                    : fetchedArtworks.data
            } catch (e) {
                throw e
            } finally {
                this.isFetchingArtworks = false
            }
        },
    },
    watch: {
        page(newPage, oldPage) {
            if (newPage > oldPage) {
                this.fetchArtworks({ append: true })
            }
        },
        query(newQuery) {
            this.page = null
            this.fetchAggregations()
            this.fetchArtworks({ append: false })
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
            isFetchingArtworks: this.isFetchingArtworks,
            query: this.query,
            page: this.page,
            aggregations: this.aggregations,
            artworks: this.artworks,
            toggleIsExtendedOpen: this.toggleIsExtendedOpen,
            loadMore: this.loadMore,
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
