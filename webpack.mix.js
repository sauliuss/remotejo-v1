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

mix.disableSuccessNotifications()
	.js('resources/js/app.js', 'public/js').vue()
	.js('resources/js/bootstrap.js', 'public/js').vue()
	.sass('resources/sass/style.scss', 'public/css').options({
    	processCssUrls: false
	})
	.browserSync({
		notify: false,
		files: ['public/css/**/*.css', 'public/js/*.js', 'resources/js/**/*.vue'],
		host: 'localhost',
        port: 3000,
		proxy: 'remotejoapp.test',
		injectChanges: true,
		reload: false
	});

