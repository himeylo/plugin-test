<?php
/**
 * The file that defines a custom taxonomy
 *
 * @package    WordPress_Plugin_Name
 * @subpackage Common
 */

namespace Common;

/**
 * Builds and registers a custom taxonomy.
 */
class Taxonomy {
	/**
	 * Taxonomy default arguments.
	 *
	 * @access private
	 * @var    array
	 */
	private $default_args = array(
		'show_ui'            => true,
		'show_in_rest'       => true,
		'show_in_quick_edit' => true,
		'show_admin_column'  => true,
	);

	/**
	 * Taxonomy meta boxes
	 *
	 * @access private
	 * @var    array $meta_boxes Stores taxonomy meta boxes
	 */
	private $meta_boxes = array();

	/**
	 * Taxonomy template file path for the archive page
	 *
	 * @access protected
	 * @var    file $template Stores taxonomy archive template file path
	 */
	protected $template;

	/**
	 * Builds and registers the custom taxonomy.
	 *
	 * @param  string          $plugin_file  The plugin file.
	 * @param  string          $taxonomy     The taxonomy slug.
	 * @param  string          $singular     The label in singular form.
	 * @param  string          $plural       The label in plural form.
	 * @param  string|string[] $post_types   The slug of the post type where the taxonomy will be added.
	 * @param  array           $args         The arguments for taxonomy registration. Accepts    $args for
	 *                                       the WordPress core register_taxonomy function.
	 * @param  array           $meta         Array (single or multidimensional) of custom fields to add to a
	 *                                       taxonomy item edit page. Requires 'name', 'slug', and 'type'.
	 * @param  boolean         $admin_filter Whether the taxonomy has a filter drop-down menu in the admin post list interface.
	 * @param  boolean         $sortable     Whether the taxonomy should be sortable from the admin dashboard.
	 * @param  string          $template     The template file path for the taxonomy archive page.
	 * @return void
	 */
	public function __construct(
		private string $plugin_file,
		private string $taxonomy,
		private string $singular,
		private string $plural,
		private $post_types,
		private array $args = array(),
		$meta = array(),
		$admin_filter = false,
		$sortable = false,
		$template = null
	) {
		// Validate the taxonomy slug before proceeding.
		$this->validate_taxonomy( $this->taxonomy );

		// Register the taxonomy.
		add_action( 'init', array( $this, 'register' ) );

		// Create taxonomy custom fields.
		// Evaluate if the meta is an array or a nested array.
		if ( ! empty( $meta ) && is_admin() ) {
			$this->add_meta_boxes( $this->taxonomy, $meta );
		}

		// Make taxonomy sortable.
		if ( $sortable && is_admin() ) {
			$this->make_sortable( $this->post_types );
		}

		if ( $admin_filter && is_admin() ) {
			$this->add_admin_dropdown();
		}

		// Add custom template for post taxonomy views.
		if ( ! empty( $template ) ) {
			$this->template = $template;
			add_filter( 'template_include', array( $this, 'template' ) );
		}

		// Add an activation hook for flushing rewrite rules.
		// Checks to ensure it is not yet hooked.
		if ( false === has_action( "activate_{$this->plugin_file}", 'flush_rewrite_rules' ) ) {
			register_activation_hook( $this->plugin_file, 'flush_rewrite_rules' );
		}
	}

	/**
	 * Register the taxonomy.
	 *
	 * @return void
	 */
	public function register() {

		// Create default post type labels argument if not defined in the args parameter.
		if ( ! isset( $this->args['labels'] ) ) {
			$labels                       = array(
				'name'              => $this->plural,
				'singular_name'     => $this->singular,
				'search_items'      => __( 'Search', 'wordpress-plugin-name-textdomain' ) . " {$this->plural}",
				'all_items'         => __( 'All', 'wordpress-plugin-name-textdomain' ) . " {$this->plural}",
				'parent_item'       => __( 'Parent', 'wordpress-plugin-name-textdomain' ) . " {$this->singular}",
				'parent_item_colon' => __( 'Parent', 'wordpress-plugin-name-textdomain' ) . " {$this->singular}:",
				'edit_item'         => __( 'Edit', 'wordpress-plugin-name-textdomain' ) . " {$this->singular}",
				'update_item'       => __( 'Update', 'wordpress-plugin-name-textdomain' ) . " {$this->singular}",
				'add_new_item'      => __( 'Add New', 'wordpress-plugin-name-textdomain' ) . " {$this->singular}",
				/* translators: placeholder is the singular taxonomy name */
				'new_item_name'     => sprintf( esc_html__( 'New %d Name', 'wordpress-plugin-name-textdomain' ), $this->singular ),
				'menu_name'         => $this->plural,
			);
			$this->default_args['labels'] = $labels;
		}

		$args = array_merge( $this->default_args, $this->args );
		register_taxonomy( $this->taxonomy, $this->post_types, $args );
		register_taxonomy_for_object_type( $this->taxonomy, $this->post_types );
	}

