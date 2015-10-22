var elixir = require('laravel-elixir');

/*
 |--------------------------------------------------------------------------
 | Elixir Asset Management
 |--------------------------------------------------------------------------
 |
 | Elixir provides a clean, fluent API for defining some basic Gulp tasks
 | for your Laravel application. By default, we are compiling the Less
 | file for our application, as well as publishing vendor resources.
 |
 */

elixir(function(mix) {
    //mix.sass('app.scss');
    //mix.styles([
    //    "bootstrap.min.css",
    //    "app.css",
    //],null,'public/css');
    mix.sass('manage.scss');
    mix.sass('promotion-form.scss');
    mix.sass('major-manage.scss');

    //mix.scripts([
    //   "jquery-2.1.4.min.js",
    //    "bootstrap.min.js",
    //]);
});
