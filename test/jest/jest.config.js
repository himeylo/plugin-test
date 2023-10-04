/**
 * External dependencies
 */
export const config = {
	rootDir: '../../',
	preset: '@wordpress/jest-preset-default',
	testEnvironmentOptions: {
		url: 'http://localhost/',
	},
	testPathIgnorePatterns: [ '/.git/', '/node_modules/', '/vendor/' ],
};

export default config;
