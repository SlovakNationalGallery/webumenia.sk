import Vue from 'vue'
import VueDragscroll from 'vue-dragscroll'

Vue.use(VueDragscroll)

window.$ = window.jQuery = require('jquery')
window.OpenSeadragon = require('openseadragon')

Vue.component('zoom-viewer', require('./components/ZoomViewer.vue').default)

new Vue({ el: '#app' })
