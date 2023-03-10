<template>
    <div>
        <div v-masonry="containerId" transition-duration="0.3s" item-selector=".item">
            <div v-masonry-tile class="item tw-w-1/3" v-for="artwork in artworks">
                <img :src="`${url}${artwork.id}/600`" />
                <span>{{ artwork.content.title }}</span>
            </div>
        </div>
        <div>
            <button>show more</button>
        </div>
    </div>
</template>

<script>
import { VueMasonryPlugin } from 'vue-masonry'
Vue.use(VueMasonryPlugin)

export default {
    props: {
        artworks: Array,
        url: String,
    },
    methods: {},
}
</script>
