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
            <button @click="$emit('click')">show more</button>
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
    components: { ArtworkImage },
}
</script>
