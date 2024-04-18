<script>
import qs from 'qs'
import { useApiClient } from '../composables/useApiClient'
import { useTitleUpdater } from './useTitleUpdater'

function getQueryFromCurrentUrl() {
    const parsedUrl = qs.parse(window.location.search, {
        arrayFormat: 'bracket',
        ignoreQueryPrefix: true,
    })
    const { date_earliest, date_latest, ...rest } = parsedUrl?.filter || {}
    const { sort, q } = parsedUrl || {}

    return {
        ...EMPTY_QUERY,
        ...rest,
        yearRange: (() => {
            if (!date_earliest || !date_latest) {
                return null
            }
            return { to: date_earliest?.lte, from: date_latest?.gte }
        })(),
        sort: sort && Object.keys(sort)[0],
        q,
    }
}

function stringifyUrl({ url, params }) {
    const searchParams = stringifyUrlQuery(params)
    if (!searchParams) return url

    return url + '?' + stringifyUrlQuery(params)
}

function stringifyUrlQuery(params) {
    const { filter, size, terms, page, min, max } = params
    const {
        yearRange,
        author,
        gallery,
        technique,
        tag,
        work_type,
        object_type,
        topic,
        medium,
        color,
        is_free,
        has_image,
        has_iip,
        has_text,
        sort,
        q,
    } = filter || {}

    const newQuery = {
        filter: {
            date_earliest: { lte: yearRange?.to },
            date_latest: { gte: yearRange?.from },
            author: author,
            work_type: work_type,
            object_type: object_type,
            tag: tag,
            technique: technique,
            topic: topic,
            gallery: gallery,
            medium: medium,
            color: color,
            is_free: is_free,
            has_image: has_image,
            has_iip: has_iip,
            has_text: has_text,
        },
        sort: {
            [sort]: SORT_DIRECTIONS[sort],
        },
        min,
        max,
        page,
        terms,
        size,
        q,
    }

    return qs.stringify(newQuery, { skipNulls: true, arrayFormat: 'brackets' })
}

const PAGE_SIZE = 30
const AGGREGATIONS_SIZE = 1000
const SINGLE_ITEM_FILTERS = ['color', 'yearRange']
const SORT_DIRECTIONS = {
    date_earliest: 'asc',
    date_latest: 'desc',
    created_at: 'asc',
    author: 'asc',
    title: 'asc',
    view_count: 'desc',
    updated_at: 'desc',
    random: 'asc',
}
const EMPTY_QUERY = {
    author: [],
    work_type: [],
    object_type: [],
    topic: [],
    gallery: [],
    technique: [],
    medium: [],
    tag: [],
    color: null,
    yearRange: null,
}
const AGGREGATIONS_TERMS = {
    author: 'author',
    work_type: 'work_type',
    topic: 'topic',
    object_type: 'object_type',
    tag: 'tag',
    medium: 'medium',
    technique: 'technique',
    gallery: 'gallery',
}