	/**
	 * Add actions to render and save custom fields
	 *
	 * @param string $taxonomy The taxonomy slug.
	 * @param array  $meta     Array (single or multidimensional) of custom fields to add to a
	 *                         taxonomy item edit page. Requires 'name', 'slug', and 'type'.
	 *
	 * @return void
	 */
	public function add_meta_boxes( $taxonomy, $meta ) {
		// Ensure the meta_boxes variable is an array of arrays.
		if ( ! array_key_exists( 0, $meta ) ) {
			$this->meta_boxes[] = $meta;
		} else {
			$this->meta_boxes = $meta;
		}
		// Add action hooks.
		add_action( "{$taxonomy}_edit_form_fields", array( $this, 'taxonomy_edit_meta_field' ), 10, 2 );
		add_action( "edited_{$taxonomy}", array( $this, 'save_taxonomy_custom_meta' ), 10 );
	}

	/**
	 * Make the taxonomy sortable.
	 *
	 * @param string|string[] $post_type The post type or types to add filters for.
	 *
	 * @return void
	 */
	public function make_sortable( $post_type ) {
		if ( ! is_array( $post_type ) ) {
			add_filter( "manage_edit-{$post_type}_sortable_columns", array( $this, 'register_sortable_columns' ) );
		} else {
			foreach ( $post_type as $taxonomy ) {
				add_filter( "manage_edit-{$taxonomy}_sortable_columns", array( $this, 'register_sortable_columns' ) );
			}
		}
		add_filter( 'posts_orderby', array( $this, 'taxonomy_orderby' ), 10, 2 );
	}

	/**
	 * Add a filter in the admin interface for the taxonomy.
	 *
	 * @return void
	 */
	private function add_admin_dropdown() {
		add_action( 'restrict_manage_posts', array( $this, 'add_posts_filter' ) );
		add_filter( 'parse_query', array( $this, 'apply_filter_to_query' ) );
	}

	/**
	 * Render custom fields
	 *
	 * @param  object $tag      Current taxonomy term object.
	 * @param  string $taxonomy Current taxonomy slug.
	 * @return void
	 */
	public function taxonomy_edit_meta_field( $tag, $taxonomy ) {

		// put the term ID into a variable.
		$t_id = $tag->term_id;

		// Make sure the form request comes from WordPress.
		wp_nonce_field( basename( __FILE__ ), "term_meta_{$taxonomy}_nonce" );

		foreach ( $this->meta_boxes as $key => $meta ) {
			// Retrieve the existing value(s) for this meta field. This returns an array.
			$taxonomy  = $meta['slug'];
			$term_meta = get_term_meta( $t_id, "term_meta_{$taxonomy}" );

			?><tr class="form-field term-<?php echo esc_attr( $taxonomy ); ?>-wrap">
				<th scope="row" valign="top"><label for="term_meta_<?php echo esc_attr( $taxonomy ); ?>"><?php echo esc_html( $meta['name'] ); ?></label></th>
				<td>
					<?php

					// Output the form field.
					switch ( $meta['type'] ) {
						case 'editor':
							$value = $term_meta ? wp_kses_post( $term_meta ) : '';
							wp_editor(
								$value,
								"term_meta_{$taxonomy}",
								array( 'textarea_name' => "term_meta_{$taxonomy}" )
							);
							break;

						case 'link':
							$value  = $term_meta ? sanitize_text_field( $term_meta ) : '';
							$output = "<input type=\"url\" name=\"term_meta_{$taxonomy}\" id=\"term_meta_{$taxonomy}\" value=\"{$value}\" placeholder=\"https://example.com\" pattern=\"http[s]?://.*\"><p class=\"description\"" . esc_html_e( 'Enter a value for this field', 'wordpress-plugin-name-textdomain' ) . '</p>';
							echo wp_kses(
								$output,
								array(
									'input' => array(
										'type'        => array(),
										'name'        => array(),
										'id'          => array(),
										'value'       => array(),
										'placeholder' => array(),
										'pattern'     => array(),
									),
									'p'     => array(
										'class' => array(),
									),
								)
							);
							break;

						case 'checkbox':
							$value  = ! empty( $term_meta ) && 'on' === $term_meta[0] ? 'checked' : '';
							$output = "<input type=\"checkbox\" name=\"term_meta_{$taxonomy}\" id=\"term_meta_{$taxonomy}\" {$value}>";
							echo wp_kses(
								$output,
								array(
									'input' => array(
										'type'    => array(),
										'name'    => array(),
										'id'      => array(),
										'checked' => array(),
									),
								)
							);
							break;

						default:
							$value  = $term_meta ? sanitize_text_field( $term_meta ) : '';
							$output = "<input type=\"text\" name=\"term_meta_{$taxonomy}\" id=\"term_meta_{$taxonomy}\" value=\"{$value}\"><p class=\"description\"" . esc_html_e( 'Enter a value for this field', 'wordpress-plugin-name-textdomain' ) . '</p>';
							echo wp_kses(
								$output,
								array(
									'input' => array(
										'type'  => array(),
										'name'  => array(),
										'id'    => array(),
										'value' => array(),
									),
									'p'     => array(
										'class' => array(),
									),
								)
							);
							break;
					}

					?>
				</td>
			</tr>
			<?php
		}
	}

