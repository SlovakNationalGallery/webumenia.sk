<template>
    <div class="tw-relative">
        <div id="viewer" class="tw-absolute tw-inset-0"></div>
        <slot :thumbnailUrls="thumbnailUrls" :page="page" :methods="methods" />
    </div>
</template>

<script>
const OpenSeadragon = require('openseadragon')

export default {
    props: ['tileSources'],
    data() {
        return {
            page: 0,
        }
    },
    mounted() {
        this.viewer = OpenSeadragon({
            id: 'viewer',
            showNavigationControl: false,
            showNavigator: false,
            visibilityRatio: 1,
            minZoomLevel: 0,
            defaultZoomLevel: 0,
            autoResize: false,
            sequenceMode: this.tileSources.length > 1,
            tileSources: this.tileSources,
            showNavigationControl: false,
            showSequenceControl: false,
        })
    },
    watch: {
        page(page) {
            this.viewer.goToPage(page)
        },
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
                zoomIn: () => {
                    //TODO
                },
                zoomOut: () => {
                    //TODO
                },
            }
        },
    },
}
</script>
