<template>
    <div>
        <img :class="{ 'tw-hidden': !this.isLoaded }" @load="onImgLoad" :src="`${src}/600`" />
        <div
            :class="[{ 'tw-hidden': this.isLoaded }, 'tw-w-full tw-saturate-50']"
            :style="{
                'aspect-ratio': ratio || 1,
                'background-color': hsl.length
                    ? `hsl(${hsl[0].h}, ${hsl[0].s}%, ${hsl[0].l}%)`
                    : '#cdcdcd',
            }"
        ></div>
    </div>
</template>

<script>
export default {
    props: {
        src: String,
        ratio: Number,
        hsl: Array,
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
