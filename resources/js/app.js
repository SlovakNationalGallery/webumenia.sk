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

const Vue = window.Vue = require('vue');


// color input for catalog-color filter
Vue.component('slider', require('./components/vue/slider').default);
Vue.component('color-slider', require('./components/vue/color-slider').default);

