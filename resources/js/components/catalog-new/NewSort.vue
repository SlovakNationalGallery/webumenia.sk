<template>
    <div>
        <span class="tw-font-semibold"
            >Zobrazujem <span class="tw-font-bold">12000</span> diel, zoradené podľa
            <div class="tw-inline-block">
                <a
                    class="tw-font-bold tw-underline tw-decoration-2 tw-underline-offset-4"
                    @click="toggleIsOpen()"
                    >{{ selectedOptionValue }}<span class="caret"></span>
                </a>
                <div
                    v-if="isOpen"
                    class="tw-absolute tw--bottom-60 tw-p-4 tw-bg-white tw-z-10 tw-border-2 tw-border-gray-800 tw-w-80"
                >
                    <ul>
                        <li
                            class="tw-pl-2 hover:tw-bg-gray-200"
                            v-for="option in selectableOptions"
                        >
                            <a
                                class="tw-w-full tw-block"
                                @click="onSortChange(option.value)"
                                >{{ option.text }}</a
                            >
                        </li>
                    </ul>
                </div>
            </div>
            . Alebo skús aj
            <a class="tw-font-bold tw-underline tw-decoration-2 tw-underline-offset-4"
                >náhodný výber</a
            ></span
        >
    </div>
</template>

<script>
export default {
    props: {
        options: Array,
        sort: String,
        handleSortChange: Function,
    },
    data() {
        return {
            isOpen: false,
        }
    },
    computed: {
        selectedOptionValue() {
            const selectedOption = this.options.find((sortItem) => this.sort === sortItem.value)
            return selectedOption ? selectedOption.text : 'podľa poslednej zmeny'
        },
        selectableOptions() {
            return this.options.filter((sortItem) => this.sort !== sortItem.value)
        },
    },
    methods: {
        toggleIsOpen() {
            this.isOpen = !this.isOpen
        },
        onSortChange(sortValue) {
            this.toggleIsOpen()
            this.handleSortChange(sortValue)
        },
    },
}
</script>
