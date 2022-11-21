<template>
    <div class="tw-flex tw-space-x-3 tw-overflow-x-auto">
        <template v-for="(filter, filterName) in controller.filters">
            <button
                class="tw-min-w-max tw-py-1 tw-px-1.5 tw-bg-gray-300 tw-flex tw-items-center"
                v-for="itemFilter in filter.list.filter((item) => item.checked)"
                @click="
                    controller.handleMultiSelectChange(
                        filterName,
                        controller.filters[filterName].list
                            .map((item) => {
                                return item.id === itemFilter.id
                                    ? { ...item, checked: !item.checked }
                                    : item
                            })
                            .filter((option) => option.checked)
                            .map((option) => option.name)
                    )
                "
            >
                <span class="tw-text-xs tw-font-semibold tw-pr-3">{{ itemFilter.name }}</span>
                <i class="fa fa-close" />
            </button>
        </template>
        <button
                v-if="controller.lengthOfLabelItems"
                class="tw-bg-white tw-px-4 tw-font-normal tw-py-1.5 tw-text-sm tw-border tw-border-gray-300 hover:tw-border-gray-800"
                @click="controller.clearAllSelection()"
            >
                <div class="tw-flex">
                    <div class="tw-pr-2 tw-flex tw-items-center">
                        <i class="fa fa-rotate-left" />
                    </div>
                    <span>zrušiť výber</span>
                </div>
            </button>
    </div>
</template>

<script>
export default {
    inject: {
        controller: {
            from: 'filterController',
        },
    },
}
</script>
