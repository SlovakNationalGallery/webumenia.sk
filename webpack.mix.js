const mix = require('laravel-mix')
const path = require('path')

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
    .js('resources/js/zoom.js', 'public/js')
    .vue({
        version: 3,
        options: {
            compilerOptions: {
                compatConfig: {
                    MODE: 2,
                },
            },
        },
    })
    .extract([
        'bootstrap/dist/js/bootstrap',
        'flickity',
        'flickity-imagesloaded',
        'clipboard',
        'date-fns',
        'imagesloaded',
        'infinite-scroll',
        'isotope-layout',
        'jquery',
        'jquery-bridget',
        'jquery.easing',
        'laravel-vue-lang',
        'lazysizes',
        'lazysizes/plugins/unveilhooks/ls.unveilhooks',
        'lazysizes/plugins/respimg/ls.respimg',
        'selectize',
        'typeahead.js/dist/typeahead.bundle',
        'vue-select',
        'vue',
    ])
    // For ziggy
    .alias({ ziggy: path.resolve('vendor/tightenco/ziggy/dist/vue') })
    // For laravel-vue-lang
    .webpackConfig({
        resolve: {
            alias: {
                '@lang': path.resolve('./lang'),
                vue: '@vue/compat',
                ziggy: path.resolve('vendor/tightenco/ziggy/dist/vue'),
            },
        },
        module: {
            rules: [
                {
                    test: /lang.+\.(php)$/,
                    loader: 'php-array-loader',
                },
            ],
        },
    })

    // CSS
    .less('resources/less/admin.less', 'public/css')
    .less('resources/less/style.less', 'public/css')
    .postCss('resources/css/tailwind.css', 'public/css', [require('tailwindcss')])

    .disableSuccessNotifications()
    .options({
        processCssUrls: !process.env.MIX_SKIP_CSS_URL_PROCESSING,
    })

if (mix.inProduction()) {
    mix.version()
}
