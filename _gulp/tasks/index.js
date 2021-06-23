import { task, parallel, series } from 'gulp';
import { linkDev, linkThemes } from './setup';
import { buildThemePotFile } from './language';
import { bsLocal } from './browserSync';
import {
	cleanDist,
	cleanDSStore,
	copyTheme,
	deleteEmptyDir,
	getThemeSize,
	replaceThemeTexts,
	zipTheme,
} from './release';

task( 'build:potFile', parallel( buildThemePotFile ) );
task( 'link:themes', linkThemes );
task( 'link:dev', linkDev );
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
task( 'default', parallel( bsLocal ) );
