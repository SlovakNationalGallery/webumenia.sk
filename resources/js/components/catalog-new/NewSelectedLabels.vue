<template>
    <div class="tw-flex tw-space-x-3 tw-overflow-x-auto">
        <template v-for="filter, filterName in controller.filters">
            <button
                class="tw-min-w-max tw-py-1 tw-px-1.5 tw-bg-gray-300 tw-flex tw-items-center"
                v-for="itemFilter in filter.list.filter((item) => item.checked)"
                @click="controller.handleChangeMultiSelect(filterName, itemFilter.name, false)"
            >
                <span class="tw-text-xs tw-font-semibold tw-pr-3">{{ itemFilter.name }}</span>
                <i class="fa fa-close" />
            </button>
        </template>
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
