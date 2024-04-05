import { createApp } from 'vue'
import VueDragscroll from 'vue-dragscroll'

import ZoomViewer from './components/ZoomViewer.vue'
import ZoomViewerThumbnail from './components/ZoomViewerThumbnail.vue'

createApp()
    .use(VueDragscroll)
    .component('zoom-viewer', ZoomViewer)
    .component('zoom-viewer.thumbnail', ZoomViewerThumbnail)
    .mount('#app')
