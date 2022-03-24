<template>
    <div>
        <div ref="carousel">
            <slot></slot>
        </div>
        <slot
            name="custom-ui"
            :next="next"
            :previous="previous"
            :selectedIndex="selectedIndex"
            :slides="slides"
        ></slot>
    </div>
</template>

<script>
import Flickity from 'flickity-imagesloaded'

export default {
    props: {
        options: Object,
        viewportClass: String,

        // Call resize() on Flickity instance when this prop changes to true for the first time.
        // Useful when Flickity is initialized hidden
        resizeOnce: Boolean,
    },
    data() {
        return {
            hasBeenResizedOnce: this.resizeOnce,
            slides: [],
            selectedIndex: null,
        }
    },
    mounted() {
        const vm = this
        this.flickity = new Flickity(this.$refs.carousel, {
            on: {
                ready() {
                    vm.slides = this.slides
                    vm.selectedIndex = this.selectedIndex

                    if (vm.viewportClass) {
                        this.viewport.classList.add(...vm.viewportClass.split(' '))
                    }
                },
                change(index) {
                    vm.selectedIndex = index
                },
            },
            ...this.options,
        })
    },
    methods: {
        next() {
            this.flickity.next()
        },
        previous() {
            this.flickity.previous()
        },
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
