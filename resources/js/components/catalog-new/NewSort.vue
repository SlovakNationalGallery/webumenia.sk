<template>
    <div>
        <span class="tw-font-semibold">
            <slot name="artwork-counter"></slot>
            <div class="tw-inline-block tw-z-10">
                <button
                    ref="button"
                    class="tw-font-bold tw-underline tw-decoration-2 tw-underline-offset-4"
                    @click="toggleIsOpen()"
                >
                    {{ selectedOptionValue
                    }}<svg
                        class="tw-inline tw-w-4 tw-h-4 tw-fill-current"
                        xmlns="http://www.w3.org/2000/svg"
                        viewBox="0 0 256 256"
                    >
                        <path
                            d="M216.49,104.49l-80,80a12,12,0,0,1-17,0l-80-80a12,12,0,0,1,17-17L128,159l71.51-71.52a12,12,0,0,1,17,17Z"
                        ></path>
                    </svg>
                </button>
                <div ref="body" class="tw-z-10">
                    <div
                        v-if="isOpen"
                        v-on-clickaway="toggleIsOpen"
                        class="tw-mt-2 tw-p-4 tw-bg-white tw-border-2 tw-border-gray-800 tw-w-80"
                    >
                        <ul>
                            <li
                                class="tw-pl-2 hover:tw-bg-gray-200"
                                v-for="option in selectableOptions"
                            >
                                <a class="tw-w-full tw-block" @click="onSortChange(option.value)">{{
                                    option.text
                                }}</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <slot name="random-select"></slot>
        </span>
    </div>
</template>

<script>
import { directive as onClickaway } from 'vue-clickaway'
import { createPopper } from '@popperjs/core'

export default {
    directives: {
        onClickaway: onClickaway,
    },
    props: {
        options: Array,
        sort: String,
        handleSortChange: Function,
    },
    data() {
        return {
            isOpen: false,
            popper: null,
        }
    },
    mounted() {
        const button = this.$refs.button
        const body = this.$refs.body
        this.popper = createPopper(button, body, {
            placement: 'bottom-start',
        })
    },
    computed: {
        selectedOptionValue() {
            const selectedOption = this.options.find((sortItem) => this.sort === sortItem.value)
            return selectedOption ? selectedOption.text : 'poslednej zmeny'
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
