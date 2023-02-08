<template>
    <label class="tw-flex tw-items-center tw-font-normal" :for="id">
        <input
            class="focus:tw-ring-0 focus:tw-ring-offset-0 focus:tw-outline-none tw-form-checkbox tw-border-gray-200 tw-m-2 tw-p-1 tw-h-4 tw-w-4"
            type="checkbox"
            :name="name"
            @change="$emit('change', $event)"
            :checked="checked"
            :key="id"
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
    }
}
</script>
