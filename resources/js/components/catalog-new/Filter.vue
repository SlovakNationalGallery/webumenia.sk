<template>
    <div class="tw-relative">
        <slot :isExtendedOpen="isExtendedOpen" />
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
                    checked: this.isSelectedCheckbox('has_image'),
                },
                has_iip: {
                    checked: this.isSelectedCheckbox('has_iip'),
                },
                has_text: {
                    checked: this.isSelectedCheckbox('has_text'),
                },
                is_free: {
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
            this.$router.push({
                query: {
                    ...this.$route.query,
                    [filterName]: undefined,
                },
            })
            this.openedFilter = null
        },
        clearAllSelections() {
            this.$router.push({
                query: {},
            })
        },
        setOpenedFilter(name) {
            this.openedFilter = name
        },
        handleCheckboxChange(checkboxName, selected) {
            this.$router.push({
                query: {
                    ...this.$route.query,
                    [checkboxName]: selected || undefined,
                },
            })
        },
        handleMultiSelectChange(filterName, selectedValues) {
            const urlQuery = this.$route.query
            this.$router.push({
                query: {
                    ...urlQuery,
                    [filterName]: selectedValues,
                },
            })
        },
        closeOpenedFilter() {
            this.openedFilter = null
        },
        toggleSelect(filterName) {
            this.openedFilter = filterName === this.openedFilter ? null : filterName
        },
        closeOpenedFilter() {
            this.openedFilter = null
        },
    },
    provide() {
        return {
            filterController: this,
        }
    },
}
</script>
