window.debounce = require('debounce');
window.Vue = require('vue');

Vue.component('linked-combos', require('./components/vue/linked-combos').default);
Vue.component('admin-links-input', require('./components/admin/LinksInput.vue').default);

new Vue({ el: '#wrapper' })
