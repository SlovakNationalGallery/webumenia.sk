<template>
    <div class="tw-border-b tw-border-gray-300 hover:tw-border-gray-800">
        <button
            class="tw-flex tw-justify-between tw-items-center tw-w-full tw-bg-white tw-py-3.5 tw-px-4 tw-text-lg tw-font-medium hover:tw-border-gray-800"
            @click="toggleOpen"
        >
            <slot name="header"></slot>
            <svg
                v-if="isOpen"
                xmlns="http://www.w3.org/2000/svg"
                class="tw-h-4 tw-w-4 tw-fill-current"
                viewBox="0 0 256 256"
            >
                <path
                    d="M216.49,168.49a12,12,0,0,1-17,0L128,97,56.49,168.49a12,12,0,0,1-17-17l80-80a12,12,0,0,1,17,0l80,80A12,12,0,0,1,216.49,168.49Z"
                ></path>
            </svg>
            <svg
                v-else
                xmlns="http://www.w3.org/2000/svg"
                class="tw-h-4 tw-w-4 tw-fill-current"
                viewBox="0 0 256 256"
            >
                <path
                    d="M216.49,104.49l-80,80a12,12,0,0,1-17,0l-80-80a12,12,0,0,1,17-17L128,159l71.51-71.52a12,12,0,0,1,17,17Z"
                ></path>
            </svg>
        </button>
        <div v-if="isOpen" class="tw-px-4 tw-pb-6">
            <slot name="filter-body"></slot>
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            isOpen: false,
        }
    },
    methods: {
        toggleOpen() {
            this.isOpen = !this.isOpen
        },
    },
}
</script>
