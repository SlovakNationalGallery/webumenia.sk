require('./bootstrap')
require( 'slick-carousel');

const jQueryBridget = require('jquery-bridget')
const Isotope = require('isotope-layout')
const InfiniteScroll = require('infinite-scroll')

jQueryBridget('isotope', Isotope, $)

jQueryBridget('infiniteScroll', InfiniteScroll, $)
InfiniteScroll.imagesLoaded = require('imagesloaded')

require('lazysizes')
require('lazysizes/plugins/unveilhooks/ls.unveilhooks')
require('lazysizes/plugins/respimg/ls.respimg')
require('jquery.easing')

// Components
require('./components/searchbar')
