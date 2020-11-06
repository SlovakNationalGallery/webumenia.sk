require('./bootstrap')

const jQueryBridget = require('jquery-bridget')
const Isotope = require('isotope-layout')
const InfiniteScroll = require('infinite-scroll');

jQueryBridget('isotope', Isotope, $)
jQueryBridget('infiniteScroll', InfiniteScroll, $);

require('./components/searchbar')
