<template>
    <label class="tw-flex tw-items-center tw-font-normal" :for="title">
        <input
            class="focus:tw-ring-0 focus:tw-ring-offset-0 focus:tw-outline-none tw-form-checkbox tw-border-gray-200 tw-m-2 tw-p-1 tw-h-4 tw-w-4"
            type="checkbox"
            @change="(e) => controller.handleCheckboxChange(name, e.target.checked)"
            :checked="controller.data.selectedValues[name]"
            :key="title"
            :id="title"
        />
        <span
            :class="[
                {
                    'tw-font-semibold': controller.data.selectedValues[name],
                },
            ]"
            >{{ title }}</span
        >
    </label>
</template>

<script>
export default {
    props: {
        title: String,
        name: String,
    },
    inject: {
        controller: {
            from: 'filterController',
        },
    },
}
</script>
