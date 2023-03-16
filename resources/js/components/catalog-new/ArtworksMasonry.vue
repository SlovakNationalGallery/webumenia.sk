<template>
    <div>
        <div v-masonry transition-duration="0" item-selector=".item">
            <div
                v-masonry-tile
                class="item tw-w-1/3 tw-p-2"
                v-for="artwork in artworks"
                :key="artwork.id"
            >
                <ArtworkImage
                    :src="`${url}${artwork.id}`"
                    :ratio="artwork.content.image_ratio"
                    :hsl="artwork.content.hsl"
                ></ArtworkImage>
                <span>{{ artwork.content.title }}</span>
            </div>
        </div>
        <div>
            <button :class="{ 'tw-invisible': this.page }" ref="more" @click="$emit('loadmore')">
                show more
            </button>
        </div>
    </div>
</template>

<script>
import { VueMasonryPlugin } from 'vue-masonry'
import ArtworkImage from './ArtworkImage.vue'

Vue.use(VueMasonryPlugin)

export default {
    props: {
        artworks: Array,
        url: String,
        page: Number,
    },
    mounted() {
        const options = {
            rootMargin: '0px',
            threshold: 1.0,
        }
        this.observer = new IntersectionObserver(this.handleObserver.bind(this), options)
        this.observer.observe(this.$refs.more)
    },
    methods: {
        handleObserver() {
            if (this.page) {
                this.$emit('loadmore')
            }
        },
    },
    components: { ArtworkImage },
}
</script>
