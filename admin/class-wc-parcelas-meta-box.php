<?php

/**
 * Add meta box within product page
 *
 * @author      Filipe Seabra <filipecseabra@gmail.com>
 * @since       1.2.7
 * @version     1.2.8
 */

class Woocommerce_Parcelas_Meta_Box extends Woocommerce_Parcelas_Settings {

	/**
	 * @var     string $fswp_post_meta_key
	 */
	public $fswp_post_meta_key = 'fswp_post_meta';
	/**
	 * @var     string $disable_in_cash_key
	 */
	public $disable_in_cash_key = 'disable_in_cash';
	/**
	 * @var     string $custom_in_cash_discount_type_key
	 */
	public $custom_in_cash_discount_type_key = 'in_cash_discount_type';	
	/**
	 * @var     string $custom_in_cash_discount_key
	 */
	public $custom_in_cash_discount_key = 'in_cash_discount';
	/**
	 * @var     string $disable_installments_key
	 */
	public $disable_installments_key = 'disable_installments';
	/**
	 * @var     string $custom_installment_qty_key
	 */
	public $custom_installment_qty_key = 'installment_qty';

	public function __construct() {
		/**
		 * Add WooCommperce Parcelas Meta Box
		 */
		add_action( 'add_meta_boxes', array( $this, 'add_fswp_product_meta_box' ) );

		/**
		 * Save WooCommerce Parcelas Meta Box
		 */
		add_action( 'save_post', array( $this, 'save_fswp_product_meta_box' ) );
	}

	public function add_fswp_product_meta_box() {
		add_meta_box(
			'fswp_product_meta_box',
			__( 'Parcelamento e pagamento à vista', 'wc-parcelas' ),
			array( $this, 'fswp_product_meta_box_callback' ),
			'product',
			'normal',
			'low'
		);
	}

