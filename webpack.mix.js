const mix = require('laravel-mix');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
 |
 */

mix
    // JavaScript
    .js('resources/js/app.js', 'public/js')
    .js('resources/js/admin.js', 'public/js')
    .vue()
    .extract([
        'bootstrap/dist/js/bootstrap',
        'flickity',
        'clipboard',
        'imagesloaded',
        'infinite-scroll',
        'isotope-layout',
        'jquery',
        'jquery-bridget',
        'jquery.easing',
        'lazysizes',
        'lazysizes/plugins/unveilhooks/ls.unveilhooks',
        'lazysizes/plugins/respimg/ls.respimg',
        'selectize',
        'typeahead.js/dist/typeahead.bundle',
        'vue-select',
        'vue',
    ])
    
    // CSS
    .less('resources/less/admin.less', 'public/css')
    .less('resources/less/style.less', 'public/css')
    .options({ 
        processCssUrls: !process.env.MIX_SKIP_CSS_URL_PROCESSING
    });

if (mix.inProduction()) {
    mix.version();
}
