<?php 
/*
Plugin Name: Coupon apply
Description: This plugin is main apply promocode of woocommere.
Version: 0.0.1
Author: Neroshin
*/
define("COUP_PLUGIN_PATH", plugin_dir_path( __FILE__ ) );
define("COUP_PLUGIN_NAME", plugin_basename( __FILE__ ));

require_once(COUP_PLUGIN_PATH. "/class/class-coupon.php");
require_once(COUP_PLUGIN_PATH. "/class/class-coupon-database.php");

global $coupon_apply_db_version;
$coupon_apply_db_version = '1.0';


if( ! class_exists( 'WP_List_Table' ) ) {
		require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
		require  plugin_dir_path( __FILE__ ).'/class/class-list-table.php';
	}


add_action( 'admin_menu', 'coupon_setup_menu' );


function wpdocs_admin_scripts() {
	
	wp_enqueue_script('jquery-ui-datepicker');
	wp_enqueue_style('jquery-ui-css', 'http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.2/themes/smoothness/jquery-ui.css');
	 wp_enqueue_script( 'script-coupon-script', plugin_dir_url( __FILE__ ) . "/lib/coupon-js/coupon-script.js" , array(), '1.0.0', true );
  
}

add_action( 'admin_enqueue_scripts', 'wpdocs_admin_scripts' );

function wpdocs_frontend_scripts() {
	wp_enqueue_style('coupon_style',  plugins_url( '/lib/style.css' , __FILE__ ) , array(), '0.1.0', 'all');
	wp_enqueue_script( 'frontend-coupon-script', plugin_dir_url( __FILE__ ) . "/lib/coupon-js/frontend-coupon-script.js" , array(), '1.0.0', true );
	wp_localize_script( 'frontend-coupon-script', 'ajax_coupon_script', array(
		'ajax_url' => admin_url( 'admin-ajax.php' )
	));
}

add_action( 'wp_enqueue_scripts', 'wpdocs_frontend_scripts' );

/* Main menu */
function coupon_setup_menu() {
	add_menu_page(  'Coupon List',  'Coupon List', 'manage_options', 'coupon-main-menu','couponListPage' , '' , 26 );
	add_submenu_page('coupon-main-menu', 'Add new','Add new', 'manage_options','add-new-coupon', 'add_new_coupon');
	add_submenu_page('coupon-main-menu', 'Add category','Add category', 'manage_options','add-new-category', 'add_new_category');
	
	add_submenu_page(null, 'Update promocode','Update promocode', 'manage_options','update-the-prmocode', 'update_the_prmocode');
}

function update_the_prmocode(){
	// require  ;
	
		ob_start();
		
		include(plugin_dir_path(__FILE__)."/view/update-coupon.php");
		
		$output = ob_get_clean();
		
		view($output);
	
}
function add_new_category(){
	// require  ;
	
		ob_start();
		
		include(plugin_dir_path( __FILE__ ).'/view/add-new-category.php');
		
		$output = ob_get_clean();
		
		view($output);
	
}
function couponListPage(){
	
	// require  ;
	
		ob_start();
		
		include(plugin_dir_path( __FILE__ ).'/view/coupon-list.php');
		
		$output = ob_get_clean();
		
		view($output);
	
}
function add_new_coupon(){
	
		
		ob_start();
		
		
		
		include(plugin_dir_path(__FILE__)."/view/add-coupon.php");
		
		$output = ob_get_clean();
		
		view($output);
}

function func_modal_coupon() {
	$fetch_row = apply_filters("db_fetch_coupon" ,$_SESSION['promocode']);
	
	ob_start();
	
	include(plugin_dir_path(__FILE__)."/view/modal-coupon.php");
	
	$output = ob_get_clean();
	if ( is_user_logged_in() ) view($output);
	
    // echo "<script>alert('".$_SESSION['promocode']."')</script>";
}
add_action( 'wp_footer', 'func_modal_coupon', 100 );



function view($param){
	print $param;
}



function coupon_apply_install() {

	 
	global $wpdb;
	//global $coupon_apply_db_version;

	$table_name = $wpdb->prefix . 'coupon_apply';
	
	$charset_collate = $wpdb->get_charset_collate();

	$sql = "CREATE TABLE `{$table_name}` (
		id mediumint(9) NOT NULL AUTO_INCREMENT,
		date datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
		exp_date datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
		first_name tinytext NOT NULL,
		promo_category int NOT NULL,
		last_name text NOT NULL,
		email varchar(55) DEFAULT '' NOT NULL,
		code varchar(55) DEFAULT '' NOT NULL,
		PRIMARY KEY  (id)
	) $charset_collate;";

	require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
	dbDelta( $sql );

	add_option( 'coupon_apply_db_version', $coupon_apply_db_version ); 
}

register_activation_hook( __FILE__, 'coupon_apply_install' );




?>