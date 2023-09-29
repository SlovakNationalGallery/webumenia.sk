<template>
    {{
        value
            .toLocaleString('sk-SK', {
                useGrouping: true,
            })
    }}
</template>
<script>
export default {
    props: {
        value: String,
    },
}
</script>
