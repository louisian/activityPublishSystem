let mix = require('laravel-mix');
let webpack=require('webpack');
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

/*
 * 前台
 */
mix.sass('resources/assets/sass/frontend/app.scss', 'public/css/frontend/app.css');
mix.js('resources/assets/js/frontend/app.js', 'public/js/frontend')

/*
 * 后台
 */

mix.sass('resources/assets/sass/backend/app.scss', 'public/css/backend/app.css');
mix.js('resources/assets/js/backend/app.js', 'public/js/backend')
mix.webpackConfig({
    plugins: [
        new webpack.optimize.CommonsChunkPlugin({
            name: "commons",
            filename: "js/commons.js",
            minChunks: 2
        })
    ]
});