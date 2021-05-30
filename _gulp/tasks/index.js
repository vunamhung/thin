import { task, parallel, series } from 'gulp';
import { linkThemes } from './setup';

task( 'link:themes', linkThemes );
