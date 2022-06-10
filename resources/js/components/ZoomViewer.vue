<template>
    <div class="tw-relative" @mousemove="resetAutoHideTimer">
        <div id="viewer" class="tw-absolute tw-inset-0"></div>
        <slot
            :thumbnailUrls="thumbnailUrls"
            :page="page"
            :methods="methods"
            :showControls="showControls"
            :sequenceMode="sequenceMode"
        />
    </div>
</template>

<script>
import { debounce } from 'debounce'
const OpenSeadragon = require('openseadragon')

const ZoomPerClick = 2
const ControlsAutoHideAfterMs = 3000

export default {
    props: {
        tileSources: Array,
        initialIndex: Number,
    },
    data() {
        return {
            page: this.initialIndex || 0,
            showControls: true,
        }
    },
    created() {
        const hideControlsDebounced = debounce(
            () => (this.showControls = false),
            ControlsAutoHideAfterMs
        )

        this.resetAutoHideTimer = () => {
            this.showControls = true
            hideControlsDebounced()
        }
    },
    mounted() {
        this.viewer = OpenSeadragon({
            id: 'viewer',
            initialPage: this.page,
            showNavigationControl: false,
            showNavigator: false,
            showSequenceControl: false,
            visibilityRatio: 1,
            minZoomLevel: 0,
            sequenceMode: this.sequenceMode,
            tileSources: this.tileSources,
        })

        // Hide browser-imposed outline
        this.viewer.canvas.classList.add('tw-outline-none')

        this.viewer.addHandler('viewport-change', this.resetAutoHideTimer)

        this.resetAutoHideTimer()
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
        sequenceMode() {
            return this.tileSources.length > 1
        },
        methods() {
            return {
                setPage: (page) => (this.page = page),
                nextPage: () => this.page++,
                previousPage: () => this.page--,
                zoomIn: () => {
                    this.viewer.viewport.zoomBy(ZoomPerClick)
                    this.viewer.viewport.applyConstraints()
                },
                zoomOut: () => {
                    this.viewer.viewport.zoomBy(1 / ZoomPerClick)
                    this.viewer.viewport.applyConstraints()
                },
            }
        },
    },
}
</script>
