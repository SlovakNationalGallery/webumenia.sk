<template>
    <label class="tw-flex tw-items-center tw-font-medium" :for="id">
        <input
            class="focus:tw-ring-0 focus:tw-ring-offset-0 focus:tw-outline-none tw-form-checkbox tw-border-gray-300 tw-m-2 tw-ml-4 md:tw-ml-0 tw-p-1 tw-h-6 tw-w-6"
            type="checkbox"
            :name="name"
            @change="$emit('change', $event)"
            :checked="checked"
            :id="id"
        />
        <span
            :class="[
                {
                    'tw-font-semibold': checked,
                },
            ]"
            >{{ title }}</span
        >
    </label>
</template>

<script>
export default {
    emits: ['change'],
    props: {
        title: String,
        name: String,
        id: String,
        checked: Boolean,
    },
}
</script>
