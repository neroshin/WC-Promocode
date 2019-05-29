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
/**
 * Filter the cart template path to use our cart.php template instead of the theme's
 */
function wc_change_template_relate( $template, $template_name, $template_path ) {
	 $basename = basename( $template );
	//echo  $basename;
	  if( $basename == 'related.php' ) {
			$template =  COUP_PLUGIN_PATH.'/woocommerce/single-product/related.php';
	 } 
 return $template;
}
add_filter( 'woocommerce_locate_template', 'wc_change_template_relate', 10, 3 );

// Get Related Products from SAME Sub-category
/* add_filter( 'woocommerce_product_related_posts', 'my_custom_related_products' );
function custom_related_products($product){
    global $woocommerce;
    // Related products are found from category and tag
    $tags_array = array(0);
    $cats_array = array(0);
    // Get tags
    $terms = wp_get_post_terms($product->id, 'product_tag');
    foreach ( $terms as $term ) $tags_array[] = $term->term_id;
    // Get categories
    $terms = wp_get_post_terms($product->id, 'product_cat');
    foreach ( $terms as $key => $term ){
        $check_for_children = get_categories(array('parent' => $term->term_id, 'taxonomy' => 'product_cat'));
        if(empty($check_for_children)){
            $cats_array[] = $term->term_id;
        }
    }
    // Don't bother if none are set
    if ( sizeof($cats_array)==1 && sizeof($tags_array)==1 ) return array();
    // Meta query
    $meta_query = array();
    $meta_query[] = $woocommerce->query->visibility_meta_query();
    $meta_query[] = $woocommerce->query->stock_status_meta_query();
    $meta_query   = array_filter( $meta_query );
    // Get the posts
    $related_posts = get_posts( array(
            'orderby'        => 'rand',
            'posts_per_page' => $limit,
            'post_type'      => 'product',
            'fields'         => 'ids',
            'meta_query'     => $meta_query,
            'tax_query'      => array(
                'relation'      => 'OR',
                array(
                    'taxonomy'     => 'product_cat',
                    'field'        => 'id',
                    'terms'        => $cats_array
                ),
                array(
                    'taxonomy'     => 'product_tag',
                    'field'        => 'id',
                    'terms'        => $tags_array
                )
            )
        ) );
    $related_posts = array_diff( $related_posts, array( $product->id ), $product->get_upsells() );
    return $related_posts;
} */
 
         

?>