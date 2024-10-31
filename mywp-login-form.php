<?php
/**
 * Plugin Name: MyWP Login Form
 * Plugin URI: https://www.whodunit.fr/
 * Description: Use Gutenberg block or shortcode to add a WP login form anywhere within WordPress.
 * Version: 1.1
 * Author: Agence Whodunit
 * Author URI: https://www.whodunit.fr/
 * License: GPL-2.0
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: mywp-login-form
 * Domain Path: /languages
 */

defined( 'ABSPATH' ) or die( 'Cheatin&#8217; uh?' );

require __DIR__ . '/vendor/autoload.php';

Whodunit\MywpLoginForm\Init\Core::get_instance( __FILE__ );