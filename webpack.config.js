const { resolve } = require( 'path' );

module.exports = {
	mode: 'development',
	entry: {
		main: './src/assets/js/index.js',
		customizer: './src/assets/js/customizer.js',
	},
	output: {
		path: resolve( __dirname, './src/js' ),
		filename: '[name].js',
	},
};
