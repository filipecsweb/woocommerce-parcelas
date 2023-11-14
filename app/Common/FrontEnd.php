<?php

namespace WcParcelas\App\Common;

defined( 'ABSPATH' ) || exit;

class FrontEnd {
	public function __construct() {
		add_action( 'init', [ $this, 'init' ] );
	}

	public function init() {
		if (
			! wcParcelas()->settings->getOption( 'enable_installments' ) &&
			! wcParcelas()->settings->getOption( 'enable_in_cash' )
		) {
			return;
		}

		add_action( 'wp_enqueue_scripts', [ $this, 'public_stylesheet_and_javascript' ] );

		// The tag below is loaded by WooCommerce only in variable products that have children with different prices
		// The priority is 98 because the function below will load necessary variables to do the calculation
		// The action with priority 99 is located under the individual files that are doing the calculation
        // @TODO Both this hook and the one above should be removed in case the both outputs end up not being done (because e.g. the product is out of stock).
		add_action( 'woocommerce_before_single_variation', [ $this, 'fswp_variable_product_js_variables' ], 98 );

		add_action( wcParcelas()->settings->getOption( 'fswp_in_loop_position' ), [ $this, 'fswp_in_loop' ], wcParcelas()->settings->getOption( 'fswp_in_loop_position_level' ) );

		add_action( wcParcelas()->settings->getOption( 'fswp_in_single_position' ), [ $this, 'fswp_in_single' ], wcParcelas()->settings->getOption( 'fswp_in_single_position_level' ) );
	}

	public function public_stylesheet_and_javascript() {
		wp_enqueue_style( WC_PARCELAS_SLUG . '-public', WC_PARCELAS_URL . 'public/styles/public.php', [], WC_PARCELAS_VERSION );
	}

	/**
	 * @return void.
	 */
	public function fswp_in_loop() {
		do_action( 'before_installments_in_loop' );

		$product = wc_get_product();
		$class   = 'loop';

		if ( ! wc_get_price_including_tax( $product ) ) {
			return;
		}

        // @TODO The condition to output the installments and in cash price should be a helper.
		if (
			wcParcelas()->settings->getOption( 'enable_installments' ) &&
			wcParcelas()->metaBox->get_fswp_post_meta_data( wcParcelas()->metaBox->disable_installments_key ) !== '1'
		) {
			if (
				wcParcelas()->utils->isProductInStock( $product ) ||
				'yes' === wcParcelas()->settings->getOption( 'enable_installments_if_out_of_stock' )
			) {
			    include WC_PARCELAS_PATH . 'public/views/installments-calc.php';
            }
		}

		if (
			wcParcelas()->settings->getOption( 'enable_in_cash' ) &&
			wcParcelas()->metaBox->get_fswp_post_meta_data( wcParcelas()->metaBox->disable_in_cash_key ) !== '1'
		) {
			if (
				wcParcelas()->utils->isProductInStock( $product ) ||
				'yes' === wcParcelas()->settings->getOption( 'enable_in_cash_if_out_of_stock' )
			) {
			    include WC_PARCELAS_PATH . 'public/views/in-cash-calc.php';
			}
		}

		do_action( 'after_installments_in_loop' );
	}

	/**
	 * @return void.
	 */
	public function fswp_in_single() {
		do_action( 'before_installments_in_single' );

		$product = wc_get_product();
		$class   = 'single';

		if (
			wcParcelas()->settings->getOption( 'enable_installments' ) &&
			wcParcelas()->metaBox->get_fswp_post_meta_data( wcParcelas()->metaBox->disable_installments_key ) !== '1'
		) {
			if (
				wcParcelas()->utils->isProductInStock( $product ) ||
				'yes' === wcParcelas()->settings->getOption( 'enable_installments_if_out_of_stock' )
			) {
				include WC_PARCELAS_PATH . 'public/views/installments-calc.php';
			}
		}

		if (
			wcParcelas()->settings->getOption( 'enable_in_cash' ) &&
			wcParcelas()->metaBox->get_fswp_post_meta_data( wcParcelas()->metaBox->disable_in_cash_key ) !== '1'
		) {
			if (
				wcParcelas()->utils->isProductInStock( $product ) ||
				'yes' === wcParcelas()->settings->getOption( 'enable_in_cash_if_out_of_stock' )
			) {
				include WC_PARCELAS_PATH . 'public/views/in-cash-calc.php';
			}
		}

		do_action( 'after_installments_in_single' );
	}

