import { createApp } from 'vue'
import VueDragscroll from 'vue-dragscroll'

const app = createApp({})

app.use(VueDragscroll)

app.component('zoom-viewer', require('./components/ZoomViewer.vue').default)
app.component('zoom-viewer.thumbnail', require('./components/ZoomViewerThumbnail.vue').default)

app.mount('#app')
