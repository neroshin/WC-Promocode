<?php
/**
 * Class to handle coupon operations
 * Changes by Alex Rabinovich (@putchi)
 * 
 * @author Joash Pereira
 * @date  2015-06-05
 */
class couponDatabase {
	
	function __construct( ) {
		
		add_action( 'admin_post_add_coupon_form', array($this ,'func_add_coupon_form') );
		add_action( 'admin_post_add_category_form', array($this ,'func_add_category_form') );
		add_filter( 'check_coupon', array($this ,'func_check_coupon'), 10, 1 );
		add_filter( 'db_fetch_coupon', array($this ,'func_db_fetch_coupon'), 10, 1 );
		
		
		add_action( 'wp_ajax_nopriv_wpajax_check_coupon', array($this ,'wpajax_check_coupon') );
		add_action( 'wp_ajax_wpajax_check_coupon', array($this ,'wpajax_check_coupon') );
		
		add_action( 'admin_post_promo_delete', array($this ,'func_promo_delete') );
		
	}
	
	/* Wp ajax check coupon is valid  */
	function func_promo_delete(){
		
		global $wpdb;
		$id = $_GET['id'];
		$prefix = $wpdb->prefix . 'coupon_apply';
		
			
		$myrows = $wpdb->get_results( "DELETE FROM `$prefix` WHERE `vns_coupon_apply`.`id` = $id" );
		// print_r($myrows);
		
		wp_redirect( get_home_url()."/wp-admin/admin.php?page=coupon-main-menu");
		die();
	}
	function wpajax_check_coupon() {
		header('Content-Type: application/json');
		global $wpdb;
		$prefix = $wpdb->prefix . 'coupon_apply';
		if(isset($_POST['promocode']))
			$promocode = $_POST['promocode'];
			$myrows = $wpdb->get_results( "SELECT COUNT(*) as count_row FROM `$prefix` WHERE  NOW() <= `exp_date` and `code` = '$promocode'" );
			
			echo json_encode($myrows);
			die();
	}
	
	/* Add action filter checking coupon  */
	
	function func_check_coupon( $arg ) {
		global $wpdb;
		$prefix = $wpdb->prefix . 'coupon_apply';
		$myrows = $wpdb->get_results( "SELECT COUNT(*) as count_row FROM `$prefix` WHERE  NOW() <= `exp_date` and `code` = '$arg'" );
		
	
		return  $myrows ;
	}
	
	/* Add action filter checking coupon  */
	
	function func_db_fetch_coupon( $arg ) {
		global $wpdb;
		$prefix = $wpdb->prefix . 'coupon_apply';
		$myrows = $wpdb->get_results( "SELECT * FROM `$prefix` WHERE  NOW() <= `exp_date` and `code` = '$arg'" );
		
	
		return  $myrows ;
	}
	
	/* Fetch data of coupon in database for list display */
	static public function  db_getlist(){
		
		global $wpdb;
		$prefix = $wpdb->prefix . 'coupon_apply';
		$myrows = $wpdb->get_results( "SELECT * FROM $prefix" );
		
		return $myrows ;
	}
	
		
	/* Add new category  */
	public function func_add_category_form() {
		
		
		if(isset($_POST['submit']) && 
		isset( $_POST['acc_add_category_meta_nonce'] )&& 
		wp_verify_nonce( $_POST['acc_add_category_meta_nonce'], 'acc_add_category_meta_form_nonce') ) {
		 if(!get_option('coupon-category')){
			  $coupon_category = array();
			  $item_number = 0;
		 }
		 else{ 
			  $coupon_category = get_option( 'coupon-category' );
			  $item_number = ($coupon_category[end(array_keys(get_option('coupon-category')))]["id"] + 1);
		 }
		
			
		 $get_form = array( "id" => $item_number , "name" =>  $_POST['tag-name'] , "description" =>  $_POST['description'] );
		
		array_push($coupon_category,  $get_form);
		
		
		update_option( 'coupon-category', $coupon_category );  
		
		 /* echo "<pre>";
		print_r(get_option( 'coupon-category' )); 
		 echo "</pre>";  */
		//delete_option( 'coupon-category');
		
		}
		wp_redirect( get_home_url()."/wp-admin/admin.php?page=add-new-category");
	}
	
	/* Add new coupon  */
	public function func_add_coupon_form() {
		
		global $wpdb;
		
		if(isset($_POST['submit']) && 
		isset( $_POST['acc_add_user_meta_nonce'] )&& 
		wp_verify_nonce( $_POST['acc_add_user_meta_nonce'], 'acc_add_user_meta_form_nonce') ) {
			$date = date('Y-m-d H:i:s');
			$time = date('H:i:s');
			$exp_date = date('Y-m-d H:i:s' , strtotime($_POST['date_exp']." ".$time) );
			
			
			
			$table=$wpdb->prefix.'coupon_apply';
			$hall= $wpdb->insert($table, 
				array(
				  'first_name'          => $_POST['first_name'],
				  'last_name'       => $_POST['last_name'],
				  'date'          => $date,
				  'exp_date'          => $exp_date,
				  'email'       => $_POST['email'],
				  'promo_category'       => $_POST['promo_category'],
				  'code'       => $_POST['code']
				),
				array(
				  '%s',
				  '%s',
				  '%s',
				  '%s',
				  '%s',
				  '%s',
				  '%s'
				) 
			); 

		
		}
	
		/*  */
		wp_redirect( get_home_url()."/wp-admin/admin.php?page=coupon-main-menu");
		exit;

	}
}
new couponDatabase();