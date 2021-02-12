window.debounce = require('debounce');
window.Vue = require('vue');

const vSelect = require("vue-select").default;
Vue.component("v-select", vSelect);

const linkedCombos = require('./components/vue/linked-combos').default;
Vue.component('linked-combos', linkedCombos);
