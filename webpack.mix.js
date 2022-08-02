const mix = require('laravel-mix');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel applications. By default, we are compiling the CSS
 | file for the application as well as bundling up all the JS files.
 |
 */

const glob = require('glob');
const path = require('path');

mix.setPublicPath(path.normalize('./public'));
if(mix.inProduction())
    mix.version();

mix.extract(['@vue'], 'js/vue.min');
mix.copy([
    'node_modules/bootstrap/dist/js/bootstrap.bundle.min.js',
    'node_modules/jquery/dist/jquery.min.js'
], './public/js/vendors.js');
glob.sync('resources/scss/*.scss').forEach(function(file){
    file = file.replace(/[\\\/]+/g, '/');
    let dest = file.replace('resources/scss', 'public/css').replace(/\.(scss|sass)$/, '.css');
    mix.sass(file, dest);
});
glob.sync('resources/js/*.js').forEach(function(file){
    file = file.replace(/[\\\/]+/g, '/');
    let dest = file.replace('resources', 'public');
    mix.js(file, dest).vue();
});
