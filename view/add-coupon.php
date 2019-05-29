<?php 


$no_of_coupons = 1;
		$length = 6;
		$prefix = "prefix-";
		$suffix = "-suffix";
		$numbers = true;
		$letters = true;
		$symbols = false;
		$random_register = false;
		$mask = "XXX-XXX";
		$coupons = coupon::generate_coupons($no_of_coupons, $length, $prefix, $suffix, $numbers, $letters, $symbols, $random_register, $mask);

		// print_r($coupons[0]);
		
		$add_meta_nonce = wp_create_nonce( 'acc_add_user_meta_form_nonce' ); 
?>

<div class="wrap">
<h1 id="add-new-user">
Add New Promocode</h1>


<div id="ajax-response"></div>

<form action="<?php echo esc_url( admin_url('admin-post.php') ); ?>" method="post"  id="createuser" class="validate">
<input type="hidden" name="action" value="add_coupon_form">
<input type="hidden" name="acc_add_user_meta_nonce" value="<?php echo $add_meta_nonce ?>" />	
	<table class="form-table">
	<tbody><tr class="form-field form-required">
		<th scope="row"><label for="user_login">Username <span class="description">(required)</span></label></th>
		<td><input name="user_login" type="text" id="user_login" value="" aria-required="true" autocapitalize="none" autocorrect="off" maxlength="60"></td>
	</tr>
	<tr class="form-field form-required">
		<th scope="row"><label for="email">Email <span class="description">(required)</span></label></th>
		<td><input name="email" type="email" id="email" value=""></td>
	</tr>
		<tr class="form-field">
		<th scope="row"><label for="first_name">First Name </label></th>
		<td><input name="first_name" type="text" id="first_name" value=""></td>
	</tr>
	<tr class="form-field">
		<th scope="row"><label for="last_name">Last Name </label></th>
		<td><input name="last_name" type="text" id="last_name" value=""></td>
	</tr>
	<tr class="form-field">
		<th scope="row"><label for="date_exp">Promocode Category</label></th>
		<td> 
			<select name="promo_category" id="promo_categories">
				<?=coupon::array_to_optionitem(get_option( 'coupon-category' ));?>
			</select>
			
		</td>
	</tr>

	<tr class="form-field">
		<th scope="row"><label for="date_exp">Date Expiration</label></th>
		<td> <input autocomplete="off" type="datetime" id='datetime' name="date_exp"/></td>
	</tr>
	<tr class="form-field">
		<th scope="row"><label for="url">Promo Code</label></th>
		<td><input name="code" type="text" id="code" class="code" value="<?=$coupons[0]?>" readonly="readonly"></td>
	</tr>
	
	

	</tbody></table>

	
	<p class="submit"><input type="submit" name="submit" id="" class="button button-primary" value="Add New Promocode"></p>
</form>
</div>