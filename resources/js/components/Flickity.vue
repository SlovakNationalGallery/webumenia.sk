<template>
    <div>
        <slot />
    </div>
</template>

<script>
import Flickity from "flickity"

export default {
    props: {
        options: Object
    },
    mounted() {
        new Flickity(this.$el, this.options)
    },
}
</script>
