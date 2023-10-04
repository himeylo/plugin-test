<?php
/**
 * PHPUnit bootstrap file.
 *
 * @package WordPress Theme
 */

// Require composer dependencies.
require_once dirname( __DIR__, 2 ) . '/vendor/autoload.php';

// Give access to tests_add_filter() function.
require_once getenv( 'WP_PHPUNIT__DIR' ) . '/includes/functions.php';

// Activate or deactivate WordPress SQLite Database Integration plugin.
if ( 'enabled' === getenv( 'WP_SQLITE_MODE' ) ) {

	if ( ! is_dir( dirname( __DIR__, 2 ) . '/wordpress/wp-content/' ) ) {
		mkdir( dirname( __DIR__, 2 ) . '/wordpress/wp-content/' );
	}
	file_put_contents(
		dirname( __DIR__, 2 ) . '/wordpress/wp-content/db.php',
		str_replace(
			array(
				'{SQLITE_IMPLEMENTATION_FOLDER_PATH}',
				'{SQLITE_PLUGIN}',
			),
			array(
				dirname( __DIR__, 2 ) . '/vendor/wordpress/sqlite-database-integration',
				'sqlite-database-integration/load.php',
			),
			file_get_contents( dirname( __DIR__, 2 ) . '/vendor/wordpress/sqlite-database-integration/db.copy' )
		)
	);

	/**
	 * Activate plugin.
	 */
	tests_add_filter(
		'muplugins_loaded',
		function () {
			require dirname( __DIR__, 2 ) . '/vendor/wordpress/sqlite-database-integration/load.php';
		}
	);

} else {

	// Undo changes if necessary.
	if ( file_exists( dirname( __DIR__, 2 ) . '/wordpress/wp-content/db.php' ) ) {
		unlink( dirname( __DIR__, 2 ) . '/wordpress/wp-content/db.php' );
		$remaining_files = array_diff(
			scandir( dirname( __DIR__, 2 ) . '/wordpress/wp-content/' ),
			array( '..', '.' )
		);
		if ( empty( $remaining_files ) ) {
			rmdir( dirname( __DIR__, 2 ) . '/wordpress/wp-content/' );
		}
	}

}

/**
 * Registers theme.
 */
tests_add_filter(
	'muplugins_loaded',
	function () {
		// Activate the theme being developed in this repository.
		$theme_dir     = dirname( __DIR__, 2 );
		$current_theme = basename( $theme_dir );
		$theme_root    = dirname( $theme_dir );

		tests_add_filter( 'theme_root', fn() => $theme_root );
		register_theme_directory( $theme_root );
		tests_add_filter( 'pre_option_template', fn() => $current_theme );
		tests_add_filter( 'pre_option_stylesheet', fn() => $current_theme );
	}
);

// Start up the WP testing environment.
require getenv( 'WP_PHPUNIT__DIR' ) . '/includes/bootstrap.php';
