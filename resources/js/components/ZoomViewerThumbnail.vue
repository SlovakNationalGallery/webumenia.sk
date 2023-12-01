<template>
    <img ref="img" @load="onLoad" @click="$emit('click')" />
</template>

<script>
export default {
    props: {
        scrollIntoView: Boolean,
    },
    methods: {
        onLoad() {
            if (!this.scrollIntoView) return
            this.$refs.img.scrollIntoView()
        },
    },
}
</script>
