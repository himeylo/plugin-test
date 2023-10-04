<?php
/**
 * Reusable custom functions.
 *
 * @package WordPress_Plugin_Name
 */

namespace WordPress_Plugin_Name;

/**
 * Get the rendered output.
 *
 * @see https://developer.wordpress.org/reference/functions/wp_kses/
 * @see https://www.php.net/manual/en/function.extract.php
 * @see https://www.php.net/manual/en/function.ob-start.php
 * @see https://www.php.net/manual/en/function.ob-get-clean.php
 *
 * @param string $file The path to the PHP file.
 * @param array  $props Associative array of variable names and values available to the file as an object.
 * @return string The rendered HTML.
 */
function render( string $file, $props ) { // phpcs:ignore Generic.CodeAnalysis.UnusedFunctionParameter.FoundAfterLastUsed
	// Convert the props into an object with keys in lowercase snake case format.
	$props = (object) array_combine(
		array_map(
			fn ( $key ) => strtolower( str_replace( '-', '_', $key ) ),
			array_keys( $props )
		),
		array_values( $props )
	);
	ob_start();
	include $file;
	$rendered                    = ob_get_clean();
	$post_allowed_html           = wp_kses_allowed_html( 'post' );
	$post_allowed_html['script'] = array(
		'type' => true,
	);
	return wp_kses( $rendered, $post_allowed_html );
}
