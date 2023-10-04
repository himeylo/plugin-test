<?php
/**
 * The file that registers the plugin's publicly available CSS and/or JS files.
 *
 * @package   Common
 * @copyright 2023 Texas A&M Transportation Institute
 * @author    Texas A&M Transportation Institute, Communications Division <webmaster@tti.tamu.edu>
 * @license   GPL-2.0-or-later
 */

namespace Common;

/**
 * The class that registers public web assets.
 */
class Assets {

	/**
	 * The base name of the plugin file.
	 *
	 * @var string
	 */
	protected string $prefix = '';

	/**
	 * Initialize the class
	 *
	 * @param string $plugin_file The root plugin file.
	 * @param array  $js_paths The paths to the JS files to be registered.
	 * @param array  $css_paths The paths to the CSS files to be registered.
	 * @param mixed  $condition Optional. A callable function for the condition to be met before registering the assets.
	 * @return void
	 */
	public function __construct(
		protected string $plugin_file,
		protected array $js_paths = array(),
		protected array $css_paths = array(),
		protected $condition = null
	) {
		$this->prefix = basename( $this->plugin_file, '.php' );
		add_action( 'wp_enqueue_scripts', array( $this, 'register_public_scripts' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_public_scripts' ) );
	}

	/**
	 * Registers public scripts.
	 *
	 * @return void
	 */
	public function register_public_scripts() {

		if ( $this->condition && ! call_user_func( $this->condition ) ) {
			return;
		}
		foreach ( $this->css_paths as $key => $path ) {
			wp_register_style(
				"{$this->prefix}-{$key}-style",
				plugin_dir_url( $this->plugin_file ) . $path,
				array(),
				filemtime( dirname( $this->plugin_file ) . '/' . $path ),
				'screen'
			);
		}

		foreach ( $this->js_paths as $key => $path ) {
			wp_register_script(
				"{$this->prefix}-{$key}-script",
				plugin_dir_url( $this->plugin_file ) . $path,
				array(),
				filemtime( dirname( $this->plugin_file ) . '/' . $path ),
				true
			);
		}
	}

	/**
	 * Enqueues public scripts.
	 *
	 * @return void
	 */
	public function enqueue_public_scripts() {

		if ( $this->condition && ! call_user_func( $this->condition ) ) {
			return;
		}
		foreach ( $this->css_paths as $key => $path ) {
			wp_enqueue_style( "{$this->prefix}-{$key}-style" );
		}
		foreach ( $this->js_paths as $key => $path ) {
			wp_enqueue_script( "{$this->prefix}-{$key}-script" );
		}
	}
}
