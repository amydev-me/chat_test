const mix = require('laravel-mix');

mix.js('resources/js/main.js', 'public/js/admin')
    .extract(['vue', 'vue-router','axios'])
    .mix.autoload({ jQuery: 'jquery', $: 'jquery', jquery: 'jquery' })
    .webpackConfig({
        output: {
            chunkFilename: `js/admin/chunks/[name].chunk.js`,
            publicPath: '/',
        },
    });