	/**
	 * @return void
	 */
	public function fswp_variable_product_js_variables() { ?>
        <script>
            let x_de = <?php echo "'" . __( 'x de', 'wc-parcelas' ) . "'"; ?>;
            let dec_sep = <?php echo "'" . get_option( 'woocommerce_price_decimal_sep' ) . "'"; ?>;
            let th_sep = <?php echo "'" . get_option( 'woocommerce_price_thousand_sep' ) . "'"; ?>;
            let cur_symbol = <?php echo "'" . get_woocommerce_currency_symbol() . "'"; ?>;
            let cur_pos = <?php echo "'" . get_option( 'woocommerce_currency_pos' ) . "'"; ?>;

            function formatMoney(cur_symbol, number, c, d, m, cur_pos) {
                c = isNaN(c = Math.abs(c)) ? 2 : c,
                    d = d === undefined ? "." : d,
                    m = m === undefined ? "," : m,
                    s = number < 0 ? "-" : "",
                    i = parseInt(number = Math.abs(+number || 0).toFixed(c)) + "",
                    j = (j = i.length) > 3 ? j % 3 : 0,
                    cur_pos = cur_pos === "" ? "left" : cur_pos;

                if (cur_pos === 'left') {
                    return cur_symbol + s + (j ? i.substr(0, j) + m : "") + i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + m) + (c ? d + Math.abs(number - i).toFixed(c).slice(2) : "");
                } else if (cur_pos === 'left_space') {
                    return cur_symbol + '&nbsp;' + s + (j ? i.substr(0, j) + m : "") + i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + m) + (c ? d + Math.abs(number - i).toFixed(c).slice(2) : "");
                } else if (cur_pos === 'right') {
                    return s + (j ? i.substr(0, j) + m : "") + i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + m) + (c ? d + Math.abs(number - i).toFixed(c).slice(2) : "") + cur_symbol;
                } else if (cur_pos === 'right_space') {
                    return s + (j ? i.substr(0, j) + m : "") + i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + m) + (c ? d + Math.abs(number - i).toFixed(c).slice(2) : "") + '&nbsp;' + cur_symbol;
                }
            }
        </script>
	<?php }

	/**
	 * Calculate and display installments when child products have different prices
	 *
	 * @return  void
	 */
	public function fswp_variable_installment_calculation() {
		$installment_qty_overwrite = wcParcelas()->metaBox->get_fswp_post_meta_data( wcParcelas()->metaBox->installment_qty_key );
		$installment_qty           = ! empty( $installment_qty_overwrite ) ? $installment_qty_overwrite : wcParcelas()->settings->getOption( 'installment_qty' ); ?>
        <script>
            let installment_prefix = <?php echo "'" . wcParcelas()->settings->getOption( 'installment_prefix' ) . "'"; ?>;
            let installment_qty = <?php echo $installment_qty; ?>;
            let installment_suffix = <?php echo "'" . wcParcelas()->settings->getOption( 'installment_suffix' ) . "'"; ?>;
            let installment_minimum_value = <?php echo "'" . wcParcelas()->settings->getOption( 'installment_minimum_value' ) ? str_replace( ',', '.', wcParcelas()->settings->getOption( 'installment_minimum_value' ) ) : 0 . "'"; ?>;
        </script>
        <script async defer src="<?php echo WC_PARCELAS_URL, 'public/scripts/variable-installment-calculation.js?v=', WC_PARCELAS_VERSION ?>"></script>
        <div class='fswp_variable_installment_calculation'></div>
	<?php }

	/**
	 * Calculate and display in cash price when child products have different prices
	 *
	 * @return  void
	 */
	public function fswp_variable_in_cash_calculation() {
		$in_cash_discount_overwrite      = wcParcelas()->metaBox->get_fswp_post_meta_data( wcParcelas()->metaBox->in_cash_discount_key );
		$in_cash_discount                = $in_cash_discount_overwrite ?? wcParcelas()->settings->getOption( 'in_cash_discount' );
		$in_cash_discount_type_overwrite = wcParcelas()->metaBox->get_fswp_post_meta_data( wcParcelas()->metaBox->in_cash_discount_type_key );
		$in_cash_discount_type           = $in_cash_discount_type_overwrite ?? wcParcelas()->settings->getOption( 'in_cash_discount_type' ); ?>
        <script>
            let in_cash_prefix = <?php echo "'" . wcParcelas()->settings->getOption( 'in_cash_prefix' ) . "'"; ?>;
            let in_cash_discount = <?php echo "'" . $in_cash_discount . "'"; ?>;
            let in_cash_discount_type = <?php echo "'" . $in_cash_discount_type . "'"; ?>;
            let in_cash_suffix = <?php echo "'" . wcParcelas()->settings->getOption( 'in_cash_suffix' ) . "'"; ?>;
        </script>
        <script async defer src="<?php echo WC_PARCELAS_URL, 'public/scripts/variable-in-cash-calculation.js?v=', WC_PARCELAS_VERSION ?>"></script>
        <div class='fswp_variable_in_cash_calculation'></div>
	<?php }
}