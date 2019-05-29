<?php 


global $coupon_apply_db_version;
$coupon_apply_db_version = '1.0';

function coupon_apply_install() {
	echo "sample";
	 
	global $wpdb;
	//global $coupon_apply_db_version;

	$table_name = $wpdb->prefix . 'coupon_apply';
	
	$charset_collate = $wpdb->get_charset_collate();

	$sql = "CREATE TABLE `{$table_name}` (
		id mediumint(9) NOT NULL AUTO_INCREMENT,
		date datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
		exp_date datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
		first_name tinytext NOT NULL,
		last_name text NOT NULL,
		email varchar(55) DEFAULT '' NOT NULL,
		code varchar(55) DEFAULT '' NOT NULL,
		PRIMARY KEY  (id)
	) $charset_collate;";

	require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
	dbDelta( $sql );

	//add_option( 'coupon_apply_db_version', $coupon_apply_db_version ); 
}


register_activation_hook( __FILE__, 'coupon_apply_install' );

?>