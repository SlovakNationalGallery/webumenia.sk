<template>
    {{ value.replace(/^([^,]*),\s*(.*)$/, '$2 $1') }}
</template>

<script>
export default {
    props: {
        value: String,
    },
}
</script>
