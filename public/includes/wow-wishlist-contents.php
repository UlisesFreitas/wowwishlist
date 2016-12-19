<?php
$this->wow_wishlist_settings=get_option('wow_wishlist_settings');
$need_to_log_in=$this->wow_wishlist_settings['wow_wishlist_for'];

if($need_to_log_in=='logged_in_users')
{
	if(is_user_logged_in()){
		require("wow-wishlist-items.php");
	}else{
		echo __("Please log in to use this feature",'wow-wishlist');
		echo do_shortcode("[woocommerce_my_account]");
	}
}else{
	require("wow-wishlist-items.php");
}
?>