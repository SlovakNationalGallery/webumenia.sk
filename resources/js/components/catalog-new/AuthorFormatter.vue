<template>
    {{ formatAuthorName }}
</template>

<script>
import { formatAuthorName } from './formatters'
export default {
    props: {
        value: String,
    },
    computed: {
        formatAuthorName: function () {
            return formatAuthorName(this.value)
        },
    },
}
</script>