	public function fswp_product_meta_box_callback( $post, $metabox ) {
		wp_nonce_field( 'fswp_product_meta_box_nonce_context', 'fswp_product_meta_box_nonce_name' );
		// Disable in cash for this product field
		echo "<p>";
			echo "<input type='hidden' name='$this->fswp_post_meta_key[$this->disable_in_cash_key]' value='0' />";
			echo "<input type='checkbox' id='$this->fswp_post_meta_key[$this->disable_in_cash_key]' name='$this->fswp_post_meta_key[$this->disable_in_cash_key]' value='1' " . checked( 1, $this->get_fswp_post_meta_data($this->disable_in_cash_key ), false ) . " />";
			echo "<label for='$this->fswp_post_meta_key[$this->disable_in_cash_key]'>" . __('Desativar preço à vista para este produto', 'wc-parcelas') . "</label>";
		echo "</p>";
		// Overwrite in cash discount type field
		$in_cash_discount_type = isset($this->settings[$this->custom_in_cash_discount_type_key]) ? $this->settings[$this->custom_in_cash_discount_type_key] : 0;
		$in_cash_discount_type_selected = $this->get_fswp_post_meta_data($this->custom_in_cash_discount_type_key) ?: $in_cash_discount_type;
		echo "<p>";
			echo "<label for='$this->fswp_post_meta_key[$this->custom_in_cash_discount_type_key]'>" . __('Sobreescrever o tipo de desconto à vista global', 'wc-parcelas') . ":</label> ";
			echo "<select id='$this->fswp_post_meta_key[$this->custom_in_cash_discount_type_key]' name='$this->fswp_post_meta_key[$this->custom_in_cash_discount_type_key]'>";
				echo "<option value='0' " . selected($in_cash_discount_type_selected, 0, false ) . ">" . '%' . ($in_cash_discount_type==0 ? ' ('. __('global', 'wc-parcelas') . ')' : '') . "</option>";
				echo "<option value='1' " . selected($in_cash_discount_type_selected, 1, false ) . ">" . __('fixo', 'wc-parcelas') . ($in_cash_discount_type == 1 ? ' (' . __('global', 'wc-parcelas') . ')' : '') . "</option>";
			echo "</select>";
		echo "</p>";
		// Overwrite in cash discount field
		$in_cash_discount = isset($this->settings[$this->custom_in_cash_discount_key]) ? $this->settings[$this->custom_in_cash_discount_key] : false;
		echo "<p>";
			echo "<label for='$this->fswp_post_meta_key[$this->custom_in_cash_discount_key]'>" . __('Sobreescrever o desconto à vista global', 'wc-parcelas') . ":</label> ";
			echo "<input type='number' id='$this->fswp_post_meta_key[$this->custom_in_cash_discount_key]' name='$this->fswp_post_meta_key[$this->custom_in_cash_discount_key]' value='" . $this->get_fswp_post_meta_data($this->custom_in_cash_discount_key) . "' min='0' step='0.01' placeholder='". $in_cash_discount ."' />";
		echo "</p>";
		// Disable installments for this product field
		echo "<p>";
			echo "<input type='hidden' name='$this->fswp_post_meta_key[$this->disable_installments_key]' value='0' />";
			echo "<input type='checkbox' id='$this->fswp_post_meta_key[$this->disable_installments_key]' name='$this->fswp_post_meta_key[$this->disable_installments_key]' value='1' " . checked( 1, $this->get_fswp_post_meta_data($this->disable_installments_key ), false ) . " />";
			echo "<label for='$this->fswp_post_meta_key[$this->disable_installments_key]'>" . __('Desativar preço parcelado para este produto', 'wc-parcelas') . "</label>";
		echo "</p>";
		// Overwrite installments qty field
		$installment_qty = isset($this->settings[$this->custom_installment_qty_key]) ? $this->settings[$this->custom_installment_qty_key] : false;
		echo "<p>";
			echo "<label for='$this->fswp_post_meta_key[$this->custom_installment_qty_key]'>" . __('Sobreescrever a quantidade de parcelas global', 'wc-parcelas') . ":</label> ";
			echo "<input type='number' id='$this->fswp_post_meta_key[$this->custom_installment_qty_key]' name='$this->fswp_post_meta_key[$this->custom_installment_qty_key]' value='" . $this->get_fswp_post_meta_data($this->custom_installment_qty_key) . "' min='0' step='1' placeholder='". $installment_qty ."' />";
		echo "</p>";
	}

	/**
	 * Get fswp post meta value
	 *
	 * @param  string  $field  meta_value name
	 *
	 * @return  string  $fswp_post_meta_data[$field]    meta_value field
	 */
	public function get_fswp_post_meta_data( $field ) {
		$post_id = get_the_ID();

		if ( null != get_post_meta( $post_id, $this->fswp_post_meta_key ) ) {
			$fswp_post_meta_data = get_post_meta( $post_id, $this->fswp_post_meta_key, true );

			return isset($fswp_post_meta_data[$field]) ? $fswp_post_meta_data[$field] : false;
		}

		return false;
	}

	public function save_fswp_product_meta_box( $post_id ) {
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}

		if ( ! isset( $_POST['fswp_product_meta_box_nonce_name'] ) || ! wp_verify_nonce( $_POST['fswp_product_meta_box_nonce_name'],
				'fswp_product_meta_box_nonce_context' ) ) {
			return;
		}

		if ( ! current_user_can( 'edit_posts' ) ) {
			return;
		}

		/**
		 * Add or update post meta
		 */
		if ( null != get_post_meta( $post_id, $this->fswp_post_meta_key ) ) {
			update_post_meta( $post_id, $this->fswp_post_meta_key, $_POST[ $this->fswp_post_meta_key ] );
		} else {
			if ( isset( $_POST[ $this->fswp_post_meta_key ] ) ) {
				add_post_meta( $post_id, $this->fswp_post_meta_key, $_POST[ $this->fswp_post_meta_key ], true );
			}
		}
	}
}

new Woocommerce_Parcelas_Meta_Box();