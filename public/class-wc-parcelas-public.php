<?php

/**
 * Build functions that will modify frontend
 *
 * @since    1.0.0
 * @author   Filipe Seabra <filipecseabra@gmail.com>
 * @version  1.2.9
 */
class Woocommerce_Parcelas_Public extends Woocommerce_Parcelas_Meta_Box {

	/**
	 * Is installments option enabled?
	 *
	 * @var     string $allow_installments
	 */
	public $allow_installments;

	/**
	 * Is in cash option enabled?
	 *
	 * @var     string $allow_in_cash
	 */
	public $allow_in_cash;

	public function __construct() {
		/**
		 * Keep array of settings
		 */
		$this->settings = get_option( $this->option_name );

		$this->allow_installments = isset( $this->settings['enable_installments'] ) ? $this->settings['enable_installments'] : 0;

		$this->allow_in_cash = isset( $this->settings['enable_in_cash'] ) ? $this->settings['enable_in_cash'] : 0;

		if ( $this->allow_installments != 1 && $this->allow_in_cash != 1 ) {
			return;
		} elseif ( $this->allow_installments || $this->allow_in_cash ) {
			/**
			 * Add plugin Stylesheet and JavaScript, in frontend
			 */
			add_action( 'wp_enqueue_scripts', array( $this, 'public_stylesheet_and_javascript' ) );

			// The tag below is loaded by WooCommerce only in varable products that have children with different prices
			// The priority is 98 because the function below will load necessary variables to do the calculation
			// The action with priority 99 is located under the individual files that are doing the calculation
			add_action( 'woocommerce_before_single_variation', array( $this, 'fswp_variable_product_js_variables' ), 98 );

			/**
			 * Add installments to WooCommerce loop pages
			 */
			add_action( $this->settings['fswp_in_loop_position'], array( $this, 'fswp_in_loop' ), $this->settings['fswp_in_loop_position_level'] );

			/**
			 * Add installments to Woocommerce single product page
			 */
			add_action( $this->settings['fswp_in_single_position'], array( $this, 'fswp_in_single' ),
				$this->settings['fswp_in_single_position_level'] );
		}
	}

	public function public_stylesheet_and_javascript() {
		wp_enqueue_style( WC_PARCELAS_SLUG . '-public', WC_PARCELAS_URL . 'public/css/wc-parcelas-public.php', array(), WC_PARCELAS_VERSION, 'all' );
	}

	/**
	 * Displays the installments price on loop.
	 *
	 * @return void.
	 */
	public function fswp_in_loop() {
		do_action( 'before_installments_in_loop' );

		/**
		 * Get product data
		 *
		 * @var     WC_Product $product
		 */
		$product = wc_get_product();

		/**
		 * Initialize class that will identify whether the product is in single or in loop
		 *
		 * @var     string $class
		 */
		$class = 'loop';

		if ( !wc_get_price_including_tax($product)) {
			return;
		}

		if ( $this->allow_installments ) {
			if ( $this->get_fswp_post_meta_data( $this->disable_installments_key ) !== '1' ) {
				include 'installments-calc.php';
			}
		}

		if ( $this->allow_in_cash ) {
			if ( $this->get_fswp_post_meta_data( $this->disable_in_cash_key ) !== '1' ) {
				include 'in-cash-calc.php';
			}
		}

		do_action( 'after_installments_in_loop' );
	}

	/**
	 * Display the installments price on single product.
	 *
	 * @return void.
	 */
	public function fswp_in_single() {
		do_action( 'before_installments_in_single' );

		/**
		 * Get product data
		 *
		 * @var     object $product
		 */
		$product = wc_get_product();

		/**
		 * Initialize class that will identify whether the product is in single or in loop
		 *
		 * @var     string $class
		 */
		$class = 'single';

		/**
		 * If installment option is enabled in backend
		 */
		if ( $this->allow_installments ) {
			if ( $this->get_fswp_post_meta_data( $this->disable_installments_key ) !== '1' ) {
				include 'installments-calc.php';
			}
		}

		/**
		 * If in cash option is enabled in backend
		 */
		if ( $this->allow_in_cash ) {
			if ( $this->get_fswp_post_meta_data( $this->disable_in_cash_key ) !== '1' ) {
				include 'in-cash-calc.php';
			}
		}

		do_action( 'after_installments_in_single' );
	}

	/**
	 * Insert necessaryJavaScript variables before doing calculation
	 *
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
		// Get get_fswp_post_meta_data for installment qty overwrite
		$installment_qty_overwrite = $this->get_fswp_post_meta_data( $this->custom_installment_qty_key ); ?>
        <script>
            let installment_prefix = <?php echo "'" . $this->settings['installment_prefix'] . "'"; ?>;
            let installment_qty = <?php echo empty($installment_qty_overwrite) ? $this->settings['installment_qty'] : $installment_qty_overwrite; ?>;
            let installment_suffix = <?php echo "'" . $this->settings['installment_suffix'] . "'"; ?>;
            let installment_minimum_value = <?php echo isset( $this->settings['installment_minimum_value'] ) ? str_replace( ',', '.',
				$this->settings['installment_minimum_value'] ) : 0; ?>;
        </script>
        <div class='fswp_variable_installment_calculation'></div>
        <script src="<?php echo WC_PARCELAS_URL . 'public/js/variable-installment-calculation.js' ?>"></script>
	<?php }

	/**
	 * Calculate and display in cash price when child products have different prices
	 *
	 * @return  void
	 */
	public function fswp_variable_in_cash_calculation() {
		// Get get_fswp_post_meta_data for in cash overwrite
		$in_cash_discount_overwrite = $this->get_fswp_post_meta_data($this->custom_in_cash_discount_key);
		$in_cash_discount_type_overwrite = $this->get_fswp_post_meta_data($this->custom_in_cash_discount_type_key); ?>
        <script>
            let in_cash_prefix = <?php echo "'" . $this->settings['in_cash_prefix'] . "'"; ?>;
            let in_cash_discount = <?php echo "'" . empty($in_cash_discount_overwrite) ? $this->settings['in_cash_discount'] : $in_cash_discount_overwrite . "'"; ?>;
            let in_cash_discount_type = <?php echo (string) empty($in_cash_discount_type_overwrite) ? $this->settings['in_cash_discount_type'] : $in_cash_discount_type_overwrite; ?>;
            let in_cash_suffix = <?php echo "'" . $this->settings['in_cash_suffix'] . "'"; ?>;
        </script>
        <div class='fswp_variable_in_cash_calculation'></div>
        <script src="<?php echo WC_PARCELAS_URL . 'public/js/variable-in-cash-calculation.js' ?>"></script>
	<?php }
}

new Woocommerce_Parcelas_Public();
