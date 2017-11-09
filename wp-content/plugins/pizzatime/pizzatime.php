<?php
/**
 * Plugin Name: PizzaTime
 * Plugin URI:  http://wppizzatime.com
 * Description: Visual pizza builder for WooCommerce.
 * Author: Sergey Burkov
 * Text Domain: pizzatime
 * Domain Path: /languages/
 * Version: 1.0.8.6
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

global $wpdb;
if ( !function_exists( 'get_home_path' ) ) {
	require_once ABSPATH . '/wp-admin/includes/file.php';
}

include 'includes/pizzatime-functions.php';

if ( is_admin() ) {
	add_action( 'admin_enqueue_scripts', 'pizzatime_enqueue_scripts_backend' );
	include 'includes/pizzatime-admin.php';
}
else {
	add_action( 'wp_enqueue_scripts', 'pizzatime_enqueue_scripts_frontend' );
}


register_activation_hook( __FILE__, 'pizzatime_activate' );
register_deactivation_hook( __FILE__, 'pizzatime_deactivate' );

add_action('init', 'pizzatime_check_installation');
function pizzatime_check_installation() {

	if ( ! function_exists( 'get_plugin_data' ) ) {
		require_once ABSPATH . 'wp-admin/includes/plugin.php';
	}
	$pizzatime_plugin_data = get_plugin_data(  __FILE__ );
	$pizzatime_current_version = get_option('pizzatime_version');

	if (!empty($pizzatime_current_version) && version_compare($pizzatime_current_version, $pizzatime_plugin_data['Version'], '<')) {
		pizzatime_check_install();
	}
}

?>