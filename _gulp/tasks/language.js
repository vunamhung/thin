import { src, dest } from 'gulp';
import sort from 'gulp-sort';
import wpPot from 'gulp-wp-pot';

const { theme } = require( '../../package' );
const {
	team,
	bugReport,
	lastTranslator,
} = require( '../../package' ).languages;

export function buildThemePotFile() {
	return src( 'src/**/*.php' )
		.pipe( sort() )
		.pipe( wpPot( { bugReport, team, lastTranslator } ) )
		.pipe( dest( `src/languages/${ theme.name }.pot` ) );
}
