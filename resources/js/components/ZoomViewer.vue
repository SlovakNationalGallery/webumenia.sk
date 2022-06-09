<template>
    <div class="tw-relative">
        <div id="viewer" class="tw-absolute tw-inset-0"></div>
        <slot :thumbnailUrls="thumbnailUrls" :page="page" :methods="methods" />
    </div>
</template>

<script>
export default {
    props: ['tileSources'],
    data() {
        return {
            page: 0,
        }
    },
    computed: {
        thumbnailUrls() {
            return this.tileSources.map((ts) => ts.replace(/\.dzi$/, '_files/0/0_0.jpg'))
        },
        methods() {
            return {
                setPage: (page) => (this.page = page),
                nextPage: () => this.page++,
                previousPage: () => this.page--,
                zoomIn: () => {},
                zoomOut: () => {},
            }
        },
    },
}
</script>
