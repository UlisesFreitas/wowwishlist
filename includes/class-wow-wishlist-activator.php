<?php

class wow_wishlist_Activator {

	public function  wow_wishlist_page(){
		$page = get_page_by_path( 'wishlist' );
		if(!$page):
			$post = array(
				'post_content'   => '[wow_wishlist]',
				'post_name'      => 'wishlist',
				'post_title'     => 'Wishlist',
				'post_status'    => 'publish',
				'post_type'      => 'page',
			);
		wp_insert_post( $post );
		endif;
	}
}