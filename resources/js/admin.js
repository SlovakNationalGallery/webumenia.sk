import Vue from 'vue'
import 'livewire-vue'
import { Lang } from 'laravel-vue-lang'

Vue.use(Lang, { fallback: 'sk' })

window.Vue = Vue
window.debounce = require('debounce')

Vue.component('admin-links-input', require('./components/admin/LinksInput.vue').default)
Vue.component('autocomplete', require('./components/Autocomplete.vue').default)
Vue.component('linked-combos', require('./components/vue/linked-combos').default)
Vue.component('query-string', require('./components/QueryString.vue').default)
Vue.component('croppr', require('./components/Croppr.vue').default)
Vue.component('tabs-controller', require('./components/TabsController.vue').default)
Vue.component('tab', require('./components/Tab.vue').default)
Vue.component('tab-panel', require('./components/TabPanel.vue').default)
Vue.component(
    'admin.shuffle-item-filters',
    require('./components/admin/ShuffleItemFilters.vue').default
)

new Vue({ el: '#wrapper' })