	/**
	 * Save custom fields with sanitization measures.
	 *
	 * @param  int $term_id The term ID.
	 * @return void
	 */
	public function save_taxonomy_custom_meta( $term_id ) {

		// Ensure this request came from WordPress.
		$nonce_key = sanitize_key( "term_meta_{$this->taxonomy}_nonce" );
		if ( ! isset( $_POST[ $nonce_key ] ) || ! wp_verify_nonce( $nonce_key, basename( __FILE__ ) ) ) {
			return;
		}

		foreach ( $this->meta_boxes as $key => $meta ) {
			$key = sanitize_key( "term_meta_{$meta['slug']}" );

			if ( 'checkbox' === $meta['type'] ) {
				$value = isset( $_POST[ $key ] ) ? sanitize_key( wp_unslash( $_POST[ $key ] ) ) : '';
			} elseif ( 'editor' === $meta['type'] ) {
				$value = wp_kses_post( wp_unslash( $_POST[ $key ] ) );
			} else {
				$value = sanitize_text_field( wp_unslash( $_POST[ $key ] ) );
			}

			// Save the option array.
			update_term_meta( $term_id, $key, $value );
		}
	}

	/**
	 * Use a custom template file if on the taxonomy archive page.
	 * The content of such a template file may depend on your theme's structure.
	 *
	 * @param string $template The path of the template to include.
	 *
	 * @return string
	 */
	public function template( $template ) {

		if ( is_tax( $this->taxonomy ) ) {
			return $this->template;
		}

		return $template;
	}

	/**
	 * Make this taxonomy sortable from the post type dashboard list page.
	 *
	 * @param  array $columns The list of taxonomy columns sortable on this post type's list page.
	 * @return array
	 */
	public function register_sortable_columns( $columns ) {

		$columns[ "taxonomy-{$this->taxonomy}" ] = "taxonomy-{$this->taxonomy}";

		return $columns;
	}


	/**
	 * Sort this taxonomy in the dashboard by the taxonomy text value.
	 *
	 * @param  string $orderby  The SQL query which orders posts.
	 * @param  object $wp_query The query object.
	 * @return array
	 */
	public function taxonomy_orderby( $orderby, $wp_query ) {

		global $wpdb;

		// If this taxonomy is the orderby parameter, then update the SQL query.
		if ( isset( $wp_query->query['orderby'] ) && "taxonomy-{$this->taxonomy}" === $wp_query->query['orderby'] ) {
			$orderby  = "(
	      SELECT GROUP_CONCAT(name ORDER BY name ASC)
	      FROM $wpdb->term_relationships
	      INNER JOIN $wpdb->term_taxonomy USING (term_taxonomy_id)
	      INNER JOIN $wpdb->terms USING (term_id)
	      WHERE $wpdb->posts.ID = object_id
	      AND taxonomy = '{$this->taxonomy}'
	      GROUP BY object_id
	    ) ";
			$orderby .= ( 'ASC' === strtoupper( $wp_query->get( 'order' ) ) ) ? 'ASC' : 'DESC';
		}

