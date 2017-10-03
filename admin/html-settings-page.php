<?php
/**
 * This file is responsible for plugin settings form
 *
 * @since         1.2.5.1
 * @author        Filipe Seabra <filipecseabra@gmail.com>
 * @version       1.2.9.1
 */
if (! defined('ABSPATH'))
{
    exit;
}
?>

<div class="wrap wc-parcelas">
    <h1><?php echo esc_html(get_admin_page_title()); ?></h1>

    <h2 class="nav-tab-wrapper">
        <a href="#general-tab" class="nav-tab nav-tab-active"><?php echo __('Geral', 'wc-parcelas'); ?></a>
        <a href="#position-tab" class="nav-tab"><?php echo __('Posição', 'wc-parcelas'); ?></a>
        <a href="#style-tab" class="nav-tab"><?php echo __('Estilo', 'wc-parcelas'); ?></a>
        <a href="#troubleshooting-tab" class="nav-tab"><?php echo __('Solucionar problemas', 'wc-parcelas'); ?></a>
    </h2>

    <form action="options.php" method="post">
        <?php settings_fields($this->option_group); ?>

        <div id="general-tab" class="section active">
            <h3><?php echo __('Opções para parcelamento', 'wc-parcelas'); ?></h3>
            <table class="form-table">
                <?php do_settings_fields($this->page, 'section_general-installments'); ?>
            </table>

            <h3><?php echo __('Opções para pagamento à vista', 'wc-parcelas'); ?></h3>
            <table class="form-table">
                <?php do_settings_fields($this->page, 'section_general-in_cash'); ?>
            </table>
        </div>

        <div id="position-tab" class="section">
            <p><?php echo __('Abaixo você pode definir a posição de alinhamento, das informações de parcela e pagamento à vista.', 'wc-parcelas'); ?></p>
            <table class="form-table">
                <?php do_settings_fields($this->page, 'section_position-alignment'); ?>
            </table>

            <hr/>

            <p><?php echo __('Abaixo você pode mudar o local onde o <strong>preço parcelado</strong> aparece.', 'wc-parcelas'); ?></p>
            <p><?php echo __('Detalhe: caso a opção para pagamento à vista esteja habilitada, o preço à vista será inserido logo após o preço parcelado.', 'wc-parcelas'); ?></p>
            <table class="form-table">
                <?php do_settings_fields($this->page, 'section_position-position'); ?>
            </table>
        </div>

        <div id="style-tab" class="section style">
            <div class="installments">
                <h3><?php echo __('Estilo para parcelamento', 'wc-parcelas'); ?></h3>

                <div class="in_loop">
                    <h4><u><?php echo __('Página que lista os produtos', 'wc-parcelas'); ?></u></h4>

                    <div class="wrapper">
                        <h5><?php echo __('Prefixo', 'wc-parcelas') ?></h5>
                        <?php do_settings_fields($this->page, $this->get_fswp_style_sections(0)); ?>
                        <h5><?php echo __('Preço', 'wc-parcelas') ?></h5>
                        <?php do_settings_fields($this->page, $this->get_fswp_style_sections(1)); ?>
                        <h5><?php echo __('Sufixo', 'wc-parcelas') ?></h5>
                        <?php do_settings_fields($this->page, $this->get_fswp_style_sections(2)); ?>
                    </div>
                </div>

                <div class="in_single">
                    <h4><u><?php echo __('Página individual do produto', 'wc-parcelas'); ?></u></h4>

                    <div class="wrapper">
                        <h5><?php echo __('Prefixo', 'wc-parcelas') ?></h5>
                        <?php do_settings_fields($this->page, $this->get_fswp_style_sections(3)); ?>
                        <h5><?php echo __('Preço', 'wc-parcelas') ?></h5>
                        <?php do_settings_fields($this->page, $this->get_fswp_style_sections(4)); ?>
                        <h5><?php echo __('Sufixo', 'wc-parcelas') ?></h5>
                        <?php do_settings_fields($this->page, $this->get_fswp_style_sections(5)); ?>
                    </div>
                </div>
            </div>

            <hr/>

            <div class="in_cash">
                <h3><?php echo __('Estilo para pagamento à vista', 'wc-parcelas'); ?></h3>

                <div class="in_loop">
                    <h4><u><?php echo __('Página que lista os produtos', 'wc-parcelas'); ?></u></h4>

                    <div class="wrapper">
                        <h5><?php echo __('Prefixo', 'wc-parcelas') ?></h5>
                        <?php do_settings_fields($this->page, $this->get_fswp_style_sections(6)); ?>
                        <h5><?php echo __('Preço', 'wc-parcelas') ?></h5>
                        <?php do_settings_fields($this->page, $this->get_fswp_style_sections(7)); ?>
                        <h5><?php echo __('Sufixo', 'wc-parcelas') ?></h5>
                        <?php do_settings_fields($this->page, $this->get_fswp_style_sections(8)); ?>
                    </div>
                </div>

                <div class="in_single">
                    <h4><u><?php echo __('Página individual do produto', 'wc-parcelas'); ?></u></h4>

                    <div class="wrapper">
                        <h5><?php echo __('Prefixo', 'wc-parcelas') ?></h5>
                        <?php do_settings_fields($this->page, $this->get_fswp_style_sections(9)); ?>
                        <h5><?php echo __('Preço', 'wc-parcelas') ?></h5>
                        <?php do_settings_fields($this->page, $this->get_fswp_style_sections(10)); ?>
                        <h5><?php echo __('Sufixo', 'wc-parcelas') ?></h5>
                        <?php do_settings_fields($this->page, $this->get_fswp_style_sections(11)); ?>
                    </div>
                </div>
            </div>
        </div>

        <div id="troubleshooting-tab" class="section">
            <p>Acesse a área oficial de suporte ao plugin WooCommerce Parcelas e poste sua <strong>sugestão</strong>, <strong>crítica</strong> ou <strong>dúvida</strong> por lá. Eu ou outras pessoas sempre estaremos dipostos a te ajudar:</p>
            <p><a href="https://wordpress.org/support/plugin/woocommerce-parcelas" target="_blank">https://wordpress.org/support/plugin/woocommerce-parcelas</a></p>
            <hr />
            <p>Se este plugin é útil para você, considere fazer uma doação e me ajude a mante-lo sempre atualizado:</p>
            <p><a href="https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=BPVZJZ2WG8ZPG" target="_blank">CLIQUE AQUI PARA DOAR</a></p>
        </div>

        <?php submit_button(); ?>
    </form>

    <?php do_action('fswp_after_settings_page_submit_button'); ?>
</div>