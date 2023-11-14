<?php
/**
 * Plugin Name: WooCommerce Parcelas
 * Plugin URI: https://wordpress.org/plugins/woocommerce-parcelas/
 * Description: Adiciona quantidade de parcelas e o valor de cada parcela nas páginas que listam os produtos e na página individual do produto.
 * Author: Filipe Seabra
 * Author URI: https://filipeseabra.me/
 * Version: 1.3.5
 * Text Domain: wc-parcelas
 * Domain Path: /languages/
 */

use WcParcelas\App\Main;

defined( 'ABSPATH' ) || exit;

try {
	if ( ! file_exists( __DIR__ . '/vendor/autoload.php' ) ) {
		throw new Exception( sprintf( 'The autoload file was not found. File: "%s".', __DIR__ . '/vendor/autoload.php' ) );
	}

	require_once __DIR__ . '/vendor/autoload.php';
} catch ( Exception $e ) {
	error_log( $e->getMessage() );

	return;
}

define( 'WC_PARCELAS_PATH', plugin_dir_path( __FILE__ ) );
define( 'WC_PARCELAS_URL', plugin_dir_url( __FILE__ ) );
define( 'WC_PARCELAS_BASENAME', plugin_basename( __FILE__ ) );
define( 'WC_PARCELAS_VERSION', '1.3.5' );
define( 'WC_PARCELAS_NAME', 'WooCommerce Parcelas' );
define( 'WC_PARCELAS_SLUG', 'wc-parcelas' );

/**
 * @return Main
 */
function wcParcelas(): ?Main {
	static $object = null;
	if ( ! isset( $object ) ) {
		$object = new Main();
	}

	return $object;
}

wcParcelas();