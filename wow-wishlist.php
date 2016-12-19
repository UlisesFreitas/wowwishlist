<?php

/**
 * @wordpress-plugin
 * Plugin Name:       WOW Wishlist for woocommerce
 * Plugin URI:        https://disenialia.com/
 * Description:       WOW Wishlist for woocommerce allow you to add wishlist functionality to your ecommerce store
 * Version:           1.0.0
 * Author:            UlisesFreitas
 * Author URI:        https://disenialia.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       wow-wishlist
 * Domain Path:       /languages
 */

if ( ! defined( 'WPINC' ) ) {
	die;
}
if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) )  ) {

	function activate_wow_wishlist() {
		require_once plugin_dir_path( __FILE__ ) . 'includes/class-wow-wishlist-activator.php';
		wow_wishlist_Activator::wow_wishlist_page();
	}

	function deactivate_wow_wishlist() {
		require_once plugin_dir_path( __FILE__ ) . 'includes/class-wow-wishlist-deactivator.php';
		wow_wishlist_Deactivator::deactivate();
	}

	register_activation_hook( __FILE__, 'activate_wow_wishlist' );

	register_deactivation_hook( __FILE__, 'deactivate_wow_wishlist' );

	require plugin_dir_path( __FILE__ ) . 'includes/class-wow-wishlist.php';

	function run_wow_wishlist() {
		$plugin = new wow_wishlist();
		$plugin->run();
	}

	run_wow_wishlist();

	function wow_wishlist_settings_link( $links ) {
		$settings_link = '<a href="admin.php?page=wow_wishlist">' . __( 'Configuration', 'wow-wishlist' ) . '</a>';
		array_push( $links, $settings_link );
		return $links;
	}

	$plugin = plugin_basename( __FILE__ );
	add_filter( "plugin_action_links_$plugin", 'wow_wishlist_settings_link');
}