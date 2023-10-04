<?php
/**
 * WordPress configuration file for tests.
 *
 * @package WordPress Theme
 */

/* Path to the WordPress codebase you'd like to test. Add a forward slash in the end. */
define( 'ABSPATH', dirname( __DIR__, 2 ) . DIRECTORY_SEPARATOR . 'wordpress' . DIRECTORY_SEPARATOR );

/*
 * Path to the theme to test with.
 *
 * The 'default' theme is symlinked from test/phpunit/data/themedir1/default into
 * the themes directory of the WordPress installation defined above.
 */
define( 'WP_DEFAULT_THEME', 'default' );

/*
 * Test with multisite enabled.
 * Alternatively, use the tests/phpunit/multisite.xml configuration file.
 * define( 'WP_TESTS_MULTISITE', true );
 */

/*
 * Force known bugs to be run.
 * Tests with an associated Trac ticket that is still open are normally skipped.
 * define( 'WP_TESTS_FORCE_KNOWN_BUGS', true );
 */

// Test with WordPress debug mode (default).
define( 'WP_DEBUG', true );

// ** MySQL settings ** //

/*
 * This configuration file will be used by the copy of WordPress being tested.
 * wordpress/wp-config.php will be ignored.
 *
 * WARNING WARNING WARNING!
 * These tests will DROP ALL TABLES in the database with the prefix named below.
 * DO NOT use a production database or one that is shared with something else.
 */

define( 'DB_NAME', getenv( 'WP_DB_NAME' ) ? getenv( 'WP_DB_NAME' ) : 'wp_phpunit_tests' );
define( 'DB_USER', getenv( 'WP_DB_USER' ) ? getenv( 'WP_DB_USER' ) : 'root' );
define( 'DB_PASSWORD', getenv( 'WP_DB_PASS' ) ? getenv( 'WP_DB_PASS' ) : '' );
define( 'DB_HOST', getenv( 'WP_DB_HOST' ) ? getenv( 'WP_DB_HOST' ) : 'localhost' );
define( 'DB_CHARSET', 'utf8' );
define( 'DB_COLLATE', '' );

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 */
define( 'AUTH_KEY', 'Hzm[vQ+#a&6BQw_h-yhokNuY=^67F6~s*Z.tF,6gfR6cB2898Dy^|Ae.p=9H/b@o' );
define( 'SECURE_AUTH_KEY', 'NoDfHZsLjwa<X4of@r3T3V2).ykjRX6><5vxnQ5y98_r,-&A!_@$]0h~JUrU|w;l' );
define( 'LOGGED_IN_KEY', '!Y)$v-sBGNCEe@yb*6c5;C^ERb)ZMY;{%%nEB!VlEfr]G;204wA5g!i?fdIU)Ev]' );
define( 'NONCE_KEY', 'eJye/+h6(;D,3j1fXT&J>|MhUuUrZ[O4Wp?tkaP|mc.IpRJ=b1gjVvy3Y!P`X7G!' );
define( 'AUTH_SALT', '0<z&/=9#VC-}1-oxj]>k(~B,8j6j)` @[Sb4g^2?6b`POAq=Xnhw#9b_^0:B|7uN' );
define( 'SECURE_AUTH_SALT', '5/2Y.mk7qhSurGCIvf21nC%uNqBHMPQ0Y-7pt~84P$fnRLt9?)K|{@WA4hpgJDUn' );
define( 'LOGGED_IN_SALT', '4<{=rx[xj2Yb%_C|;PB[+,6$+{:|>/>cET&]vX4iJ:kJ#iFm--JPMqdXFM-xrvK8' );
define( 'NONCE_SALT', '7xGQd(2!:R IS@yB$f?Dm^m52aB-m?%?}V i_8rY&7=,Id4-%XRT5Ql]gC@<{/:+' );

$table_prefix = 'tests_';   // Only numbers, letters, and underscores please!

define( 'WP_TESTS_DOMAIN', 'localhost' );
define( 'WP_TESTS_EMAIL', 'admin@example.org' );
define( 'WP_TESTS_TITLE', 'Test Blog' );

define( 'WP_PHP_BINARY', 'php' );

define( 'WPLANG', '' );
