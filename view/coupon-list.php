<?php

	global  $wpdb;
	
	$get_coupons = coupon::objToArray(couponDatabase::db_getlist());
	$columns = array(
            'id'      => 'ID',
			'date'    => 'Date Created',
            'exp_date'=> 'Expiration Date',
			'code'    => 'Coupon Code',	
			'promo_category'    => 'Promo Category',	
            'email'   => 'Email Address',
            'action'   => 'Action',
        );
	$ListTable = new List_Table();
	$ListTable->set_table_data($get_coupons);
	$ListTable->prepare_items(null,$columns); 
	?>
	<div class="wrap">
<h1 id="add-new-user">Promocode list</h1>
	
	<?php
	echo $ListTable->display();
	?>
	
	</div>