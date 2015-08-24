jQuery(document).ready(function(){
	jQuery('.woocommerce-parcelas form h3').click(function(){
		jQuery('.woocommerce-parcelas form h3').removeClass('fs-active');
		jQuery(this).addClass('fs-active');
	});	
});