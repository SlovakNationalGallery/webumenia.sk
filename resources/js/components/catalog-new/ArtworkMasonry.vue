<template>
    <div class="grid" data-masonry='{ "itemSelector": ".grid-item", "columnWidth": 160 }'>
        <div class="grid-item"></div>
        <div class="grid-item grid-item--width2 grid-item--height2"></div>
        <div class="grid-item grid-item--height3"></div>
        <div class="grid-item grid-item--height2"></div>
        <div class="grid-item grid-item--width3"></div>
        <div class="grid-item"></div>
        <div class="grid-item"></div>
        <div class="grid-item grid-item--height2"></div>
        <div class="grid-item grid-item--width2 grid-item--height3"></div>
        <div class="grid-item"></div>
        <div class="grid-item grid-item--height2"></div>
        <div class="grid-item"></div>
        <div class="grid-item grid-item--width2 grid-item--height2"></div>
        <div class="grid-item grid-item--width2"></div>
        <div class="grid-item"></div>
        <div class="grid-item grid-item--height2"></div>
        <div class="grid-item"></div>
        <div class="grid-item"></div>
        <div class="grid-item grid-item--height3"></div>
        <div class="grid-item grid-item--height2"></div>
        <div class="grid-item"></div>
        <div class="grid-item"></div>
        <div class="grid-item grid-item--height2"></div>
    </div>
</template>

<script>
import Masonry from 'masonry-layout'

export default {
    props: {
        artworks: Array,
    },
    methods: {},
}
</script>
