<template>
    <div>
        <div v-if="isFetchingArtworks">
            <slot name="loading-message"></slot>
        </div>
        <div v-show="!isFetchingArtworks" class="tw-flex tw-justify-center" ref="load-more-waypoint">
            <slot name="load-more-button"></slot>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        page: Number,
        isFetchingArtworks: Boolean,
    },
    mounted() {
        const options = {
            rootMargin: '0px',
            threshold: 1.0,
        }
        this.observer = new IntersectionObserver(this.handleObserver.bind(this), options)
        this.observer.observe(this.$refs['load-more-waypoint'])
    },
    methods: {
        handleObserver() {
            if (this.page && !this.isFetchingArtworks) {
                this.$emit('loadmore')
            }
        },
    },
}
</script>
