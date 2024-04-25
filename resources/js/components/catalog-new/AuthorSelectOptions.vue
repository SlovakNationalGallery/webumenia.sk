<script setup>
import SelectOptions from './SelectOptions.vue'
import { formatAuthorName } from './formatters.js'
</script>

<template>
    <SelectOptions :formatLabel="formatAuthorName" />
</template>
