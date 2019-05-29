
jQuery(document).ready(function(){
	jQuery("div#popup_box").hide();
	jQuery("span#close_coupon").click(function(){
		jQuery(this).closest("div#popup_box").fadeOut();
		
	})
	jQuery("div#coupon_modal i.fa.fa-scissors").click(function(){
		jQuery("div#popup_box").fadeIn();
		
	})
	
	jQuery("button.button_modal").click(function(e){
		e.preventDefault();
		var input_promocode = jQuery("input#promocode_apply").val();
				jQuery("button.button_modal").text("Waiting...");
			jQuery.ajax({
				url : ajax_coupon_script.ajax_url,
				type : 'post',
				data : {
					action : 'wpajax_check_coupon',
					promocode : input_promocode
				},
				success : function( response ) {
					console.log(response);
					 if(parseInt(response[0]["count_row"]) !== 0  ){
					   jQuery( "form#promocode_form" ).submit();
					   jQuery("button.button_modal").text("Redirect");
					 }
					 else{
						 jQuery( "div#open-modal" ).removeClass("hide_animate_popup");
						jQuery( "div#open-modal" ).addClass("animate_popup");
							   jQuery("button.button_modal").text("Apply");
						setTimeout(function() {
							
							jQuery( "div#open-modal" ).removeClass("animate_popup");
							jQuery("div#open-modal").addClass("hide_animate_popup");
						},3000);
					 }
				},
				error: function(errorThrown){
					
				}
		});
		
	})
	
	
	var promocode_applyval = jQuery("input#promocode_apply").val();
	if(promocode_applyval === ""){
		jQuery("div#coupon_modal").addClass("animatepulse");
	}
	else{
		jQuery("div#coupon_modal").removeClass("animatepulse");
	}
})
