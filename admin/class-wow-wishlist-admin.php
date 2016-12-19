<?php


class wow_wishlist_Admin {

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
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register submenu for the plugin
	 *
	 * @since    1.0.0
	 */

	public function  wow_wishlist_menu(){

			add_menu_page('WOW Wishlists', 'WOW Wishlists', 'manage_options', 'wow_wishlist', array($this, 'wow_wishlist_settings_form'),'dashicons-heart',30);
			//plugins_url('/img/icon.png',__DIR__));

			//$settings=add_submenu_page('woocommerce', 'WOW Wishlist Settings', 'WOW Wishlist Settings', 'manage_options', 'wow_wishlist',
			//array($this, 'wow_wishlist_settings_form'));
			add_action( "load-{$settings}", array($this,'wow_wishlist_settings_page') );

   }

 	/**
	 * Wishlist setting form for admin
	 *
	 * @since    1.0.0
	 */

   	public function wow_wishlist_settings_form(){
		include_once('includes/wow-wishlist-settings-form.php');
	}


   	/**
	 * the default values of wishlist settings page
	 *
	 * @since    1.0.0
	 */
	public function wow_wishlist_setting_default(  ) {

	if( !get_option( 'wow_wishlist_settings' ) ) :
		$this->wow_wishlist_settings=array();
		$this->wow_wishlist_settings['wow_wishlist_enabling']				='1';
		$this->wow_wishlist_settings['wow_wishlist_page']					='';
		$this->wow_wishlist_settings['wow_wishlist_enable_image']			='1';
		$this->wow_wishlist_settings['wow_wishlist_button_position']		='after-add-to-cart';
		$this->wow_wishlist_settings['wow_wishlist_enable_add_to_cart']		='1';
		$this->wow_wishlist_settings['wow_wishlist_enable_unit_price']		='1';
		$this->wow_wishlist_settings['wow_wishlist_enable_stock']			='1';
		$this->wow_wishlist_settings['wow_wishlist_add_to_wishlist_text']	= __('Add to Wishlist', 'wow-wishlist');
		$this->wow_wishlist_settings['wow_wishlist_view_wishlist_text']		= __('Browse Wishlist','wow-wishlist');
		$this->wow_wishlist_settings['wow_wishlist_for']					='all_users';
		$this->wow_wishlist_settings['wow_wishlist_show_in_shop_page']		='0';

		$this->wow_wishlist_settings['wow_wishlist_show_as_btn_or_icon']	='1';

		add_option( 'wow_wishlist_settings',$this->wow_wishlist_settings,'','yes');
	    endif;
	}




	/**
	 * Create table for wishlists
	 *
	 * @since    1.0.0
	 */
	public function wow_wishlist_create_table(){

		global $wpdb;
		$charset_collate = $wpdb->get_charset_collate();
		$table_name= $wpdb->prefix."wow_wishlist";

		$sql = "CREATE TABLE $table_name (
 		 id mediumint(9) NOT NULL AUTO_INCREMENT,
 		 user_id mediumint(9) NOT NULL ,
 		 product_id mediumint(9) NOT NULL,
		 wishlist_created datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
		  UNIQUE KEY id (id)
			) $charset_collate;";

		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		dbDelta( $sql );

	}

	/**
	 * Create table for wishlists
	 *
	 * @since    1.0.0
	 */
	public	function wow_wishlist_settings_page() {
			if ( $_POST["wow_wishlist_submit"]) :
			check_admin_referer( "wow_wishlist_page" );
			$this->wow_wishlist_save_settings();
			$param = isset($_GET['tab'])? 'updated=true&tab='.$_GET['tab'] : 'updated=true';
			wp_redirect(admin_url('admin.php?page=wow_wishlist&'.$param));
			exit;
			endif;
	}

	/**
	 * Save setting for wishlist admin
	 *
	 * @since    1.0.0
	 */
	public function wow_wishlist_save_settings(){

			$this->wow_wishlist_settings=array();
			if ( isset ( $_GET['page'] )=='wow_wishlist'):
				$this->wow_wishlist_settings['wow_wishlist_enabling']				=$_POST['wow_wishlist_enabling'];
				$this->wow_wishlist_settings['wow_wishlist_page']					=$_POST['wow_wishlist_page'];
				$this->wow_wishlist_settings['wow_wishlist_button_position']		=$_POST['wow_wishlist_button_position'];
				$this->wow_wishlist_settings['wow_wishlist_enable_image']			=$_POST['wow_wishlist_enable_image'];
				$this->wow_wishlist_settings['wow_wishlist_enable_add_to_cart']		=$_POST['wow_wishlist_enable_add_to_cart'];
				$this->wow_wishlist_settings['wow_wishlist_enable_unit_price']		=$_POST['wow_wishlist_enable_unit_price'];
				$this->wow_wishlist_settings['wow_wishlist_enable_stock']			=$_POST['wow_wishlist_enable_stock'];
				$this->wow_wishlist_settings['wow_wishlist_add_to_wishlist_text']	=$_POST['wow_wishlist_add_to_wishlist_text'];;
				$this->wow_wishlist_settings['wow_wishlist_view_wishlist_text']		=$_POST['wow_wishlist_view_wishlist_text'];;
				$this->wow_wishlist_settings['wow_wishlist_for']					=$_POST['wow_wishlist_for'];
				$this->wow_wishlist_settings['wow_wishlist_show_in_shop_page']		=$_POST['wow_wishlist_show_in_shop_page'];

				$this->wow_wishlist_settings['wow_wishlist_show_as_btn_or_icon']	=$_POST['wow_wishlist_show_as_btn_or_icon'];

			   //update option
			   update_option( "wow_wishlist_settings", 	$this->wow_wishlist_settings );

			endif;
		}

	/**
	 * nc wishlist functionate initiate
	 *
	 * @since    1.0.0
	 */
	public function wow_wishlist_admin_init(){

		$this->wow_wishlist_setting_default();
		$this->wow_wishlist_create_table();
		$this->wow_wishlist_settings_page();

		}

	/**
	 * nc wishlist functionate initiate
	 *
	 * @since    1.0.0
	 */

	public function wow_wishlist_admin_pages_display()
	{
	   $args = array
	       (
	      'posts_per_page'   => -1,
	      'offset'           => 0,
	      'category'         => '',
		     'category_name'    => '',
		     'orderby'          => 'date',
		     'order'            => 'DESC',
		     'include'          => '',
		     'exclude'          => '',
		     'meta_key'         => '',
		     'meta_value'       => '',
		     'post_type'        => 'page',
		     'post_mime_type'   => '',
		     'post_parent'      => '',
		     'author'	   => '',
		     'post_status'      => 'publish',
		     'suppress_filters' => true
			);
			$posts_array = get_posts( $args );


		return  $posts_array;

	}


	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Plugin_Name_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Plugin_Name_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/wow-whishlist-for-woocommerce-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Plugin_Name_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Plugin_Name_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		 wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/wow-wishlist-for-woocommerce-admin.js', array( 'jquery' ), $this->version, false );

	}

}
