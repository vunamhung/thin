const { resolve } = require('path');
const webpack = require('webpack');
const DuplicatePackageChecker = require('duplicate-package-checker-webpack-plugin');
const MiniCssExtract = require('mini-css-extract-plugin');
const BrowserSync = require('browser-sync-webpack-plugin');

module.exports = {
	mode: 'development',
	entry: {
		main: './src/assets/js/index.js',
	},
	output: {
		path: resolve(__dirname, './src/js'),
		filename: '[name].js',
	},
	externals: {
		jquery: 'jQuery',
	},
	plugins: [
		new webpack.ProvidePlugin({
			$: 'jquery',
			jQuery: 'jquery',
		}),
		new DuplicatePackageChecker(),
		new MiniCssExtract({
			filename: '../assets/css/components/[name]-js.css',
		}),
		new BrowserSync(
			{
				ui: false,
				files: ['src/*.css', 'src/js/*.js', 'src/**/*.php'],
				notify: {
					styles: {
						backgroundColor: '#222',
						fontSize: '1.2em',
						top: '50%',
						borderBottomLeftRadius: 0,
						fontFamily: 'inherit',
					},
				},
			},
			{
				injectCss: true,
				reload: false,
			}
		),
	],
	module: {
		rules: [
			{
				test: /\.js$/,
				use: ['babel-loader'],
				exclude: /node_modules/,
			},
			{
				test: /\.css$/,
				use: [MiniCssExtract.loader, 'css-loader'],
			},
			{
				test: /\.(png|gif|jpg|jpeg|woff|woff2|eot|ttf|svg)$/,
				use: ['url-loader'],
			},
		],
	},
};
