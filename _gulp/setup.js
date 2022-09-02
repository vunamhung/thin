import { resolve } from 'path';
import { readFileSync } from 'fs';
import { exec } from 'child_process';
import { task } from 'gulp';

const { theme } = require('../package.json');
const localPath = getInfo('path');
const themesDirPath = resolve(localPath, 'wp-content/themes');
const pluginsDirPath = resolve(localPath, 'wp-content/plugins');
const finalThemePath = resolve(`dist/${theme.name}`);
const devThemePath = resolve('src');
const devPluginPath = resolve('dev');

export function getInfo(keyword) {
	let info;
	const fileContent = readFileSync('./wp-cli.local.yml', 'utf-8');
	const lines = fileContent.split(/\r\n|\n|\n\t/);

	for (const line of lines) {
		if (line.includes(keyword)) {
			const arrayOfLine = line.split(' ');

			if (arrayOfLine[1]) {
				info = arrayOfLine[1];
			}

			break;
		}
	}

	return info;
}

function linkThemes(done) {
	const linkFinalTheme = `ln -s ${finalThemePath} ${themesDirPath}`;
	const linkDevTheme = `ln -s ${devThemePath} ${themesDirPath}`;
	const renameDevTheme = `mv ${themesDirPath}/src ${themesDirPath}/${theme.name}-dev`;

	const { stdout, stderr } = exec(`${linkFinalTheme} && ${linkDevTheme} && ${renameDevTheme}`);

	stdout.pipe(process.stdout);
	stderr.pipe(process.stderr);

	done();
}

function linkDev(done) {
	const { stdout, stderr } = exec(`ln -s ${devPluginPath} ${pluginsDirPath}`);

	stdout.pipe(process.stdout);
	stderr.pipe(process.stderr);

	done();
}

task('link:themes', linkThemes);
task('link:dev', linkDev);
