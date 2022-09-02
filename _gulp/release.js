import del from 'del';
import zip from 'gulp-zip';
import size from 'gulp-size';
import deleteEmpty from 'delete-empty';
import replace from 'gulp-replace-task';
import gulp, { task, series } from 'gulp';
import { exec } from 'child_process';
import notify from 'gulp-notify';

const { theme } = require('../package.json');
const themeFiles = [
	'src/**',
	'!**/.*',
	'!**/docs/**',
	'!**/tests/**',
	'!**/composer.*',
	'!**/license.txt',
	'!**/LICENSE.txt',
	'!**/*.yml',
	'!**/*.md',
	'!**/doc/**',
	'!**/Dockerfile',
	'!**/make.sh',
	'!**/lang/**',
	'!**/Gruntfile.js',
	'!**/gulpfile.js',
	'!**/Gulpfile.js',
	'!**/package*.json',
	'!**/phpunit*',
	'!**/README.md',
	'!**/CHANGELOG.md',
	'!**/CONTRIBUTING.md',
	'!**/LICENSE.md',
	'!**/LICENSE',
	'!**/phpcs*',
	'!src/assets/**',
	'!src/vendor/**/readme.txt',
	'!src/vendor/**/changelog.txt',
	'!src/vendor/tgmpa/tgm-plugin-activation/languages/**',
	'!src/vendor/tgmpa/tgm-plugin-activation/plugins/**',
	'!src/vendor/tgmpa/tgm-plugin-activation/example.php',
];
const json = {
	prefix: theme.prefix,
	title: theme.title,
	short_title: theme.short_title,
	tags: theme.tags,
	name: theme.name,
	cookie: theme.name,
	uri: theme.uri,
	author: theme.author,
	author_uri: theme.author_uri,
	document_uri: theme.document_uri,
	license: theme.license,
	license_uri: theme.license_uri,
	copyright: theme.copyright,
	textdomain: theme.name,
	description: theme.description,
	wp_requires: theme.wp_requires,
	php_requires: theme.php_requires,
	tested_up_to: theme.tested_up_to,
	dev_mode: theme.dev_mode,
};

export function zipTheme() {
	return gulp
		.src(`./dist/${theme.name}/**/*`)
		.pipe(zip(`${theme.name}.zip`))
		.pipe(gulp.dest('./dist'));
}
export function deleteEmptyDir() {
	return deleteEmpty(`./dist/${theme.name}`);
}
export function cleanDSStore(done) {
	const cmd = "find ./src -type f -name '*.DS_Store' -ls -delete",
		run = exec(cmd);
	run.stdout.pipe(process.stdout);

	run.stderr.pipe(process.stderr);
	done();
}
export function cleanDist() {
	return del('./dist/**');
}
export function copyTheme() {
	return gulp.src(themeFiles).pipe(gulp.dest(`./dist/${theme.name}`));
}
export function replaceThemeTexts() {
	return gulp
		.src(`./dist/${theme.name}/**/*.{php,css,txt}`)
		.pipe(
			replace({
				patterns: [{ json }],
				prefix: 'vnh_',
			})
		)
		.pipe(gulp.dest(`./dist/${theme.name}`));
}

export function getSize() {
	return gulp.src('./dist/*.zip').pipe(
		size({
			pretty: true,
			showFiles: true,
		})
	);
}

export function getThemeSize() {
	const s = size({
		pretty: true,
		showFiles: true,
	});

	return gulp
		.src('./dist/*.zip')
		.pipe(s)
		.pipe(
			notify({
				title: theme.name,
				onLast: true,
				message: () => `This theme's size is ${s.prettySize}`,
			})
		);
}

task(
	'release',
	series(
		cleanDist,
		cleanDSStore,
		copyTheme,
		deleteEmptyDir,
		replaceThemeTexts,
		zipTheme,
		getThemeSize
	)
);
