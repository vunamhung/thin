const { resolve } = require( 'path' );

module.exports = {
	entry: {
		main: './src/assets/theme/index.js',
	},
	output: {
		path: resolve( __dirname, './src/assets/js' ),
		filename: '[name].js',
	},
};
