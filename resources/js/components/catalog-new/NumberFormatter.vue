<template>
    {{
        value
            .toLocaleString('en-US', {
                useGrouping: true,
            })
            .replace(/,/g, ' ')
    }}
</template>
<script>
export default {
    props: {
        value: String,
    },
}
</script>
