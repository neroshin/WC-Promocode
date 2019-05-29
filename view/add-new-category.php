<?php

	global  $wpdb;
	$get_coupons = get_option( 'coupon-category' );
	$add_meta_nonce = wp_create_nonce( 'acc_add_category_meta_form_nonce' ); 
	$columns = array(
            'id'      => 'ID',
			'name'    => 'Name',
            'description'=> 'Description'
        );
	$ListTable = new List_Table();
	
	
	if(!empty($get_coupons)){
		
	}
	else{
		$get_coupons = array();
	}
		$ListTable->set_table_data($get_coupons);
	
	
	$ListTable->prepare_items(7 , $columns); 
	
	 
	//print_r($get_coupons );
	
	?>
	<div class="wrap">
<h1 id="add-new-user">Promocode categories</h1>
	
	
<div id="col-container" class="wp-clearfix">

			<div id="col-left">
				<div class="col-wrap">
				<form action="<?php echo esc_url( admin_url('admin-post.php') ); ?>" method="post"  id="createuser" class="validate">
				<input type="hidden" name="action" value="add_category_form">
				<input type="hidden" name="acc_add_category_meta_nonce" value="<?php echo $add_meta_nonce ?>" />
				
				
				
				
				<div class="form-field form-required term-name-wrap">
					<label for="tag-name">Name</label>
					<input name="tag-name" id="tag-name" type="text" value="" size="40" aria-required="true">
					<p>The name is how it appears on your site.</p>
				</div>
					
					
					<div class="form-field term-description-wrap">
					<label for="tag-description">Description</label>
					<textarea name="description" id="tag-description" rows="5" cols="40"></textarea>
					<p>The description is not prominent by default; however, some themes may show it.</p>
				</div>

					<p class="submit"><input type="submit" name="submit" id="submit" class="button button-primary" value="Add New Category"></p>
				</form>
				
						
				</div >
			</div >
			
			<div id="col-right">
				<div class="col-wrap">
					<?php
					echo $ListTable->display();
					?>
				</div >
			</div >
			
		</div >
	</div>