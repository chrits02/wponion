let $files = {};

// wponion-base.scss
$files[ 'src/scss/wponion-base.scss' ]    = {
	dist: 'assets/css/',
	combine_files: true,
	scss: true,
	autoprefixer: true,
	minify: true,
	rename: 'wponion-base.css',
	watch: [ 'src/scss/wponion-base.scss', 'src/scss/includes/*', 'src/scss/includes/fields/*', 'src/scss/includes/fields/*/*', 'src/scss/includes/modules/*', 'src/scss/includes/modules/*/*' ],
};
$files[ 'src/scss/wponion-plugins.scss' ] = {
	dist: 'assets/css/',
	combine_files: true,
	scss: true,
	minify: true,
	watch: [ 'src/scss/wponion-plugins.scss', 'src/vendors/json-view/json-view.scss' ]
};

// Javascripts
$files[ 'src/js/wponion-plugins.js' ]     = {
	dist: 'assets/js',
	watch: [ './src/vendors/*/*.js', 'src/js/wponion-plugins.js' ],
	combine_files: true,
	uglify: true,
};
$files[ 'src/js/wponion-core.js' ]        = {
	dist: 'assets/js',
	watch: [
		'src/js/wponion-core.js',
		'src/js/core/*',
		'src/js/fields/*',
		'src/js/helpers/*',
		'src/js/modules/*',
		'src/js/modules/*/*',
		'src/vendors/backbone-modal.js' ],
	webpack: 'webpack_dev',
	sourcemaps: true,
	rename: 'wponion-core.js',
};
$files[ 'src/js/wponion-customizer.js' ]  = {
	dist: 'assets/js',
	watch: true,
	webpack: true,
	rename: 'wponion-customizer.js',
};
$files[ 'src/js/themes/wp-theme.js' ]     = {
	dist: 'templates/wp/assets/',
	webpack: 'webpack_dev',
	uglify: true,
	watch: true,
	rename: 'wponion-wp-theme.js',
};
$files[ 'src/js/themes/modern-theme.js' ] = {
	dist: 'templates/modern/assets/',
	webpack: 'webpack_dev',
	uglify: true,
	watch: true,
	rename: 'wponion-modern-theme.js'
};
$files[ 'src/js/themes/fresh-theme.js' ]  = {
	dist: 'templates/fresh/assets/',
	webpack: 'webpack_dev',
	uglify: true,
	watch: true,
	rename: 'wponion-fresh-theme.js'
};
$files[ 'src/js/wponion-cloner.js' ]      = {
	dist: 'assets/js',
	babel: true,
	uglify: true,
	watch: true,
	rename: 'wponion-cloner.js',
};

// Plugins.
$files[ 'src/vendors/flatpickr/style.scss' ]           = {
	dist: 'assets/plugins/flatpickr/',
	combine_files: true,
	scss: true,
	autoprefixer: true,
	minify: true,
	rename: 'style.css',
};
$files[ 'src/vendors/colorpicker/cs-colorpicker.css' ] = {
	dist: 'assets/plugins/colorpicker/',
	watch: [ 'src/js/wponion-plugins.js' ],
	autoprefixer: true,
	minify: true,
	rename: 'cs-colorpicker.css',
};

// Themes

// WP Modern
$files[ 'src/js/themes/wp-modern.js' ]           = {
	watch: true,
	babel: true,
	uglify: true,
	rename: 'script.js',
	dist: 'templates/wp-modern/assets/',
};
$files[ 'src/scss/themes/wp-modern/style.scss' ] = {
	watch: true,
	scss: true,
	autoprefixer: true,
	minify: true,
	rename: 'style.css',
	dist: 'templates/wp-modern/assets/',
};

// wp-lite
$files[ 'src/js/themes/wp-lite.js' ]           = {
	watch: true,
	babel: true,
	uglify: true,
	rename: 'script.js',
	dist: 'templates/wp-lite/assets/',
};
$files[ 'src/scss/themes/wp-lite/style.scss' ] = {
	watch: true,
	scss: true,
	autoprefixer: true,
	minify: true,
	rename: 'style.css',
	dist: 'templates/wp-lite/assets/',
};
// wp-lite
$files[ 'src/js/themes/wp.js' ]                = {
	watch: true,
	babel: true,
	uglify: true,
	rename: 'script.js',
	dist: 'templates/wp/assets/',
};
$files[ 'src/scss/themes/wp/style.scss' ]      = {
	watch: true,
	scss: true,
	autoprefixer: true,
	minify: true,
	rename: 'style.css',
	dist: 'templates/wp/assets/',
};

module.exports = {
	files: $files,
	config: {
		combine_files: {
			append: 'wponion-append',
			prepend: 'wponion-prepend',
			inline: 'wponion-inline',
		},
	}
};
