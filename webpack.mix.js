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

if (process.env.NODE_ENV == 'testing') {
    mix.options({ processCssUrls: false });
}

mix
    // JavaScript
    .js('resources/js/app.js', 'public/js')
    .extract([
        'jquery',
        'bootstrap/dist/js/bootstrap',
        'infinite-scroll',
        'isotope-layout',
        'typeahead.js/dist/typeahead.bundle',
    ])

    // CSS
    .less('resources/less/style.less', 'public/css').options({
        autoprefixer: {
            options: {
                browsers: [
                    'last 6 versions',
                ]
            }
        }
    });

if (mix.inProduction()) {
    mix.version();
}
