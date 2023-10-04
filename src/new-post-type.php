<?php
/**
 * Register the plugin's custom post type.
 *
 * @package WordPress_Plugin_Name
 */

namespace WordPress_Plugin_Name;

// Alert the user if Advanced Custom Fields is not installed.
if ( ! class_exists( 'acf' ) ) {
	add_action(
		'admin_notices',
		function () {
			?>
			<div class="notice notice-error">
				<p><?php esc_html_e( 'WordPress Plugin Name requires Advanced Custom Fields to be installed and activated.', 'wordpress-plugin-name-textdomain' ); ?></p>
			</div>
			<?php
		}
	);
}

add_action(
	'acf/init',
	fn () => require 'advanced-custom-fields/new-post-type.php'
);

add_action(
	'init',
	function () {
		register_post_type(
			POST_TYPE_KEY,
			array(
				'labels'           => array(
					'singular_name' => 'New Post Type',
					'name'          => 'New Post Types',
				),
				'description'      => 'Demonstrate how to provide a custom post type.',
				'public'           => true,
				'show_in_rest'     => true,
				'menu_position'    => 20,
				'menu_icon'        => 'dashicons-portfolio',
				'supports'         => array(
					'title',
					'editor',
					'author',
					'custom-fields',
					'page-attributes',
					'excerpt',
					'thumbnail',
				),
				'has_archive'      => true,
				'rewrite'          => array( 'slug' => 'custom-posts' ),
				'delete_with_user' => false,
			),
		);

		add_action(
			'wp_enqueue_scripts',
			function () {
				if ( is_singular( POST_TYPE_KEY ) ) {
					wp_enqueue_script(
						PLUGIN_KEY . '-single-' . POST_TYPE_KEY,
						PLUGIN_URL . 'assets/js/single-new-post-type.js',
						array(),
						filemtime( __DIR__ . '/assets/js/single-new-post-type.js' ),
						true
					);
				}
			}
		);

		add_action(
			'wp_enqueue_scripts',
			function () {
				if ( is_singular( POST_TYPE_KEY ) ) {
					wp_enqueue_style(
						PLUGIN_KEY . '-single-' . POST_TYPE_KEY,
						PLUGIN_URL . 'assets/css/single-new-post-type.css',
						array(),
						filemtime( __DIR__ . '/assets/css/single-new-post-type.css' )
					);
				}
			}
		);

		add_action(
			'wp_enqueue_scripts',
			function () {
				if ( is_post_type_archive( POST_TYPE_KEY ) ) {
					wp_enqueue_style(
						PLUGIN_KEY . '-single-' . POST_TYPE_KEY,
						PLUGIN_URL . 'assets/css/archive-new-post-type.css',
						array(),
						filemtime( __DIR__ . '/assets/css/archive-new-post-type.css' )
					);
				}
			}
		);

		add_filter(
			'the_content',
			function ( $content ) {
				if ( is_singular( POST_TYPE_KEY ) ) {
					return render( 'views/content-new-post-type.php', array( 'content' => $content ) );
				}
				return $content;
			}
		);

		add_filter(
			'the_excerpt',
			function ( $excerpt ) {
				if ( get_post_type() === POST_TYPE_KEY && ! is_singular( POST_TYPE_KEY ) ) {
					return render( 'views/excerpt-new-post-type.php', array( 'excerpt' => $excerpt ) );
				}
				return $excerpt;
			}
		);
	}
);

register_activation_hook(
	PLUGIN_FILE,
	function () {
		flush_rewrite_rules();
	}
);

register_deactivation_hook(
	PLUGIN_FILE,
	function () {
		unregister_post_type( POST_TYPE_KEY );
		flush_rewrite_rules();
	}
);
