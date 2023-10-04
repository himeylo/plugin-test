<?php
/**
 * Make changes to the site to set up demo content, etc.
 *
 * @package   WordPress_Plugin_Name
 * @copyright 2023 Texas A&M Transportation Institute
 * @author    Texas A&M Transportation Institute, Communications Division <webmaster@tti.tamu.edu>
 * @license   GPL-2.0-or-later
 */

namespace WordPress_Plugin_Name;

if ( ! defined( 'ABSPATH' ) ) {
	die( 'We\'re sorry, but you can not directly access this file.' );
}

if ( 'local' !== wp_get_environment_type() || strpos( get_site_url(), 'localhost' ) === false ) {
	// Return early if this file is not running in a local developer environment for WordPress.
	return;
}

add_action(
	'init',
	function () {
		// Create a demo post for the custom post type.
		$id = create_demo_post();
		if ( site_uses_default_theme() && get_option( 'page_on_front' ) !== $id ) {
			// Assign the demo post to the site's home page.
			update_option( 'show_on_front', 'page' );
			update_option( 'page_on_front', $id );
		}
	}
);

/** Create a demo post for the custom post type. */
function create_demo_post(): int {
	$get_demo_post = new \WP_Query(
		array(
			'post_type'      => POST_TYPE_KEY,
			'post_status'    => 'any',
			'posts_per_page' => 1,
			'fields'         => 'ids',
			'post_title'     => 'Plugin Demo Page',
		)
	);
	if ( ! empty( $get_demo_post->posts ) ) {
		return $get_demo_post->posts[0];
	}
	$post_id = wp_insert_post(
		array(
			'post_title'   => 'Plugin Demo Page',
			'post_content' => '<!-- wp:paragraph -->
<p>This post provides a custom post type named "New Post Type".</p>
<!-- /wp:paragraph -->
<!-- wp:paragraph -->
<p>It also provides a shortcode named "my-shortcode". The output is shown below:</p>
<!-- /wp:paragraph -->
<!-- wp:shortcode -->
[my-shortcode]
<!-- /wp:shortcode -->',
			'post_status'  => 'publish',
			'post_type'    => POST_TYPE_KEY,
			'post_author'  => 1,
		)
	);

	// Set the Advanced Custom Fields values for the demo post.
	update_field( 'post_field_1', 'This is the value of field 1.', $post_id );
	update_field( 'post_field_2', 'Option A', $post_id );
	update_field( 'post_field_3', 'Option B', $post_id );
	return $post_id;
}

/** Whether the current site is using a default WordPress theme. */
function site_uses_default_theme() {
	// Only modify the default homepage if the site uses a standard WordPress theme.
	$current_theme      = wp_get_theme();
	$wp_themes          = array( 'Twenty Twenty', 'Twenty Twenty-One', 'Twenty Twenty-Two', 'Twenty Twenty-Three', 'Twenty Twenty-Four' );
	$current_theme_name = $current_theme->get( 'Name' );
	return in_array( $current_theme_name, $wp_themes, true );
}
