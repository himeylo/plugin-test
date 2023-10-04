<?php
/**
 * WordPress Plugin Name
 *
 * @package   WordPress_Plugin_Name
 * @copyright 2023 Texas A&M Transportation Institute
 * @author    Texas A&M Transportation Institute, Communications Division <webmaster@tti.tamu.edu>
 * @license   GPL-2.0-or-later
 *
 * @wordpress-plugin
 * Plugin Name:       WordPress Plugin Name
 * Plugin URI:        https://github.com/ttitamu/com-wp-plugin-template
 * Description:       A template WordPress plugin.
 * Version:           1.0.0
 * Requires at least: 5.2
 * Requires PHP:      8.1
 * Author:            Texas A&M Transportation Institute, Communications Division
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       wordpress-plugin-name-textdomain
 * Update URI:        https://github.com/ttitamu/com-wp-plugin-template
 */

namespace WordPress_Plugin_Name;

if ( ! defined( 'ABSPATH' ) ) {
	die( 'We\'re sorry, but you can not directly access this file.' );
}

const PLUGIN_FILE   = __FILE__;
const PLUGIN_KEY    = 'wordpress-plugin-name';
const POST_TYPE_KEY = 'new_post_type';
// Using a function to define a constant. Only supported by the `define()` function.
define( 'WordPress_Plugin_Name\PLUGIN_URL', plugins_url( 'src', __FILE__ ) . '/' );

require 'src/demo.php';
require 'src/functions.php';
require 'src/shortcode.php';
require 'src/new-post-type.php';

// Register the plugin's sitewide JS and CSS files.
add_action(
	'wp_enqueue_scripts',
	function () {
		wp_enqueue_script(
			PLUGIN_KEY . '-public',
			PLUGIN_URL . 'assets/js/public.js',
			array(),
			filemtime( __DIR__ . '/src/assets/js/public.js' ),
			true
		);
		wp_enqueue_style(
			PLUGIN_KEY . '-public-style',
			PLUGIN_URL . 'assets/css/public.css',
			array(),
			filemtime( __DIR__ . '/src/assets/css/public.css' )
		);
	}
);
