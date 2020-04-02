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

mix.less('resources/less/style.less', 'public/css').options({
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
