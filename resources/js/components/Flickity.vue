<template>
    <div>
        <slot />
    </div>
</template>

<script>
import Flickity from "flickity"

export default {
    props: {
        options: Object,

        // Call resize() on Flickity instance when this prop changes to true for the first time.
        // Useful when Flickity is initialized hidden
        resizeOnce: Boolean,
    },
    data() {
        return {
            hasBeenResizedOnce: this.resizeOnce,
        }
    },
    mounted() {
        this.flickity = new Flickity(this.$el, this.options)
    },
    watch: {
        resizeOnce(shouldResize) {
            if (!shouldResize) return
            if (this.hasBeenResizedOnce) return

            this.flickity.resize()
        },
    },
}
</script>
