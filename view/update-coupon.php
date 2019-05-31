<?php 

		$id = $_GET["id"];

		// print_r($coupons[0]);
		
		$add_meta_nonce = wp_create_nonce( 'acc_update_promocode_meta_form_nonce' ); 
		
		
		$prmocode_data = couponDatabase::db_fetch_coupon_data($id);
?>

<div class="wrap">
<h1 >
Update Promocode</h1>


<div id="ajax-response"></div>

<form action="<?php echo esc_url( admin_url('admin-post.php') ); ?>" method="post"  id="createuser" class="validate">
<input type="hidden" name="action" value="update_coupon_form">
<input type="hidden" name="acc_update_promocode_meta_nonce" value="<?php echo $add_meta_nonce ?>" />	
	<table class="form-table">
	<tbody>
	
	<tr class="form-field form-required">
		<th scope="row"><label for="email">Email <span class="description">(required)</span></label></th>
		<td><input name="email" type="email" id="email" value="<?=$prmocode_data[0]->email?>"></td>
	</tr>
		<tr class="form-field">
		<th scope="row"><label for="first_name">First Name </label></th>
		<td><input name="first_name" type="text" id="first_name" value="<?=$prmocode_data[0]->first_name?>"></td>
	</tr>
	<tr class="form-field">
		<th scope="row"><label for="last_name">Last Name </label></th>
		<td><input name="last_name" type="text" id="last_name" value="<?=$prmocode_data[0]->last_name?>"></td>
	</tr>
	<tr class="form-field">
		<th scope="row"><label for="promo_category">Promocode Category</label></th>
		<td> 
			<select name="promo_category" id="promo_categories">
				<?=coupon::array_to_optionitem(get_option( 'coupon-category' ),$prmocode_data[0]->promo_category );?>
			</select>
			
		</td>
	</tr>

	<tr class="form-field">
		<th scope="row"><label for="date_exp">Date Expiration</label></th>
		<td> <input autocomplete="off" type="datetime" id='datetime' name="date_exp" value="<?=date('Y-m-d' , strtotime($prmocode_data[0]->exp_date))?>"/></td>
	</tr>
	<tr class="form-field">
		<td> <input type="hidden" id='id' name="id" value="<?=$id ?>"/></td>
	</tr>
	
	

	</tbody></table>

	
	<p class="submit"><input type="submit" name="submit" id="" class="button button-primary" value="Update Promocode"></p>
</form>
</div>