import sort from 'gulp-sort';
import wpPot from 'gulp-wp-pot';
import { src, dest, task, parallel } from 'gulp';

const { theme, languages } = require('../package.json');
const { team, bugReport, lastTranslator } = languages;

function buildThemePotFile() {
	return src('src/**/*.php')
		.pipe(sort())
		.pipe(wpPot({ bugReport, team, lastTranslator }))
		.pipe(dest(`src/languages/${theme.name}.pot`));
}

task('build:potFile', parallel(buildThemePotFile));
