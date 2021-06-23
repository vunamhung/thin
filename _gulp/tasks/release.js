import { exec } from 'child_process';
import gulp from 'gulp';
import zip from 'gulp-zip';
import del from 'del';
import deleteEmpty from 'delete-empty';
import replace from 'gulp-replace-task';
import size from 'gulp-size';
import notify from 'gulp-notify';

import paths from '../paths';

const { theme } = require( '../../package' );

export function zipTheme() {
	return gulp
		.src( `./dist/done/${ theme.name }/**/*` )
		.pipe( zip( `${ theme.name }.zip` ) )
		.pipe( gulp.dest( './dist' ) );
}

export function deleteEmptyDir() {
	return deleteEmpty( './dist/build/' );
}

export function cleanDSStore( done ) {
	const cmd = "find ./src -type f -name '*.DS_Store' -ls -delete",
		run = exec( cmd );

	run.stdout.pipe( process.stdout );
	run.stderr.pipe( process.stderr );

	done();
}

export function cleanDist() {
	return del( './dist/**' );
}

export function copyTheme() {
	return gulp
		.src( paths.theme.build )
		.pipe( gulp.dest( `./dist/build/${ theme.name }` ) );
}

export function replaceThemeTexts() {
	return gulp
		.src( `./dist/build/${ theme.name }/**/*` )
		.pipe(
			replace( {
				patterns: [
					{
						json: {
							prefix: theme.prefix,
							title: theme.title,
							short_title: theme.short_title,
							tags: theme.tags,
							name: theme.name,
							cookie: theme.name,
							version: theme.version,
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
						},
					},
				],
				prefix: 'vnh_',
			} )
		)
		.pipe( gulp.dest( `./dist/done/${ theme.name }` ) );
}

export function getSize() {
	return gulp.src( './dist/*.zip' ).pipe(
		size( {
			pretty: true,
			showFiles: true,
		} )
	);
}

export function getThemeSize() {
	const s = size( {
		pretty: true,
		showFiles: true,
	} );

	return gulp
		.src( './dist/*.zip' )
		.pipe( s )
		.pipe(
			notify( {
				title: theme.name,
				onLast: true,
				message: () => `This theme's size is ${ s.prettySize }`,
			} )
		);
}
