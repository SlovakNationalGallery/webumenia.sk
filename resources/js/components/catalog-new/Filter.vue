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
        addParameterToUrlQuery(filterName, id, value) {
            const index = this.filters[filterName].list.findIndex((el) => el.id === id)
            this.filters[filterName].list[index].checked = value
            value
                ? this.urlQuery[filterName].push(id)
                : this.urlQuery[filterName].splice(this.urlQuery[filterName].indexOf(value), 1)
        },
        toggleSelect(filterName) {
            this.openedFilter = filterName === this.openedFilter ? null : filterName;
        }
    },
    provide() {
        return {
            filterController: this,
        }
    },
}
</script>
