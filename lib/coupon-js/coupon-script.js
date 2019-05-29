
jQuery(document).ready(function(){
	jQuery("input#datetime").datepicker({
		dateFormat : 'yy-mm-dd'
	});
	jQuery("span#close_coupon").click(function(){
		jQuery(this).closest("div#popup_box").fadeOut();
		
	})
	jQuery("span#close_coupon").click(function(){
		jQuery(this).closest("div#popup_box").fadeOut();
		
	})
})
