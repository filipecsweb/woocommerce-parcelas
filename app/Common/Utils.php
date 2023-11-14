<?php

namespace WcParcelas\App\Common;

defined( 'ABSPATH' ) || exit;

/**
 * Service class for utilities.
 */
class Utils {
	use Helpers\Woo;

	/**
	 * @param  string $template_name Template name.
	 * @param  array  $args          Arguments.
	 * @return void
	 */
	public function loadTemplate( string $template_name, array $args = [] ) {
		if ( ! empty( $args ) && is_array( $args ) ) {
			extract( $args );
		}

		include WC_PARCELAS_PATH . 'public/views/' . $template_name . '.php';
	}
}