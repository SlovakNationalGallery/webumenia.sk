<template>
    <div>
        <img ref="img" :src="src" />
        <slot :value="value" />
    </div>
</template>

<script>
import Croppr from 'croppr'
import 'croppr/dist/croppr.min.css'

export default {
    props: {
        src: String,
        defaultValue: Object,

        // Aspect ratio, but inverted so that it can be defined as e.g. "16/9"
        aspectRatio: Number,
    },
    data() {
        return { value: null }
    },
    mounted() {
        this.croppr = new Croppr(this.$refs.img, {
            aspectRatio: this.aspectRatio ? 1 / this.aspectRatio : undefined,
            returnMode: 'ratio',
            onInitialize: (instance) => {
                if (!this.defaultValue) return

                // Set to an initial value if provided
                // NOTE: Right now only works with 'ratio' values
                instance.resizeTo(
                    instance.imageEl.clientWidth * this.defaultValue.width,
                    instance.imageEl.clientHeight * this.defaultValue.height
                )

                instance.moveTo(
                    instance.imageEl.clientWidth * this.defaultValue.x,
                    instance.imageEl.clientHeight * this.defaultValue.y
                )
            },
            onCropEnd: (value) => {
                this.value = value
            },
        })
    },
}
</script>
