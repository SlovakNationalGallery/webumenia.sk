<template>
    <div class="tw-relative">
        <slot :isExtendedOpen="isExtendedOpen"/>
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
        checkboxes() {
            return {
                has_image: {
                    title: 'Len s obrázkom',
                    checked: this.isSelectedCheckbox('has_image'),
                },
                has_iip: {
                    title: 'Len so zoomom',
                    checked: this.isSelectedCheckbox('has_iip'),
                },
                has_text: {
                    title: 'Len s textom',
                    checked: this.isSelectedCheckbox('has_text'),
                },
                is_free: {
                    title: 'Len voľné',
                    checked: this.isSelectedCheckbox('is_free'),
                },
            }
        },
    },
    methods: {
        isSelectedMultiSelect(filterName, name) {
            const urlQuery = this.$route.query
            return urlQuery[filterName] && urlQuery[filterName].includes(name)
        },
        isSelectedCheckbox(checkboxName) {
            const urlQuery = this.$route.query
            return urlQuery[checkboxName]
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
        handleCheckboxChange(checkboxName, selected) {
            const { [checkboxName]: removedCheckboxName, ...queryWithoutCheckboxName } =
                this.$route.query

            this.$router.push({
                path: 'katalog-new',
                query: selected
                    ? { ...queryWithoutCheckboxName, [checkboxName]: 1 }
                    : {
                        ...queryWithoutCheckboxName,
                    },
            })
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
