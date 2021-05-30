module.exports = {
	extends: [ 'plugin:@woocommerce/eslint-plugin/recommended' ],
	globals: {
		jQuery: 'readonly',
	},
	settings: {},
	rules: {
		'woocommerce/feature-flag': 'off',
		'@woocommerce/dependency-group': 'off',
		'@wordpress/no-global-active-element': 'warn',
	},
	ignorePatterns: [ '**/*.min.js' ],
};
