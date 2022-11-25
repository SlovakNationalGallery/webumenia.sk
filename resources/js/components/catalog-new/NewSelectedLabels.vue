<template>
    <div class="tw-flex tw-space-x-3 tw-overflow-x-auto">
        <template v-for="(filter, filterName) in selectedOptions">
            <button
                class="tw-min-w-max tw-py-1 tw-px-1.5 tw-bg-gray-300 tw-flex tw-items-center"
                v-for="option in filter"
                @click="
                    controller.handleMultiSelectChange(
                        filterName,
                        controller.selectedValues[filterName].filter(
                            (selectedOption) => selectedOption !== option
                        )
                    )
                "
            >
                <span class="tw-text-xs tw-font-semibold tw-pr-3">{{ option }}</span>
                <i class="fa fa-close"></i>
            </button>
        </template>
        <button
            v-if="isAnythingSelected"
            class="tw-min-w-max tw-bg-white tw-px-4 tw-font-normal tw-py-1.5 tw-text-sm tw-border tw-border-gray-300 hover:tw-border-gray-800"
            @click="controller.clearAllSelections()"
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
    computed: {
        selectedOptions() {
            return Object.fromEntries(
                Object.entries(this.controller.selectedValues).filter(([filterName, _]) =>
                    Object.keys(this.controller.filters).includes(filterName)
                )
            )
        },
        isAnythingSelected() {
            return Object.keys(this.controller.selectedOptionsAsLabels).length
        },
    },
    inject: {
        controller: {
            from: 'filterController',
        },
    },
}
</script>
