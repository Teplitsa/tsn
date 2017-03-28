const elixir = require('laravel-elixir');
require('laravel-elixir-webpack-official-fixed');

require('laravel-elixir-vue');

/*
 |--------------------------------------------------------------------------
 | Elixir Asset Management
 |--------------------------------------------------------------------------
 |
 | Elixir provides a clean, fluent API for defining some basic Gulp tasks
 | for your Laravel application. By default, we are compiling the Sass
 | file for our application, as well as publishing vendor resources.
 |
 */

elixir(mix => {
    mix.sass('style.scss')
        .webpack('app.js')
        .version([
            'public/css/style.css',
            'public/js/app.js',
        ])
        .copy('node_modules/eonasdan-bootstrap-datetimepicker/build/css/bootstrap-datetimepicker.min.css', 'public/css/bootstrap-datepicker.min.css')
        .copy('node_modules/eonasdan-bootstrap-datetimepicker/build/js/bootstrap-datetimepicker.min.js', 'public/js/bootstrap-datepicker.js');
})