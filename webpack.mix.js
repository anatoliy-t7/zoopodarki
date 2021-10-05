const mix = require('laravel-mix');

const tailwindcss = require('tailwindcss');

mix.sourceMaps(true, 'source-map');

mix
	.js('resources/js/app.js', 'public/js')
	.sass('resources/scss/app.scss', 'public/css')
	.options({
		processCssUrls: false,
		postCss: [tailwindcss('./tailwind.config.js')],
	})
	.version();

mix
	.js('resources/js/dashboard.js', 'public/js')
	.sass('resources/scss/dashboard.scss', 'public/css')
	.options({
		processCssUrls: false,
		postCss: [tailwindcss('./tailwind.config.js')],
	})
	.version();

mix.copy('resources/json/address.json', 'public/json/address.json');

// for dashboard in edit product page
mix.copy('node_modules/@yaireo/tagify/dist/tagify.min.js', 'public/js');
mix.copy('node_modules/@yaireo/tagify/dist/tagify.css', 'public/css');

// Slider
mix.copy('node_modules/@splidejs/splide/dist/js/splide.min.js', 'public/js');

mix.options({
	autoprefixer: false,
});

mix.browserSync({
	proxy: 'zoopodarki.test',
	host: 'zoopodarki.test',
	port: '8080',
	open: 'external',
	files: [
		'resources/views/**',
		'resources/js/*.js',
		'resources/scss/*.scss',
		'resources/views/*.blade.php',
		'resources/views/*/*.blade.php',
		'resources/views/*/*/*.blade.php',
		'app/**',
		'app/*/*/*',
		'packages/*/*/*',
	],
	notify: false,
	cors: true,
});

mix.disableSuccessNotifications();

if (mix.inProduction()) {
	mix.version();
}

// config eslint
mix.webpackConfig({
	module: {
		rules: [
			{
				enforce: 'pre',
				exclude: ['/node_modules/', '/vendor/'],
				loader: 'eslint-loader',
				test: /\.(js|blade.php)?$/,
			},
		],
	},
});
