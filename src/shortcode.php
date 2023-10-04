<?php
/**
 * Register a new shortcode.
 *
 * @package WordPress_Plugin_Name
 */

namespace WordPress_Plugin_Name;

add_shortcode(
	'my-shortcode',
	function ( $atts ) {
		wp_enqueue_script(
			PLUGIN_KEY . '-my-shortcode-script',
			PLUGIN_URL . 'assets/js/my-shortcode.js',
			array(),
			filemtime( __DIR__ . '/assets/js/my-shortcode.js' ),
			true
		);

		wp_enqueue_style(
			PLUGIN_KEY . '-my-shortcode-style',
			PLUGIN_URL . 'assets/css/my-shortcode.css',
			array(),
			filemtime( __DIR__ . '/assets/css/my-shortcode.css' )
		);

		return render(
			'views/my-shortcode.php',
			shortcode_atts( array( 'id' => 'my-shortcode' ), $atts )
		);
	}
);
