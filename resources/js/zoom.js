import Vue from 'vue'
import VueDragscroll from 'vue-dragscroll'

Vue.use(VueDragscroll)

Vue.component('zoom-viewer', require('./components/ZoomViewer.vue').default)

new Vue({ el: '#app' })
