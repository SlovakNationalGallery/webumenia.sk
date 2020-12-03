require('./bootstrap')

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

window.Vue = require('vue')

Vue.component('example-component', require('./components/ExampleComponent.vue').default);

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const app = new Vue({
    el: '#app',
});
