require('./bootstrap')

const jQueryBridget = require('jquery-bridget')
const isotope = require('isotope-layout')

jQueryBridget('isotope', isotope, $)
