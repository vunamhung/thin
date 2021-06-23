import { task, parallel, series } from 'gulp';
import { linkThemes } from './setup';
import { bsLocal } from './browserSync';

task( 'link:themes', linkThemes );
task( 'default', parallel( bsLocal ) );
