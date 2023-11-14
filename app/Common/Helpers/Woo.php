<?php

namespace WcParcelas\App\Common\Helpers;

defined( 'ABSPATH' ) || exit;

trait Woo {
	/**
	 * @since 1.3.4
	 *
	 * @param  \WC_Product|null|false $product The product instance.
	 * @return bool Whether the current product is in stock.
	 */
	public function isProductInStock( $product = null ) {
		$product = $product ?: wc_get_product();
		if ( ! $product ) {
			return false;
		}

		return $product->is_in_stock();
	}
}