<template>
    <label class="tw-flex tw-items-center" :for="controller.checkboxes[checkboxName].title">
        <input
            class="focus:tw-ring-0 focus:tw-ring-offset-0 focus:tw-outline-none tw-form-checkbox tw-border-gray-200 tw-m-2 tw-p-1 tw-h-4 tw-w-4"
            type="checkbox"
            @change="
                controller.handleCheckboxChange(
                    checkboxName,
                    !controller.checkboxes[checkboxName].checked
                )
            "
            :checked="controller.checkboxes[checkboxName].checked"
            :key="controller.checkboxes[checkboxName].title"
            :id="controller.checkboxes[checkboxName].title"
        />
        <span class="tw-font-normal tw-text-base">{{
            controller.checkboxes[checkboxName].title
        }}</span>
    </label>
</template>

<script>
export default {
    props: {
        checkboxName: String,
    },
    inject: {
        controller: {
            from: 'filterController',
        },
    },
}
</script>
