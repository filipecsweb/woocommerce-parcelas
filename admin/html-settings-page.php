<?php
/**
 * This file is responsible for plugin settings form
 *
 * @since 		1.2.5.1
 * @author 		Filipe Seabra <eu@filipecsweb.com.br>
 * @version 	1.2.5.1
 */
if(!defined('ABSPATH')){
	exit;
}
?>

<div class="wrap">
	<div class="section">
		<form action="options.php" method="post">
			<?php settings_fields($this->option_group); ?>
			<?php // settings_fields( $option_group ); ?>

			<?php do_settings_sections($this->page); ?>
			<?php // do_settings_sections( $page ); ?>

			<?php submit_button(); ?>
		</form>		
	</div>
	<div class="section">
		<div class="fswp_rodape">
			<p><?php echo __('Achou a ferramenta útil? Faça uma doação... assim você ajuda com a manutenção e criação de todos os projetos gratuitos.', 'woocommerce-parcelas'); ?></p>
			<form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">
				<input type="hidden" name="cmd" value="_s-xclick">
				<input type="hidden" name="hosted_button_id" value="QM6NM5RMLQ9L4">
				<input type="image" src="https://www.paypalobjects.com/pt_BR/BR/i/btn/btn_donateCC_LG.gif" border="0" name="submit" alt="Doe para FilipeCS Web">
				<img alt="" border="0" src="https://www.paypalobjects.com/pt_BR/i/scr/pixel.gif" width="1" height="1">
			</form>
			<hr />
			<?php echo '<a class="button-secondary" href="//filipecsweb.com.br/?p=43" target="_blank">' . __('Bugs e Sugestões', 'woocommerce-parcelas') . '</a>'; ?>
		</div>
	</div>
</div>