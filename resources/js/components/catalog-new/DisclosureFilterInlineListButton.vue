<template>
    <div class="tw-border-b tw-border-gray-300 hover:tw-border-gray-800">
        <button
            class="tw-flex tw-justify-between tw-items-center tw-w-full tw-bg-white tw-py-3.5 tw-px-4 tw-text-lg tw-font-medium hover:tw-border-gray-800"
            @click="toggleOpen"
        >
            <slot name="header"></slot>
            <slot v-if="isOpen" name="opened-icon"></slot>
            <slot v-else name="closed-icon"></slot>
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
