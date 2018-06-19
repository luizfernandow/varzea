let mix = require('laravel-mix');

const {InjectManifest} = require('workbox-webpack-plugin');

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

mix.webpackConfig(webpack => {
    return {
        plugins: [
            new InjectManifest({
                swSrc: './sw.js',
                swDest: path.join(`${__dirname}/public`, 'sw.js')
            })
        ],
        output: {
            publicPath: ''
        }
    };
}).js('resources/assets/js/app.js', 'public/js')
  .sass('resources/assets/sass/app.scss', 'public/css')
  .styles([
            'public/css/app.css',
            'node_modules/dialog-polyfill/dialog-polyfill.css',
    ], 'public/css/app.css')
  .copyDirectory('resources/assets/images', 'public/images');

if (mix.inProduction()) {
    mix.version();
    mix.disableNotifications();
}