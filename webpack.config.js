const { resolve } = require( 'path' );

module.exports = {
	entry: {
		main: './src/assets/theme/index.js',
		customizer: './src/assets/theme/customizer.js',
	},
	output: {
		path: resolve( __dirname, './src/js' ),
		filename: '[name].js',
	},
};
