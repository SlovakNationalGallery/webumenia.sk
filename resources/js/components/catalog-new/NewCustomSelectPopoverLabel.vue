<template>
    <span class="tw-font-semibold tw-font-sm md:tw-font-base tw-min-w-max">
        {{ name }}
        <span class="tw-inline-block tw-w-4">
            <span v-if="this.selectedCount" class="tw-font-bold">({{ this.selectedCount }})</span>
        </span>
    </span>
</template>

<script>
export default {
    props: {
        selectedValues: Array,
        name: String,
    },
    computed: {
        selectedCount() {
            return this.selectedValues ? this.selectedValues.length : 0
        },
    },
}
</script>
