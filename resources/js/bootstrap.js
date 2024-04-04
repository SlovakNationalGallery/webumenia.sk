import jQuery from 'jquery'
import 'bootstrap/dist/js/bootstrap'

window.$ = window.jQuery = jQuery

// Custom global utilities
import * as utils from './webumenia'
window.spravGrid = utils.spravGrid
window.isIE = utils.isIE
window.isMobileSafari = utils.isMobileSafari
