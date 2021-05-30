import { resolve } from 'path';
import { exec } from 'child_process';
import { getInfo } from '../helper';

const localPath = getInfo( 'path' );
const { theme } = require( '../../package' );

export function linkThemes( done ) {
	const themeFinalPath = resolve( `dist/done/${ theme.name }` ),
		themePath = resolve( 'src' ),
		targetThemePath = resolve( localPath, 'wp-content/themes' );

	const linkFinalTheme = `ln -s ${ themeFinalPath } ${ targetThemePath }`,
		linkDevTheme = `ln -s ${ themePath } ${ targetThemePath }`,
		renameDevTheme = `mv ${ targetThemePath }/src ${ targetThemePath }/${ theme.name }-dev`,
		cmd = `${ linkFinalTheme } && ${ linkDevTheme } && ${ renameDevTheme }`,
		run = exec( cmd );

	run.stdout.pipe( process.stdout );
	run.stderr.pipe( process.stderr );

	done();
}
