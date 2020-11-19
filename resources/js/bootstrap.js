try {
    window.$ = window.jQuery = require('jquery');

    require('bootstrap/dist/js/bootstrap');
} catch (e) {}

// Custom global utilities
var utils = require('./webumenia')
window.spravGrid = utils.spravGrid
window.isIE = utils.isIE
window.isMobileSafari = utils.isMobileSafari
