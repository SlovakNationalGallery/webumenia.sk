<template>
    <div>
        <div class="tw-flex tw-justify-center" v-if="isLoading">
            <slot name="loading-message"></slot>
        </div>
        <div v-show="!isLoading" class="tw-flex tw-justify-center" ref="load-more-waypoint">
            <slot name="load-more-button"></slot>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        page: Number,
        isLoading: Boolean,
    },
    mounted() {
        this.observer = new IntersectionObserver(this.handleObserver.bind(this), {
            rootMargin: '0px',
            threshold: 1.0,
        })
        this.observer.observe(this.$refs['load-more-waypoint'])
    },
    methods: {
        handleObserver() {
            if (this.page > 1 && !this.isLoading) {
                this.$emit('loadmore')
            }
        },
    },
}
</script>
