/**
 * Command line application to initialize this template repository.
 * @author Texas A&M Transportation Institute, Communications Division <webmaster@tti.tamu.edu>
 */

import fs from 'fs';
import path from 'path';
import readline from 'readline';

const HELP = `A command line application for updating this template repository's files with unique details.
Usage: node .bin/init.js [options]
Options:
	--verbose   Log changes as they happen.
    --help, -h  Display this help message and exit.
`;

if ( process.argv.includes( '--help' ) || process.argv.includes( '-h' ) ) {
	console.log( HELP );
	process.exit( 0 );
}

const ARGS = {
	VERBOSE: process.argv.includes( '--verbose' ),
	CURRENT_PLUGIN_NAME: new PluginName(),
	CURRENT_PLUGIN_DESCRIPTION: 'A template WordPress plugin.',
	CURRENT_PLUGIN_REPOSITORY:
		'https://github.com/ttitamu/com-wp-plugin-template',
	NEW_PLUGIN_NAME: null,
	NEW_PLUGIN_DESCRIPTION: null,
	NEW_PLUGIN_REPOSITORY: null,
	FILES: [
		'.config/.phpcs.xml.dist',
		'.wp-env/database.php',
		'.wp-env/database.sql',
		'common/',
		'src/',
		'test/',
		'.wp-env.json',
		'composer.json',
		'composer.lock',
		'package.json',
		'package-lock.json',
	],
	DIRNAME: path.dirname( import.meta.url.replace( 'file://', '' ) ),
	ROOT: path.resolve(
		path.dirname( import.meta.url.replace( 'file://', '' ) ),
		'..'
	),
};

const rl = readline.createInterface( {
	input: process.stdin,
	output: process.stdout,
} );

rl.question( "What is your plugin's name? ", ( answer ) => {
	ARGS.NEW_PLUGIN_NAME = new PluginName( answer );
	rl.question( "What is your plugin's description? ", ( answer ) => {
		ARGS.NEW_PLUGIN_DESCRIPTION = answer;
		rl.question(
			"What is your plugin's repository URL? https://github.com/ttitamu/",
			( answer ) => {
				ARGS.NEW_PLUGIN_REPOSITORY =
					'https://github.com/ttitamu/' + answer;
				const filtered = searchFilesForChanges( ARGS );
				rl.write( 'The following changes will be made:\n' );
				rl.write( getChangeExplanation( filtered ) );
				rl.question( 'Proceed? [Y/n] ', ( answer ) => {
					if ( answer.toLowerCase() !== 'y' ) {
						rl.close();
						process.exit( 0 );
						return;
					}
					handle( filtered );
					rl.close();
				} );
			}
		);
	} );
} );

/**
 * Filter the arguments by searching through the file list and returning only the arguments that were found.
 *
 * @param {ARGS} args - Arguments provided by the user and request context.
 * @return {object} The filtered arguments.
 */
function searchFilesForChanges( args ) {
	const filteredArgs = {};
	for ( const key in args ) {
		if ( 0 > key.indexOf( 'CURRENT' ) ) {
			filteredArgs[ key ] = args[ key ];
			continue;
		}
		const value = args[ key ];
		if (
			typeof value === 'string' &&
			searchFilesForValue( value, args.FILES )
		) {
			filteredArgs[ key ] = value;
			continue;
		}
		if ( typeof value === 'object' ) {
			filteredArgs[ key ] = {};
			for ( const subkey in value ) {
				if ( typeof filteredArgs[ key ][ subkey ] !== 'undefined' ) {
					// Only search for each value once.
					continue;
				}
				const subvalue = value[ subkey ];
				if (
					typeof subvalue === 'string' &&
					searchFilesForValue( subvalue, args.FILES )
				) {
					filteredArgs[ key ][ subkey ] = subvalue;
				}
			}
		}
	}
	return filteredArgs;
}

/**
 * Search through the given files for the given value and return true if the value is found in any file.
 *
 * @param {any} value
 * @param {string[]} files
 * @return {boolean} True if the value is found in any file, false otherwise.
 */
function searchFilesForValue( value, files ) {
	for ( let i = 0; i < files.length; i++ ) {
		const file = files[ i ];
		if ( fs.lstatSync( file ).isDirectory() ) {
			const found = searchFilesForValue(
				value,
				fs.readdirSync( file ).map( ( f ) => path.join( file, f ) )
			);
			if ( found ) {
				return true;
			}
		} else {
			const content = fs.readFileSync( file, 'utf8' );
			if ( 0 <= content.indexOf( value ) ) {
				return true;
			}
		}
	}
	return false;
}

/**
 * Get a message explaining the changes they are about to make to the template repository.
 * @param {ARGS} args
 * @returns {string} A message explaining the changes they are about to make to the template repository.
 */
function getChangeExplanation( args ) {
	let message = '';
	for ( const key in args.CURRENT_PLUGIN_NAME ) {
		const oldValue = args.CURRENT_PLUGIN_NAME[ key ];
		const newValue = args.NEW_PLUGIN_NAME[ key ];
		if ( oldValue === newValue ) {
			continue;
		}
		message += `  ${ oldValue } => ${ newValue }\n`;
	}
	if ( args.CURRENT_PLUGIN_DESCRIPTION !== args.NEW_PLUGIN_DESCRIPTION ) {
		message += `  ${ args.CURRENT_PLUGIN_DESCRIPTION } => ${ args.NEW_PLUGIN_DESCRIPTION }\n`;
	}
	if ( args.CURRENT_PLUGIN_REPOSITORY !== args.NEW_PLUGIN_REPOSITORY ) {
		message += `  ${ args.CURRENT_PLUGIN_REPOSITORY } => ${ args.NEW_PLUGIN_REPOSITORY }\n`;
	}
	return message;
}

