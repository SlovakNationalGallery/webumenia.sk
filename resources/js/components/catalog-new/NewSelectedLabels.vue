<template>
    <div class="tw-flex tw-space-x-3 tw-overflow-x-auto">
        <button
            class="tw-min-w-max tw-py-1 tw-px-1.5 tw-bg-gray-300 tw-flex tw-items-center"
            v-for="option in selectedOptionsAsLabels"
            @click="removeSelection(option)"
        >
            <span class="tw-text-xs tw-font-semibold tw-pr-3">{{ option.value }}</span>
            <i class="fa fa-close"></i>
        </button>
        <button
            v-if="isAnythingSelected"
            class="tw-min-w-max tw-bg-white tw-px-4 tw-font-normal tw-py-1.5 tw-text-sm tw-border tw-border-gray-300 hover:tw-border-gray-800"
            @click="clearAllSelections()"
        >
            <div class="tw-flex">
                <div class="tw-pr-2 tw-flex tw-items-center">
                    <i class="fa fa-rotate-left"></i>
                </div>
                <span>zrušiť výber</span>
            </div>
        </button>
    </div>
</template>

<script>
export default {
    props: {
        selectedOptionsAsLabels: Array,
        selectedValues: Object,
        clearAllSelections: Function,
        removeSelection: Function,
    },
    computed: {
        isAnythingSelected() {
            return Object.keys(this.selectedOptionsAsLabels).length
        },
    },
}
</script>
