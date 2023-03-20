<template>
    <div>
        <img :class="{ 'tw-hidden': !this.isLoaded }" @load="onImgLoad" :src="imageSrc" />
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
        artworkId: String,
        previewUrlTemplate: String,
        aspectRatio: Number,
        placeholderColorHsl: Object,
    },
    data() {
        return {
            isLoaded: false,
        }
    },
    computed: {
        imageSrc() {
            return this.getUrlFromTemplate(this.previewUrlTemplate)
        },
    },
    methods: {
        onImgLoad() {
            this.isLoaded = true
        },
        getUrlFromTemplate(template) {
            return template.replace('__ARTWORK_ID__', this.artworkId)
        },
    },
}
</script>
