<?php
/**
 * This file is responsible for plugin settings form
 *
 * @since 		1.2.5.1
 * @author 		Filipe Seabra <eu@filipecsweb.com.br>
 * @version 	1.2.6
 */
if(!defined('ABSPATH')){
	exit;
}
?>

<div class="wrap woocommerce-parcelas">
	<h1><?php echo esc_html(get_admin_page_title()); ?></h1>

	<form action="options.php" method="post">
		<?php settings_fields($this->option_group); ?>			

		<div class="tabs">
			<h3 class="fs-active"><?php echo __('Geral', 'woocommerce-parcelas'); ?></h3>
			<div class="section">
				<h3><?php echo __('Opções para parcelamento', 'woocommerce-parcelas'); ?></h3>				
				<table class="form-table">					
					<?php do_settings_fields($this->page, 'fswp_installments_section'); ?>
				</table>

				<h3><?php echo __('Opções para pagamento à vista', 'woocommerce-parcelas'); ?></h3>
				<table class="form-table">
					<?php do_settings_fields($this->page, 'fswp_in_cash_section'); ?>
				</table>
			</div>
			
			<h3><?php echo __('Posição', 'woocommerce-parcelas'); ?></h3>
			<div class="section">				
				<p><?php echo __('Você pode mudar o local onde o <strong>preço parcelado</strong> aparece. Caso não queira alterar nada, basta deixar como está.', 'woocommerce-parcelas'); ?></p>
				<p><?php echo __('Detalhe: caso a opção para pagamento à vista esteja habilitada, o preço à vista será inserido logo após o preço parcelado.') ?></p>				
				<table class="form-table">					
					<?php do_settings_fields($this->page, 'fswp_position_section'); ?>
				</table>
			</div>
		</div>

		<?php submit_button(); ?>
	</form>
	
	<div class="section">
		<p><?php echo __('Colabore com o desenvolvimento de plugins gratuitos fazendo uma doação:') ?></p>
		<iframe style="width:100%; max-width:100%; height:30px;" src="//filipecsweb.com.br/plugin-footer.html" frameborder="0" scrolling="no"></iframe>

		<?php echo '<a class="button-secondary" href="//filipecsweb.com.br/?p=43" target="_blank">' . __('Bugs e Sugestões', 'woocommerce-parcelas') . '</a>'; ?>
	</div>
	
	<?php do_action('fswp_after_settings_page_submit_button'); ?>
</div>