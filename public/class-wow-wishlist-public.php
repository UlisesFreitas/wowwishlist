<?php
/**
 * wow_wishlist_Public class.
 */

class wow_wishlist_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * The settings data for wishlist
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      array    $wow_wishlist_settings    The settings data for wishlist
	 */
	private $wow_wishlist_settings;


	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;
		add_shortcode( 'wow_wishlist', array($this,'wow_wishlist_display_shortcode') );


	}
	/**
	 * session start if no started
	 *
	 * @since    1.0.0
	 */

	public function wow_wishlist_register_session(){
   		 if( !session_id())
       		 session_start();
	}

	/**
	 * identifying if user is logged in or not and prepare array for the wishlist table
	 *
	 * @since    1.0.0
	 */

	public function wow_wishlist_post_in_ids(){
		global $wpdb;
		$products=array();
		if(is_user_logged_in()){

			$user_id=get_current_user_id();
			$ids=$wpdb->get_results( "select product_id from " . $wpdb->prefix . "wow_wishlist  where user_id='$user_id'");
				foreach($ids as $id){
					$products[]=$id->product_id;
				}
	     }else{
				$products=$_SESSION['wow_wihslist_items'];
			}

		return $products;
	}

	/**
	 * function for shortcode
	 *
	 * @since    1.0.0
	 */
	public function wow_wishlist_display_shortcode( ) {

	 	$this->wow_wishlist_settings=get_option('wow_wishlist_settings');
		if($this->wow_wishlist_settings['wow_wishlist_enabling']!='1')
		return;
		include_once('includes/wow-wishlist-contents.php');
	 }

	 /**
	  * wow_wishlist_button function.
	  *
	  * @access public
	  * @return void
	  */
	 public function wow_wishlist_button(){

	 	$this->wow_wishlist_settings=get_option('wow_wishlist_settings');
		if($this->wow_wishlist_settings['wow_wishlist_enabling']!='1')
			return;

			global $product,$wpdb;
			$this->wow_wishlist_settings=get_option('wow_wishlist_settings');
			$logged_in=$this->wow_wishlist_settings['wow_wishlist_for'];
			$show_in_shop=$this->wow_wishlist_settings['wow_wishlist_show_in_shop_page'];

			if($this->wow_wishlist_settings['wow_wishlist_for']=='logged_in_users'){
			  if(!is_user_logged_in())
			  return;
			}

			if($show_in_shop!=1 && (is_shop() || is_tax("product_cat"))){
			  return;
			}

			$product_added=array();
			if(!is_user_logged_in()){
				if(empty($_SESSION['wow_wihslist_items'])):
			  	else:
			  		$product_added=$_SESSION['wow_wihslist_items'];
			  	endif;
			  }else{

			  	$products=$wpdb->get_results ( "select product_id from " . $wpdb->prefix . "wow_wishlist  where user_id=".get_current_user_id());
				$i=0;
				foreach($products as $product_new){
					$product_added[$i]=$product_new->product_id;
					$i++;
				}
			  }
			  //content: "\f487"; icon heart

			 if(!in_array($product->id,$product_added)):
				 if($this->wow_wishlist_settings['wow_wishlist_for'] == 'button'){ ?>

					  <a href="javascript:void(0)" rel="nofollow" data-product_id="<?php echo $product->id; ?>"
                         id="wow_wishlist_add_<?php echo $product->id; ?>"  class="button add_to_wishlist_button">
						 <?php printf(esc_html('%s',$this->plugin_name),$this->wow_wishlist_settings['wow_wishlist_add_to_wishlist_text']); ?>

						<span class="wow_wishlist_loader" id="wow_wishlist_add_<?php echo $product->id; ?>"
                        style="display:none;background: url(<?php echo plugin_dir_url( __FILE__ ); ?>images/ajax-loader.gif) no-repeat; 						height:11px; width:16px"></span></a>
						<span id="wow_wishlist_added" class="wow_wishlist_added_<?php echo $product->id; ?>" style="display:none;">
						<?php echo __('Product Added!',$this->plugin_name); ?></span>

				<?php }else{ ?>

					<a href="javascript:void(0)" rel="nofollow" data-product_id="<?php echo $product->id; ?>"
                         id="wow_wishlist_add_<?php echo $product->id; ?>"  class="add_to_wishlist_button"><span class="dashicons dashicons-heart"></span>
	                <?php printf(esc_html('%s',$this->plugin_name),$this->wow_wishlist_settings['wow_wishlist_add_to_wishlist_text']); ?>
	                <span class="wow_wishlist_loader" id="wow_wishlist_add_<?php echo $product->id; ?>" style="display:none;background: url(<?php echo plugin_dir_url( __FILE__ ); ?>images/ajax-loader.gif) no-repeat; 						height:11px; width:16px"></span></a>
						<span id="wow_wishlist_added" class="wow_wishlist_added_<?php echo $product->id; ?>" style="display:none;">
						<?php echo __('Product Added!',$this->plugin_name); ?></span>

				<?php } ?>

			<?php else: ?>

				 <a href="<?php echo get_permalink(esc_attr($this->wow_wishlist_settings['wow_wishlist_page'])); ?>"
                 rel="nofollow"   class="button view_wishlist_button">
				 <?php printf(esc_html('%s',$this->plugin_name),$this->wow_wishlist_settings['wow_wishlist_view_wishlist_text']); ?>
				 </a>

			<?php endif;
		}


	/**
	 * add product to wishlist for logged in user
	 * @param    string   $product_id   The id for the product
	 * @since    1.0.0
	 */
	public function wow_wishlist_add_product_to_wishlist_logged_in_user($product_id){

	    $this->wow_wishlist_settings=get_option('wow_wishlist_settings');
	    if($this->wow_wishlist_settings['wow_wishlist_enabling']!='1')
		return;

		global $wpdb;
	    $duplicate=$wpdb->get_results ( "select * from " . $wpdb->prefix . "wow_wishlist  where product_id=".$product_id);
		  if(!$duplicate)
		  {

		         $insert=$wpdb->query("insert  into " . $wpdb->prefix . "wow_wishlist(user_id,product_id,wishlist_created)
				 values('".get_current_user_id( )."','".$product_id."','".date("Y-m-d")."')");

						if($insert) echo '[{"response":"success"}]'; exit;
			 }

	}

	/**
	 * session data to table if user logged in after adding products to wishlist
	 *
	 * @since    1.0.0
	 */
	public	function wow_wishlist_data_to_table_logged_user(){
		$this->wow_wishlist_settings=get_option('wow_wishlist_settings');
	    if($this->wow_wishlist_settings['wow_wishlist_enabling']!='1')
		return;

			if(is_user_logged_in() && $_SESSION['wow_wihslist_items'])
			{
			  global $wpdb;
			  $products=$_SESSION['wow_wihslist_items'];
			    foreach($products as $prod)
				{
			        $duplicate=$wpdb->get_results ( "select * from " . $wpdb->prefix . "wow_wishlist  where product_id=".$prod);
			             if(!$duplicate)
						 {
			              $insert=$wpdb->query("insert  into " . $wpdb->prefix . "wow_wishlist(user_id,product_id,wishlist_created)
			              values('".get_current_user_id( )."','".$prod."','".date("Y-m-d")."')");
						 }
				}
				unset($_SESSION['wow_wihslist_items']);
			}
	}


	/**
	 * add product to wishlist for non logged in user
	 * @param    string   $product_id   The id for the product
	 * @since    1.0.0
	 */

	public	function wow_wishlist_add_product_to_wishlist_guest_user($product_id){

				$name = "wow_wihslist_items";
				empty($_SESSION[$name])? $value=array() : $value=$_SESSION[$name];
				$product=array(); $new_value=array();
				$product[]=$product_id;
				$new_value=array_merge($value,$product);
				$_SESSION[$name]=$new_value;

					 if($_SESSION[$name]) echo '[{"response":"success"}]';  exit;

	}

	/**
	 * remove product from wishlist for normal user
	 * @param    string   $product_id   The id for the product
	 * @since    1.0.0
	 */
	public function wow_wishlist_remove_product_from_wishlist_normal_user($product_id){
			  $arr = $_SESSION['wow_wihslist_items'];
			  $arr_new = array_diff($arr, array($product_id));
        	  $_SESSION['wow_wihslist_items']=$arr_new;
			  echo '[{"response":"removed"}]';
              exit;
	}

	/**
	 * wow_wishlist_remove_product_from_wishlist_logged_user function.
	 *
	 * @access public
	 * @param mixed $product_id
	 * @return void
	 */
	public	function wow_wishlist_remove_product_from_wishlist_logged_user($product_id){
		global $wpdb;
		$remove=$wpdb->query("DELETE FROM ".$wpdb->prefix."wow_wishlist WHERE product_id=".$product_id );
			if($remove) echo '[{"response":"removed-'.$product_id.'"}]';
			exit;
	}
	/**
	 * wow_wishlist_action function.
	 *
	 * @access public
	 * @return void
	 */
	public function wow_wishlist_action(){

		$product_id=$_POST['product_id'];
		$action=$_POST['action_id'];
			if($action=='add' && is_user_logged_in()){
				$this->wow_wishlist_add_product_to_wishlist_logged_in_user($product_id);
			}else if($action=='add' && !is_user_logged_in()){
				$this->wow_wishlist_add_product_to_wishlist_guest_user($product_id);
			}else if($action=='remove' && is_user_logged_in()){
				$this->wow_wishlist_remove_product_from_wishlist_logged_user($product_id);
			}else if($action=='remove' && !is_user_logged_in()){
				$this->wow_wishlist_remove_product_from_wishlist_normal_user($product_id);
			}
	 }


	/**
	 * wow_wishlist_ajax_script function.
	 *
	 * @access public
	 * @return void
	 */
	public function wow_wishlist_ajax_script(){
		include_once('includes/wow-wishlist-ajax.php');
	}


	/**
	 * enqueue_styles function.
	 *
	 * @access public
	 * @return void
	 */
	public function enqueue_styles() {
		wp_enqueue_style( $this->plugin_name . '-custom-css', plugin_dir_url( __FILE__ ) . 'css/' . $this->plugin_name . '-style.css', array(), $this->version, 'all' );
	}

	/**
	 * enqueue_scripts function.
	 *
	 * @access public
	 * @return void
	 */
	public function enqueue_scripts() {
		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/' . $this->plugin_name . '-main.js', array( 'jquery' ), $this->version, false );
	}

	/**
	 * function to add on init action
	 *
	 * @since    1.0.0
	 */
	public function wow_ajax_cart_public_init(){
		$this->wow_wishlist_register_session();
		$this->wow_wishlist_data_to_table_logged_user();
	}

}