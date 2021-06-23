import { init } from 'browser-sync';

const { theme } = require( '../paths' ).default;

export function bsLocal() {
	init( {
		ui: false,
		files: theme.files,
		notify: {
			styles: {
				backgroundColor: '#222',
				fontSize: '1.2em',
				top: '50%',
				borderBottomLeftRadius: 0,
				fontFamily: 'inherit',
			},
		},
	} );
}
