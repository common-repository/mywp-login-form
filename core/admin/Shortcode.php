<?php

namespace Whodunit\MywpLoginForm\Admin;

use Whodunit\MywpLoginForm\Init\Core;

/**
 * Register the shortcode for the login form.
 */
class Shortcode {

	protected $core;
	private   $slug_attributes = 'mwp-login-form';

	public function __construct( Core $Core ) {
		$this->core = $Core;
		add_shortcode( 'mywp_login_form', [ $this, 'register_shortcode' ] );
	}

	/**
	 * Apply filter to register the ids where the shortcode is allowed.
	 *
	 * @return array
	 */
	public static function authorized_ids() {
		return (array) apply_filters( 'mwplf_authorized_ids_shortcode', [] );
	}

	/**
	 * Register the shortcode.
	 *
	 * @param array $attrs Shortcode attributes.
	 *
	 * @return string the shortcode output.
	 */
	public function register_shortcode( $attrs ) {
		$array_default = json_decode( file_get_contents( $this->core->base_dir . '/core/admin/attributes/' . $this->slug_attributes . '.json' ), true );

		$array_default_format = array();
		foreach ( $array_default as $key => $value ) {
			$array_default_format[ $key ] = $value['default'];
		}

		$attributes              = shortcode_atts( $array_default_format, $attrs );
		$attributes['shortcode'] = true;
		$attributes['slug']      = $this->slug_attributes;

		ob_start();
		include( $this->core->base_dir . '/core/views/template-blocks/' . $this->slug_attributes . '.php' );

		return ob_get_clean();
	}
}
