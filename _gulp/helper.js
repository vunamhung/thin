import { readFileSync } from 'fs';

export function getInfo( keyword ) {
	let info;
	const fileContent = readFileSync( './wp-cli.local.yml', 'utf-8' );
	const lines = fileContent.split( /\r\n|\n|\n\t/ );

	for ( const line of lines ) {
		if ( line.includes( keyword ) ) {
			const arrayOfLine = line.split( ' ' );

			if ( arrayOfLine[ 1 ] ) {
				info = arrayOfLine[ 1 ];
			}

			break;
		}
	}

	return info;
}
