<?php

namespace Whodunit\MywpLoginForm\Utility;

class Helpers {

	/**
	 * Check if boolean exist in $attributes and set fallback
	 *
	 * @param array $attributes
	 * @param string $key
	 * @param boolean $default_value
	 *
	 * @return boolean
	 */
	public static function check_boolean( $attributes, $key, bool $default_value = true ): bool {
		return ( isset( $attributes[ $key ] ) ) ? (bool) $attributes[ $key ] : $default_value;
	}

	/**
	 * Check if input exist in $attributes and set fallback
	 *
	 * @param array $attributes
	 * @param string|int $key
	 * @param string|int $default_value
	 *
	 * @return void
	 */
	public static function check_input( $attributes, $key, $default_value ) {
		return ( isset( $attributes[ $key ] ) && ! empty( $attributes[ $key ] ) ) ? strip_tags( $attributes[ $key ] ) : $default_value;
	}
}