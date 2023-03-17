<template>
    <div>
        <img :class="{ 'tw-hidden': !this.isLoaded }" @load="onImgLoad" :src="`${src}/600`" />
        <div
            :class="[{ 'tw-hidden': this.isLoaded }, 'tw-w-full tw-saturate-50']"
            :style="{
                'aspect-ratio': aspectRatio || 1,
                'background-color': placeholderColorHsl
                    ? `hsl(${placeholderColorHsl.h}, ${placeholderColorHsl.s}%, ${placeholderColorHsl.l}%)`
                    : '#cdcdcd',
            }"
        ></div>
    </div>
</template>

<script>
export default {
    props: {
        src: String,
        aspectRatio: Number,
        placeholderColorHsl: Object,
    },
    data() {
        return {
            isLoaded: false,
        }
    },
    methods: {
        onImgLoad() {
            this.isLoaded = true
        },
    },
}
</script>
