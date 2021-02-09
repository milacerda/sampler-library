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

mix.js("resources/js/app.js","public/js")
    .sass("resources/sass/app.scss", "public/css")
    .sourceMaps();

mix.scripts([
    'node_modules/angular/angular.js',
    'node_modules/angular-route/angular-route.js',
    'node_modules/moment/moment.js',
    'node_modules/angular-moment/angular-moment.js',
    'node_modules/angular-jwt/dist/angular-jwt.js',
    'resources/app/routes.js',
    'resources/app/main.js',
], 'public/js/all.js')