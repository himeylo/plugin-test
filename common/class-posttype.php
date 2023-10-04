<?php
/**
 * The file that initializes custom post types.
 *
 * @package    WordPress_Plugin_Name
 * @subpackage Common
 */

namespace Common;

/**
 * The post type registration class
 */
class PostType {

	/**
	 * Default post type registration arguments.
	 *
	 * @var array
	 */
	private $default_args = array(
		'can_export'         => true,
		'has_archive'        => true,
		'hierarchical'       => false,
		'menu_icon'          => 'dashicons-portfolio',
		'menu_position'      => 20,
		'public'             => true,
		'publicly_queryable' => true,
		'show_in_rest'       => true,
		'show_in_menu'       => true,
		'show_in_admin_bar'  => true,
		'show_in_nav_menus'  => true,
		'show_ui'            => true,
		'supports'           => array(
			'title',
			'editor',
			'revisions',
			'author',
			'custom-fields',
			'page-attributes',
			'thumbnail',
		),
		'delete_with_user'   => false,
	);

	/**
	 * Builds and registers the custom taxonomy.
	 *
	 * @param  string $plugin_file The plugin file.
	 * @param  string $post_type   The post type slug.
	 * @param  string $singular    The singular post label.
	 * @param  string $plural      The plural post label.
	 * @param  array  $args        Additional user arguments which override all others for the function register_post_type.
	 * @param  array  $templates   {
	 *     The post type templates for archive or single views.
	 *     @key string $single  The single post template.
	 *     @key string $archive The archive post template.
	 * }
	 * @return void
	 */
	public function __construct(
		private string $plugin_file,
		private string $post_type,
		private string $singular,
		private string $plural,
		private array $args = array(),
		private array $templates = array()
	) {
		// Register the post type.
		add_action( 'init', array( $this, 'register' ) );

		// Register post type templates.
		if ( ! empty( $this->templates ) ) {
			if ( isset( $this->templates['single'] ) ) {
				add_filter( 'single_template', array( $this, 'get_single_template' ) );
			}
			if ( isset( $this->templates['archive'] ) ) {
				add_filter( 'archive_template', array( $this, 'get_archive_template' ) );
			}
		}

		// Add an activation hook for flushing rewrite rules.
		// Checks to ensure it is not yet hooked.
		if ( false === has_action( "activate_{$this->plugin_file}", 'flush_rewrite_rules' ) ) {
			register_activation_hook( $this->plugin_file, 'flush_rewrite_rules' );
		}

		// Add a deactivation hook for unregistering the post type and flushing rewrite rules.
		// Checks to ensure it is not yet hooked.
		if ( false === has_action( "deactivate_{$this->plugin_file}", array( $this, 'unregister' ) ) ) {
			register_deactivation_hook( $this->plugin_file, array( $this, 'unregister' ) );
		}
	}

	/**
	 * Register the post type.
	 *
	 * @return void
	 */
	public function register() {

		// Create default post type labels argument if not defined in the args parameter.
		if ( ! isset( $this->args['labels'] ) ) {
			$labels                       = array(
				'name'               => $this->plural,
				'singular_name'      => $this->singular,
				'add_new'            => __( 'Add New', 'wordpress-plugin-name-textdomain' ),
				'add_new_item'       => __( 'Add New', 'wordpress-plugin-name-textdomain' ) . " {$this->singular}",
				'edit_item'          => __( 'Edit', 'wordpress-plugin-name-textdomain' ) . " {$this->singular}",
				'new_item'           => __( 'New', 'wordpress-plugin-name-textdomain' ) . " {$this->singular}",
				'view_item'          => __( 'View', 'wordpress-plugin-name-textdomain' ) . " {$this->singular}",
				'search_items'       => __( 'Search', 'wordpress-plugin-name-textdomain' ) . " {$this->plural}",
				/* translators: placeholder is the plural taxonomy name */
				'not_found'          => sprintf( esc_html__( 'No %s Found', 'wordpress-plugin-name-textdomain' ), $this->plural ),
				/* translators: placeholder is the plural taxonomy name */
				'not_found_in_trash' => sprintf( esc_html__( 'No %s found in trash', 'wordpress-plugin-name-textdomain' ), $this->plural ),
				'parent_item_colon'  => '',
				'menu_name'          => $this->plural,
			);
			$this->default_args['labels'] = $labels;
		}

		$args = array_merge( $this->default_args, $this->args );
		register_post_type( $this->post_type, $args );
	}

	/**
	 * Unregister the post type.
	 *
	 * @return void
	 */
	public function unregister() {

		unregister_post_type( $this->post_type );
		flush_rewrite_rules();
	}

	/**
	 * Shows which single template is needed
	 *
	 * @param  string $single_template The default single template.
	 * @return string
	 */
	public function get_single_template( $single_template ) {

		if ( get_query_var( 'post_type' ) === $this->post_type ) {
			$single_template = dirname( $this->plugin_file ) . '/' . $this->templates['single'];
		}

		return $single_template;
	}

	/**
	 * Shows which archive template is needed
	 *
	 * @param  string $archive_template The default archive template.
	 * @return string
	 */
	public function get_archive_template( $archive_template ) {

		if ( get_query_var( 'post_type' ) === $this->post_type ) {
			$archive_template = dirname( $this->plugin_file ) . '/' . $this->templates['archive'];
		}

		return $archive_template;
	}
}
