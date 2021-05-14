require('./bootstrap')
require( 'slick-carousel');
require('selectize')

const jQueryBridget = require('jquery-bridget')
const Isotope = require('isotope-layout')
const InfiniteScroll = require('infinite-scroll')
const Flickity = require('flickity');

Flickity.setJQuery( $ );
jQueryBridget('flickity', Flickity, $)

jQueryBridget('isotope', Isotope, $)

jQueryBridget('infiniteScroll', InfiniteScroll, $)
InfiniteScroll.imagesLoaded = require('imagesloaded')

require('lazysizes')
require('lazysizes/plugins/unveilhooks/ls.unveilhooks')
require('lazysizes/plugins/respimg/ls.respimg')
require('jquery.easing')

// Components
require('./components/searchbar')
require('./components/clipboard-button')

// Vue components
window.Vue = require('vue')

Vue.component('filter-sort-by', require('./components/filter/SortBy.vue').default);
Vue.component('filter-checkbox', require('./components/filter/Checkbox.vue').default);
Vue.component('filter-custom-select', require('./components/filter/CustomSelect.vue').default);
Vue.component('user-collections-nav-link', require('./components/user-collections/NavLink.vue').default);
Vue.component('user-collections-favourite-button', require('./components/user-collections/FavouriteButton.vue').default);
Vue.component('user-collections-clear-button', require('./components/user-collections/ClearButton.vue').default);
Vue.component('color-widget', require('./components/ColorWidget.vue').default);
Vue.component('year-slider', require('./components/YearSlider.vue').default);

Vue.component('slider', require('./components/vue/slider').default);
Vue.component('color-slider', require('./components/vue/color-slider').default);

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

 new Vue({ el: '#app' });
