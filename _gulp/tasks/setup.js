import { resolve } from 'path';
import { exec } from 'child_process';
import { getInfo } from '../helper';

const localPath = getInfo( 'path' );
const { theme } = require( '../../package' );

export function linkThemes( done ) {
	const themeFinalPath = resolve( `dist/done/${ theme.name }` );
	const themePath = resolve( 'src' );
	const targetThemePath = resolve( localPath, 'wp-content/themes' );

	const linkFinalTheme = `ln -s ${ themeFinalPath } ${ targetThemePath }`;
	const linkDevTheme = `ln -s ${ themePath } ${ targetThemePath }`;
	const renameDevTheme = `mv ${ targetThemePath }/src ${ targetThemePath }/${ theme.name }-dev`;
	const cmd = `${ linkFinalTheme } && ${ linkDevTheme } && ${ renameDevTheme }`;
	const { stdout, stderr } = exec( cmd );

	stdout.pipe( process.stdout );
	stderr.pipe( process.stderr );

	done();
}

export function linkDev( done ) {
	const devPath = resolve( 'dev' );
	const targetPluginPath = resolve( localPath, 'wp-content/plugins' );

	const cmd = `ln -s ${ devPath } ${ targetPluginPath }`;
	const { stdout, stderr } = exec( cmd );

	stdout.pipe( process.stdout );
	stderr.pipe( process.stderr );

	done();
}