		return $orderby;
	}

	/**
	 * Detect if a taxonomy slug is a reserved term or too long.
	 *
	 * @param string $taxonomy The taxonomy slug.
	 * @return true
	 */
	private function validate_taxonomy( $taxonomy ) {

		$valid = true;

		$reserved_terms = array(
			'attachment',
			'attachment_id',
			'author',
			'author_name',
			'calendar',
			'cat',
			'category',
			'category__and',
			'category__in',
			'category__not_in',
			'category_name',
			'comments_per_page',
			'comments_popup',
			'custom',
			'customize_messenger_channel',
			'customized',
			'cpage',
			'day',
			'debug',
			'embed',
			'error',
			'exact',
			'feed',
			'fields',
			'hour',
			'link_category',
			'm',
			'minute',
			'monthnum',
			'more',
			'name',
			'nav_menu',
			'nonce',
			'nopaging',
			'offset',
			'order',
			'orderby',
			'p',
			'page',
			'page_id',
			'paged',
			'pagename',
			'pb',
			'perm',
			'post',
			'post__in',
			'post__not_in',
			'post_format',
			'post_mime_type',
			'post_status',
			'post_tag',
			'post_type',
			'posts',
			'posts_per_archive_page',
			'posts_per_page',
			'preview',
			'robots',
			's',
			'search',
			'second',
			'sentence',
			'showposts',
			'static',
			'status',
			'subpost',
			'subpost_id',
			'tag',
			'tag__and',
			'tag__in',
			'tag__not_in',
			'tag_id',
			'tag_slug__and',
			'tag_slug__in',
			'taxonomy',
			'tb',
			'term',
			'terms',
			'theme',
			'title',
			'type',
			'types',
			'w',
			'withcomments',
			'withoutcomments',
			'year',
		);

		if ( in_array( $taxonomy, $reserved_terms, true ) ) {
			$valid = 'a reserved term';
		} elseif ( strlen( $taxonomy ) > 32 ) {
			$valid = 'longer than 32 characters';
		} elseif ( taxonomy_exists( $taxonomy ) ) {
			$valid = 'already registered';
		}

		if ( is_string( $valid ) ) {
			wp_die( esc_html( "The taxonomy \"{$taxonomy}\" encountered the following validation error and was not registered: {$valid}" ) );
		}

		return $valid;
	}

	/**
	 * Add taxonomy term select element to admin page.
	 *
	 * @return void
	 */
	public function add_posts_filter() {

		global $typenow;
		global $wp_query;

		$is_post_type_page = ! is_array( $this->post_types ) ? $typenow === $this->post_types : in_array( $typenow, $this->post_types, true );

		if ( $is_post_type_page ) {
			$taxonomy     = $this->taxonomy;
			$taxonomy_obj = get_taxonomy( $taxonomy );
			$args         = array(
				'show_option_all' => "Show All {$taxonomy_obj->label}",
				'value_field'     => 'slug',
				'taxonomy'        => $taxonomy,
				'name'            => $taxonomy,
				'orderby'         => 'name',
				'hide_empty'      => false,
			);
			$query_args   = $wp_query->query;
			if ( isset( $query_args[ $taxonomy ] ) ) {
				$args['selected'] = $query_args[ $taxonomy ];
			}
			wp_dropdown_categories( $args );
		}
	}

	/**
	 * Convert the Term ID in a query variable to a Term Slug for readability.
	 *
	 * @param WP_Query $query The main WordPress Query object for listing admin posts.
	 *
	 * @return void
	 */
	public function apply_filter_to_query( $query ) {

		global $pagenow;

		if ( 'edit.php' === $pagenow ) {
			$query_vars = &$query->query_vars;
			if (
				isset( $query_vars['taxonomy'] )
				&& $query_vars['taxonomy'] === $this->taxonomy
				&& isset( $query_vars['term'] )
				&& is_numeric( $query_vars['term'] )
			) {
				$term                      = get_term_by( 'id', $query_vars['term'], $this->taxonomy );
				$query->query_vars['term'] = $term->slug;
			}
		}
	}
}
