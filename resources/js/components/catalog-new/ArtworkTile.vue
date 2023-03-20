<template>
    <div>
        <a :href="this.artworkUrl">
            <img
                :class="{ 'tw-hidden': !this.isLoaded }"
                @load="onImgLoad"
                :src="imageSrc"
                :srcSet="imageSrcSet"
            />
        </a>
        <div
            :class="[{ 'tw-hidden': this.isLoaded }, 'tw-w-full tw-saturate-50']"
            :style="{
                'aspect-ratio': aspectRatio || 1,
                'background-color': placeholderColorHsl
                    ? `hsl(${placeholderColorHsl.h}, ${placeholderColorHsl.s}%, ${placeholderColorHsl.l}%)`
                    : '#cdcdcd',
            }"
        ></div>
        <div class="tw-flex tw-mt-6">
            <div class="tw-grow">
                <a
                    :href="artworkUrl"
                    class="tw-block tw-leading-5 tw-pb-2 tw-italic tw-font-light tw-text-lg"
                    >{{ author }}</a
                >
                <a
                    :href="artworkUrl"
                    class="tw-block tw-leading-5 tw-pb-2 tw-font-medium tw-text-lg"
                    >{{ title }}</a
                >
                <a
                    :href="artworkUrl"
                    class="tw-block tw-leading-5 tw-pb-2 tw-font-normal tw-text-base"
                    >{{ dating }}</a
                >
            </div>
            <div class="tw-flex tw-gap-4 tw-items-start">
                <slot name="favourite-button"></slot>
                <a v-if="has_iip" :href="zoomUrl">
                    <svg
                        class="tw-w-5 tw-h-5 tw-fill-current"
                        xmlns="http://www.w3.org/2000/svg"
                        viewBox="0 0 256 256"
                    >
                        <path
                            d="M156,112a12,12,0,0,1-12,12H124v20a12,12,0,0,1-24,0V124H80a12,12,0,0,1,0-24h20V80a12,12,0,0,1,24,0v20h20A12,12,0,0,1,156,112Zm76.49,120.49a12,12,0,0,1-17,0L168,185a92.12,92.12,0,1,1,17-17l47.54,47.53A12,12,0,0,1,232.49,232.49ZM112,180a68,68,0,1,0-68-68A68.08,68.08,0,0,0,112,180Z"
                        ></path>
                    </svg>
                </a>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        artworkId: String,
        previewUrlTemplate: String,
        artworkUrlTemplate: String,
        previewSrcSetTemplate: String,
        zoomUrlTemplate: String,
        author: String,
        dating: String,
        title: String,
        has_iip: Boolean,
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
        zoomUrl() {
            return this.getUrlFromTemplate(this.zoomUrlTemplate)
        },
        artworkUrl() {
            return this.getUrlFromTemplate(this.artworkUrlTemplate)
        },
        imageSrcSet() {
            return this.getUrlFromTemplate(this.previewSrcSetTemplate)
        },
    },
    methods: {
        onImgLoad() {
            this.isLoaded = true
        },
        getUrlFromTemplate(template) {
            return template.replaceAll('__ARTWORK_ID__', this.artworkId)
        },
    },
}
</script>
