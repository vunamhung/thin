const { resolve } = require( 'path' );

module.exports = {
	entry: {
		main: './src/assets/theme/index.js',
	},
	output: {
		path: resolve( __dirname, './src/js' ),
		filename: '[name].js',
	},
};
