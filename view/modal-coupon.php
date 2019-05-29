
	<div id="coupon_modal"><i class="fa fa-scissors" aria-hidden="true"></i></div>
    <div id="popup_box" style="display:none;">
			
		<div class="couponContainer">
		  <div class="ticketHolder noFloat"> 
		
			<div class="coupon">
				<form action="" method="POST" id="promocode_form">
				<span id="close_coupon">
					X
				</span>
			  <div class="inner">
			   <div>Promo Code:</div>
				<div class="savings"><input name="promocode_apply" type="text" id="promocode_apply" value="<?=$fetch_row[0]->code?>"></div>
			
			  </div>
			  </form>
			  	<button class="button_modal" type="submit" form="promocode_form" value="Submit">
				Apply
			</button>
			</div>
		
			<div class="clear"></div>
			<div id="open-modal" class="modal-window">
			<div>

				<h1>Promocode Invalid!</h1>
			
			</div>
			</div>
		  </div> 
		  <div class="clear"></div>
		</div>
	</div>
	
	
	
