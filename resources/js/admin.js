import Vue from 'vue'
import 'livewire-vue'

window.Vue = Vue
window.debounce = require('debounce');

Vue.component('admin-links-input', require('./components/admin/LinksInput.vue').default);
Vue.component('autocomplete', require('./components/Autocomplete.vue').default);
Vue.component('linked-combos', require('./components/vue/linked-combos').default);
Vue.component('livewire-vue-adaptor', require('./components/LivewireVueAdaptor.vue').default);

new Vue({ el: '#wrapper' })
