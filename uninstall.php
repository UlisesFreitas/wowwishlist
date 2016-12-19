<?php

if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) { exit;}

function pluginUninstall() {

     global $wpdb;
     $table = $wpdb->prefix."wow_wishlist";

	delete_option('wow_wishlist_settings');
	$wpdb->query("DROP TABLE IF EXISTS $table");

	$page = get_page_by_path( 'wishlist' );
	wp_delete_post( $page->ID, $force_delete );
}
pluginUninstall();