<?php

namespace Whodunit\MywpLoginForm\Init;

class Core extends Plugin {

	protected      $main_file;
	private static $_instance;

	public function __construct( $main_file = null ) {
		$this->main_file = $main_file;
		parent::__construct();
		$this->init();

		add_action( 'plugins_loaded', array( $this, 'plugins_loaded' ) );
	}

	/**
	 * Plugin init setup
	 *
	 * @return void
	 */
	public function init() {
		$this->init_class( 'Gutenberg', 'Admin' );
		$this->init_class( 'Shortcode', 'Admin' );
	}

	/**
	 * Load text domain when the plugin is loaded
	 *
	 * @return void
	 */
	public function plugins_loaded() {
		load_plugin_textdomain( 'mywp-login-form', false, dirname( $this->plugin_basename ) . '/languages/' );
	}

	/**
	 * Init class easily to avoid lot of namespace use
	 *
	 * @param string $name      Class name to init
	 * @param string $namespace Namespace to use
	 *
	 * @return mixed Class instance
	 */
	public function init_class( $name, $namespace ) {
		$class_name = '\\Whodunit\\MywpLoginForm\\' . $namespace . '\\' . ( $name );
		return new $class_name( $this );
	}

	/**
	 * Get instance of this class
	 *
	 * @return Core Instance of this class
	 */
	public static function get_instance( $main_file ): Core {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new Core( $main_file );
		}

		return self::$_instance;
	}
}