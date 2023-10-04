<?php
/**
 * Shortcode content for [my-shortcode].
 * phpcs:ignorefile WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound
 *
 * @package WordPress_Plugin_Name
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( 'We\'re sorry, but you can not directly access this file.' );
}

// $props is provided to this file by the `render()` function.
?><div id="<?= $props->id ?>">Hello, WordPress!</div>
<p>Would you like to see some API data?</p>
<?php
$url      = 'https://api.sampleapis.com/coffee/hot';
$response = wp_remote_get( $url );

if ( is_wp_error( $response ) ) {
	?>
	<p>Sorry, but it's an error: <strong>
	<?php
	echo esc_html( $response->get_error_message() );
	?>
	</strong></p>
	<?php
} else {
	?>
	<p>Here's the API response:</p>
	<pre>
	<?php
	$body = wp_remote_retrieve_body( $response );
	$body = wp_json_encode( json_decode( $body ), JSON_PRETTY_PRINT );
	echo esc_html( $body );
	?>
	</pre>
	<?php
}
