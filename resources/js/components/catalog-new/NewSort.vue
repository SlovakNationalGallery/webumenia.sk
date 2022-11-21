<template>
    <div>
        <span class="tw-font-semibold"
            >Zobrazujem <span class="tw-font-bold">12000</span> diel, zoradené podľa
            <div class="tw-inline-block">
                <a
                    class="tw-font-bold tw-underline tw-decoration-2 tw-underline-offset-4"
                    @click="toggleIsOpen()"
                    >{{ selectedOption.text }}<span class="caret"></span>
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
                            <a class="tw-w-full tw-block" @click="handleSort(option.value)">{{
                                option.text
                            }}</a>
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
    data() {
        return {
            isOpen: false,
        }
    },
    computed: {
        options() {
            return [
                {
                    value: 'updated_at',
                    text: 'podľa poslednej zmeny',
                },
                {
                    value: 'created_at',
                    text: 'dátumu pridania',
                },
                {
                    value: 'title',
                    text: 'názvu',
                },
                {
                    value: 'author',
                    text: 'autora',
                },
                {
                    value: 'newest',
                    text: 'dotovania - od najnovšieho',
                },
                {
                    value: 'oldest',
                    text: 'dotovania - od najstaršieho',
                },
                {
                    value: 'view_count',
                    text: 'počtu videní',
                },
                {
                    value: 'random',
                    text: 'náhodného poradia',
                },
            ]
        },
        selectedOption() {
            return this.options.find((sortItem) => this.controller.sort === sortItem.value)
        },
        selectableOptions() {
            return this.options.filter((sortItem) => this.controller.sort !== sortItem.value)
        },
    },
    methods: {
        toggleIsOpen() {
            this.isOpen = !this.isOpen
        },
        handleSort(sortValue) {
            this.toggleIsOpen()
            this.controller.handleSort(sortValue)
        },
    },
    inject: {
        controller: {
            from: 'filterController',
        },
    },
}
</script>