/**
 * Replace instances of template text with the provided arguments.
 * @param {ARGS} args - Arguments provided by the user and request context.
 * @returns {void}
 */
function handle( args ) {
	const currentValues = args.CURRENT_PLUGIN_NAME;
	const newValues = {};
	for ( const key in currentValues ) {
		newValues[ key ] = args.NEW_PLUGIN_NAME[ key ];
	}
	// Replace text within files.
	for ( let i = 0; i < args.FILES.length; i++ ) {
		replace(
			path.join( args.ROOT, args.FILES[ i ] ),
			currentValues,
			newValues,
			args.VERBOSE
		);
	}
}

/**
 * Replace instances of text values within the given file.
 * @param {string} filename - Relative path to a file.
 * @param {object} oldValues - Object with keys and old values.
 * @param {object} newValues - Object with keys and new values.
 * @param {boolean} verbose - Whether to log changes as they happen.
 * @returns {void}
 */
function replaceInFile( filename, oldValues, newValues, verbose ) {
	if ( ! fs.existsSync( filename ) ) {
		return;
	}

	let content = fs.readFileSync( filename, 'utf8' );
	let contentChanged = false;
	let loggedFile = false;
	for ( const key in oldValues ) {
		const oldValue = oldValues[ key ];
		const newValue = newValues[ key ];
		if ( 0 > content.indexOf( oldValue ) ) {
			continue;
		}
		if ( verbose ) {
			if ( ! loggedFile ) {
				console.log( filename );
				loggedFile = true;
			}
			console.log( `  ${ oldValue } => ${ newValue }` );
		}
		content = content.replaceAll( oldValue, newValue );
		contentChanged = true;
	}
	if ( contentChanged ) {
		fs.writeFileSync( filename, content, 'utf8' );
	}
}

/**
 * Replace instances of a plugin's various namespaces within the given file or directory recursively.
 * @param {string} fileOrDirectory - Absolute path to a file or directory.
 * @param {object} oldValues - The current text types and values.
 * @param {object} newValues - The new text types and values.
 * @param {boolean} verbose - Whether to log changes as they happen.
 * @returns {void}
 */
function replace( fileOrDirectory, oldValues, newValues, verbose ) {
	if ( ! fs.existsSync( fileOrDirectory ) ) {
		return;
	}

	if ( fs.lstatSync( fileOrDirectory ).isDirectory() ) {
		const files = fs.readdirSync( fileOrDirectory );
		for ( let i = 0; i < files.length; i++ ) {
			const file = files[ i ];
			replace(
				path.join( fileOrDirectory, file ),
				oldValues,
				newValues,
				verbose
			);
		}
		return;
	}

	replaceInFile( fileOrDirectory, oldValues, newValues, verbose );
}

/**
 * Declare the naming conventions for a WordPress plugin author.
 * @class
 * @param {string} [name="Texas A&M Transportation Institute, Communications Division"] - Author's name.
 * @param {string} [email="webmaster@tti.tamu.edu"] - Author's email address.
 * @property {string} name - Author's name.
 * @property {string} email - Author's email address.
 */
function PluginAuthor( name, email, url ) {
	this.name =
		name || 'Texas A&M Transportation Institute, Communications Division';
	this.email = email || 'webmaster@tti.tamu.edu';
}

/**
 * Declare the naming conventions for a WordPress plugin.
 * @class
 * @param {string} [name="WordPress Plugin Name"] - Plugin name shown to users in the dashboard.
 * @property {string} name - Plugin name shown to users in the dashboard.
 * @property {string} slug - Slug name of the plugin. Used in the plugin's directory name, textdomain, repository name, and more. Must be lowercase with hyphens instead of spaces and all other special characters and numbers removed.
 * @property {string} prefix - Database and global function name prefix used in all PHP files declared by the plugin. Must be lowercase with underscores instead of spaces and hyphens. Here we assume that the plugin's prefix will be the same as its lowercase name with underscores instead of spaces and hyphens and no other special characters or numbers.
 * @property {string} namespace - PHP class namespace used in all PHP classes declared by the plugin. Replace spaces with underscores and remove all other special characters and numbers.
 * @property {string} constant - Constant name prefix used in all global PHP constants declared by the plugin. Must be uppercase with underscores instead of spaces. Here we assume that the plugin's constant prefix will be the same as its prefix in uppercase.
 * @property {string} textdomain - Name of the plugin's textdomain. Used to provide text translations in the dashboard and website. Must be lowercase with hyphens instead of spaces, and here we assume that the plugin's textdomain will be the same as its slug plus "-textdomain".
 */
function PluginName( name ) {
	name = name?.trim() || 'WordPress Plugin Name';
	this.name = name;
	this.slug = name
		.toLowerCase()
		.replace( /[^a-z\s]/g, '' )
		.replace( /\s+/g, '-' );
	this.prefix = this.slug.replace( /-/g, '_' );
	this.namespace = name
		.replace( /\s+/g, '_' )
		.replace( /-/g, '_' )
		.replace( /[^a-z_]/gi, '' );
	this.constant = this.prefix.toUpperCase();
	this.textdomain = this.slug + '-textdomain';
}
