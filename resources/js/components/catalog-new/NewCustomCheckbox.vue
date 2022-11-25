<template>
    <label class="tw-flex tw-items-center tw-font-normal" :for="title">
        <input
            class="focus:tw-ring-0 focus:tw-ring-offset-0 focus:tw-outline-none tw-form-checkbox tw-border-gray-200 tw-m-2 tw-p-1 tw-h-4 tw-w-4"
            type="checkbox"
            @change="
                controller.handleCheckboxChange(
                    name,
                    !controller.checkboxes[name].checked
                )
            "
            :checked="controller.checkboxes[name].checked"
            :key="title"
            :id="title"
        />
        <span
            :class="[
                'tw-text-base',
                {
                    'tw-font-semibold': controller.checkboxes[name].checked,
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