export default {
    setup(props) {
        const apiClient = useApiClient()
        const { refreshTitle } = useTitleUpdater(props.titleStaticPartSeparator, props.locale)

        return { apiClient, refreshTitle }
    },
    props: {
        titleStaticPartSeparator: String,
    },
    data() {
        return {
            isFetchingArtworks: false,
            hasError: false,
            artworks: [],
            last_page: 1,
            artworks_total: null,
            aggregations: {},
            query: getQueryFromCurrentUrl(),
            page: 1,
        }
    },
    created() {
        this.fetchAggregations()
        this.fetchArtworks({ replaceArtworks: true })
    },
    mounted() {
        window.addEventListener('popstate', this.handleHistoryPopState)
        window.history.scrollRestoration = 'manual' // Do not change scroll position on back/forward
    },
    beforeUnmount() {
        window.removeEventListener('popstate', this.handleHistoryPopState)
    },
    computed: {
        selectedOptionsAsLabels() {
            return Object.entries(this.query)
                .filter(([filterName, _]) =>
                    [
                        'author',
                        'technique',
                        'topic',
                        'medium',
                        'work_type',
                        'object_type',
                        'tag',
                        'gallery',
                        'color',
                        'yearRange',
                    ].includes(filterName)
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
        handleHistoryPopState() {
            this.query = getQueryFromCurrentUrl()
        },
        hasFilterOptions(filterName) {
            return (
                this.query[filterName].length ||
                (this.aggregations[filterName] && this.aggregations[filterName].length > 0)
            )
        },
        clearFilterSelection(filterName) {
            this.updateQuery({ [filterName]: EMPTY_QUERY[filterName] })
        },
        clearAllSelections() {
            this.replaceQuery(EMPTY_QUERY)
        },
        handleColorChange(color) {
            this.updateQuery({ color })
        },
        handleYearRangeChange(yearRange) {
            this.updateQuery({ yearRange })
        },
        handleSortChange(sort) {
            this.updateQuery({ sort })
        },
        handleCheckboxChange(e) {
            const { name, checked } = e.target

            this.updateQuery({
                [name]: checked || undefined,
            })
        },
        removeSelection({ filterName: name, value }) {
            if (SINGLE_ITEM_FILTERS.includes(name)) {
                this.updateQuery({
                    [name]: EMPTY_QUERY[name],
                })
                return
            }

            this.updateQuery({
                [name]: this.query[name].filter((v) => v !== value),
            })
        },
        handleMultiSelectChange(name, e) {
            const { checked, value } = e.target
            if (checked) {
                // Add to query
                this.updateQuery({
                    [name]: [...this.query[name], value],
                })
                return
            }
            // Remove from query
            this.updateQuery({
                [name]: this.query[name].filter((v) => v !== value),
            })
        },
        loadMore() {
            this.page++
        },
        handleSelectRandomly() {
            this.replaceQuery({
                ...EMPTY_QUERY,
                sort: 'random',
                has_image: true,
            })
        },
        async fetchAggregations() {
            try {
                const aggregations = await this.apiClient
                    .get(
                        stringifyUrl({
                            url: '/api/v1/items/aggregations',
                            params: {
                                filter: this.query,
                                terms: AGGREGATIONS_TERMS,
                                size: AGGREGATIONS_SIZE,
                                min: { date_earliest: 'date_earliest' },
                                max: { date_latest: 'date_latest' },
                            },
                        })
                    )
                    .then(({ data }) => data)

                this.aggregations = {
                    ...this.aggregations,
                    ...aggregations,
                }
            } catch (e) {
                this.hasError = true
                throw e
            }
        },
        async fetchArtworks({ append }) {
            this.isFetchingArtworks = true
            try {
                const fetchedArtworks = await this.apiClient
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

                this.last_page = fetchedArtworks.last_page
                this.artworks_total = fetchedArtworks.total
                this.artworks = append
                    ? [...this.artworks, ...fetchedArtworks.data]
                    : fetchedArtworks.data
            } catch (e) {
                this.hasError = true
                throw e
            } finally {
                this.isFetchingArtworks = false
            }
        },
        updateQuery(data, { replace = false } = {}) {
            console.log('updateQuery', data, replace)
            this.query = replace ? { ...data } : { ...this.query, ...data }

            window.history.pushState(
                JSON.parse(JSON.stringify(this.query)),
                '', // unused param
                stringifyUrl({
                    url: window.location.pathname,
                    params: { filter: { ...this.query } },
                })
            )
        },
        replaceQuery(data) {
            this.updateQuery(data, { replace: true })
        },
    },
    watch: {
        page(newPage, oldPage) {
            if (newPage > oldPage) {
                this.fetchArtworks({ append: true })
            }
        },
        query(newQuery) {
            const newParams = { filter: { ...newQuery } }

            this.page = 1
            this.refreshTitle(stringifyUrlQuery(newParams))
            this.fetchAggregations()
            this.fetchArtworks({ append: false })
        },
    },
    render() {
        return this.$slots.default({
            isFetchingArtworks: this.isFetchingArtworks,
            hasError: this.hasError,
            query: this.query,
            page: this.page,
            last_page: this.last_page,
            aggregations: this.aggregations,
            artworks: this.artworks,
            artworks_total: this.artworks_total,
            hasFilterOptions: this.hasFilterOptions,
            loadMore: this.loadMore,
            handleSortChange: this.handleSortChange,
            handleYearRangeChange: this.handleYearRangeChange,
            handleCheckboxChange: this.handleCheckboxChange,
            handleMultiSelectChange: this.handleMultiSelectChange,
            handleColorChange: this.handleColorChange,
            handleSelectRandomly: this.handleSelectRandomly,
            selectedOptionsAsLabels: this.selectedOptionsAsLabels,
            clearAllSelections: this.clearAllSelections,
            clearFilterSelection: this.clearFilterSelection,
            removeSelection: this.removeSelection,
        })
    },
}
</script>
