<?php
/**
 * Assets for the plugin.
 *
 * @package   Common
 * @copyright 2023 Texas A&M Transportation Institute
 * @author    Texas A&M Transportation Institute, Communications Division <webmaster@tti.tamu.edu>
 * @license   GPL-2.0-or-later
 */

namespace Common;

/**
 * Create shortcode to display a file within the `views` folder.
 */
class Shortcode {

	/**
	 * Initialize the class
	 *
	 * @param string       $name         The name of the shortcode used in markup.
	 * @param string       $file         The file that renders the shortcode content.
	 * @param array        $attributes   The allowed shortcode attributes.
	 * @param array|string $allowed_html An array of allowed HTML elements and attributes, or a
	 *                                   context name such as 'post'. Default value 'post'. For
	 *                                   the list of accepted context names, see
	 *                                   https://developer.wordpress.org/reference/functions/wp_kses_allowed_html/.
	 * @return void
	 */
	public function __construct(
		protected string $name = 'my-shortcode',
		protected string $file = 'my-shortcode.php',
		protected array $attributes = array( 'id' => 'my-shortcode' ),
		protected $allowed_html = 'post'
	) {
		add_shortcode( $name, array( $this, 'view' ) );
	}

	/**
	 * Rendering function for the shortcode content.
	 *
	 * @param array $atts Shortcode attributes.
	 * @return string
	 */
	public function view( $atts ) {

		$atts = shortcode_atts( $this->attributes, $atts );

		ob_start();
		// Included file can reference the $atts associative array.
		include __DIR__ . '/../views/' . $this->file;
		$output = ob_get_clean();

		return wp_kses( $output, $this->allowed_html );
	}
}
