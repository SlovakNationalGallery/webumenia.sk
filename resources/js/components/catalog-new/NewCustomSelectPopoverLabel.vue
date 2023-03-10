<template>
    <span class="tw-font-semibold tw-font-sm md:tw-font-base">
        {{ name }}
        <span v-if="this.selectedCount" class="tw-font-bold">({{ this.selectedCount }})</span>
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
