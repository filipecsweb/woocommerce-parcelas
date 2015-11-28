jQuery(document).ready(function(){
	$ = jQuery.noConflict();

	(function(){
		$('.woocommerce-parcelas form h3').click(function(){
			$('.woocommerce-parcelas form h3').removeClass('fs-active');
			$(this).addClass('fs-active');
		});

		$('.color-field').wpColorPicker();
	})();
});