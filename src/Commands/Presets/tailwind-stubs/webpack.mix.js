const mix = require("laravel-mix");

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

mix.setPublicPath("public/themes/%theme%")
    .js(`${__dirname}/js/app.js`, "js")
    .postCss(`${__dirname}/css/app.css`, "css", [
        require("postcss-import"),
        require("tailwindcss"),
        require("autoprefixer"),
    ]);
