<?php
/**
 * This file is responsible for plugin settings form
 *
 * @since 		1.2.5.1
 * @author 		Filipe Seabra <eu@filipecsweb.com.br>
 * @version 	1.2.8
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
					<?php do_settings_fields($this->page, 'section_general-installments'); ?>
				</table>

				<h3><?php echo __('Opções para pagamento à vista', 'woocommerce-parcelas'); ?></h3>
				<table class="form-table">
					<?php do_settings_fields($this->page, 'section_general-in_cash'); ?>
				</table>
			</div>
			
			<h3><?php echo __('Posição', 'woocommerce-parcelas'); ?></h3>
			<div class="section">				
				<p><?php echo __('Abaixo você pode definir a posição de alinhamento, das informações de parcela e pagamento à vista.', 'woocommerce-parcelas'); ?></p>
				<table class="form-table">
					<?php do_settings_fields($this->page, 'section_position-alignment'); ?>
				</table>

				<hr />

				<p><?php echo __('Abaixo você pode mudar o local onde o <strong>preço parcelado</strong> aparece.', 'woocommerce-parcelas'); ?></p>
				<p><?php echo __('Detalhe: caso a opção para pagamento à vista esteja habilitada, o preço à vista será inserido logo após o preço parcelado.', 'woocommerce-parcelas'); ?></p>
				<table class="form-table">					
					<?php do_settings_fields($this->page, 'section_position-position'); ?>
				</table>				
			</div>

			<h3><?php echo __('Estilo', 'woocommerce-parcelas'); ?></h3>
			<div class="section style">							
				<div class="installments">
					<h3><?php echo __('Estilo para parcelamento', 'woocommerce-parcelas'); ?></h3>

					<div class="in_loop">
						<h4><u><?php echo __('Página que lista os produtos', 'woocommerce-parcelas'); ?></u></h4>

						<div class="wrapper">
							<h5><?php echo __('Prefixo', 'woocommerce-parcelas') ?></h5>
							<?php do_settings_fields($this->page, $this->get_fswp_style_sections(0)); ?>
							<h5><?php echo __('Preço', 'woocommerce-parcelas') ?></h5>
							<?php do_settings_fields($this->page, $this->get_fswp_style_sections(1)); ?>
							<h5><?php echo __('Sufixo', 'woocommerce-parcelas') ?></h5>
							<?php do_settings_fields($this->page, $this->get_fswp_style_sections(2)); ?>
						</div>
					</div>

					<div class="in_single">
						<h4><u><?php echo __('Página individual do produto', 'woocommerce-parcelas'); ?></u></h4>

						<div class="wrapper">
							<h5><?php echo __('Prefixo', 'woocommerce-parcelas') ?></h5>
							<?php do_settings_fields($this->page, $this->get_fswp_style_sections(3)); ?>
							<h5><?php echo __('Preço', 'woocommerce-parcelas') ?></h5>
							<?php do_settings_fields($this->page, $this->get_fswp_style_sections(4)); ?>
							<h5><?php echo __('Sufixo', 'woocommerce-parcelas') ?></h5>
							<?php do_settings_fields($this->page, $this->get_fswp_style_sections(5)); ?>
						</div>
					</div>
				</div>

				<hr />

				<div class="in_cash">
					<h3><?php echo __('Estilo para pagamento à vista', 'woocommerce-parcelas'); ?></h3>

					<div class="in_loop">
						<h4><u><?php echo __('Página que lista os produtos', 'woocommerce-parcelas'); ?></u></h4>

						<div class="wrapper">
							<h5><?php echo __('Prefixo', 'woocommerce-parcelas') ?></h5>
							<?php do_settings_fields($this->page, $this->get_fswp_style_sections(6)); ?>
							<h5><?php echo __('Preço', 'woocommerce-parcelas') ?></h5>
							<?php do_settings_fields($this->page, $this->get_fswp_style_sections(7)); ?>
							<h5><?php echo __('Sufixo', 'woocommerce-parcelas') ?></h5>
							<?php do_settings_fields($this->page, $this->get_fswp_style_sections(8)); ?>
						</div>
					</div>

					<div class="in_single">
						<h4><u><?php echo __('Página individual do produto', 'woocommerce-parcelas'); ?></u></h4>

						<div class="wrapper">
							<h5><?php echo __('Prefixo', 'woocommerce-parcelas') ?></h5>
							<?php do_settings_fields($this->page, $this->get_fswp_style_sections(9)); ?>
							<h5><?php echo __('Preço', 'woocommerce-parcelas') ?></h5>
							<?php do_settings_fields($this->page, $this->get_fswp_style_sections(10)); ?>
							<h5><?php echo __('Sufixo', 'woocommerce-parcelas') ?></h5>
							<?php do_settings_fields($this->page, $this->get_fswp_style_sections(11)); ?>
						</div>
					</div>
				</div>
			</div>
		</div>

		<?php submit_button(); ?>
	</form>
	
	<div class="section">
		<p><?php echo __('Ajude a manter o WooCommerce Parcelas gratuito fazendo uma doação:', 'woocommerce-parcelas'); ?></p>
		<iframe style="width:100%; max-width:100%; height:30px;" src="//filipecsweb.com.br/plugin-footer.html" frameborder="0" scrolling="no"></iframe>

		<?php echo '<a class="button-secondary" href="//filipecsweb.com.br/contato" target="_blank">' . __('Bugs e Sugestões', 'woocommerce-parcelas') . '</a>'; ?>
	</div>
	
	<?php do_action('fswp_after_settings_page_submit_button'); ?>
</div>