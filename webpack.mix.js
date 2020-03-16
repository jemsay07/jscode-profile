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

mix.react("resources/js/app.js", "public/js")
    .sass("resources/sass/app.scss", "public/css")
    .sass("resources/sass/admin.scss", "public/css")
    .browserSync({
        proxy: "http://www.sandbox.site.com/",
        files: [
            "public/css/app.css",
            "public/css/admin.css",
            "public/js/app.js",
            "public/js/admin.js",
            "app/**/*",
            "routes/**/*",
            "resources/views/**/*",
            "resources/lang/**/*"
        ]
    })
    .copyDirectory('node_modules/tinymce/plugins', 'public/node_modules/tinymce/plugins')
    .copyDirectory('node_modules/tinymce/skins', 'public/node_modules/tinymce/skins')
    .copyDirectory('node_modules/tinymce/themes', 'public/node_modules/tinymce/themes')
    .copy('node_modules/tinymce/jquery.tinymce.js', 'public/node_modules/tinymce/jquery.tinymce.js')
    .copy('node_modules/tinymce/jquery.tinymce.min.js', 'public/node_modules/tinymce/jquery.tinymce.min.js')
    .copy('node_modules/tinymce/tinymce.js', 'public/node_modules/tinymce/tinymce.js')
    .copy('node_modules/tinymce/tinymce.min.js', 'public/node_modules/tinymce/tinymce.min.js');
