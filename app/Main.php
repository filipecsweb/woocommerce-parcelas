<?php

namespace WcParcelas\App;

use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

defined( 'ABSPATH' ) || exit;

/**
 * Plugin main service class.
 */
final class Main extends MainAbstract {
	/**
	 * Constructor.
	 */
	public function __construct() {
		$this->init_container();
		$this->init_plugin();
	}

	/**
	 * @return void
	 * @throws ContainerExceptionInterface
	 * @throws NotFoundExceptionInterface
	 */
	private function init_container() {
		$container = new Container();

		$this->utils    = $container->get( 'utils' );
		$this->settings = $container->get( 'settings' );
		$this->metaBox  = $container->get( 'metaBox' );
		$this->frontEnd = $container->get( 'frontEnd' );
	}

	/**
	 * @return void
	 */
	private function init_plugin() {
		add_action( 'plugins_loaded', function () {
			if ( ! in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins', [] ) ) ) ) {
				add_action( 'admin_notices', [ $this, 'wc_missing_notice' ] );

				return;
			}

			add_action( 'init', [ $this, 'load_plugin_textdomain' ] );
			add_action( 'plugin_action_links_' . WC_PARCELAS_BASENAME, [ $this, 'load_plugin_action_links' ] );
			add_action( 'admin_enqueue_scripts', [ $this, 'load_admin_assets' ] );
		} );
	}

	/**
	 * @return void
	 */
	public function load_plugin_textdomain() {
		load_plugin_textdomain( 'wc-parcelas', false, dirname( WC_PARCELAS_BASENAME ) . '/languages' );
	}

	/**
	 * @param  array $links
	 * @return array
	 */
	public function load_plugin_action_links( $links ) {
		$settings_url = admin_url( 'admin.php?page=fswp' );

		return array_merge( (array) $links, [
			'<a href="' . esc_url( $settings_url ) . '">' . __( 'Settings' ) . '</a>'
		] );
	}

	/**
	 * @param  string $hook_suffix The current admin page.
	 * @return void
	 */
	public function load_admin_assets( $hook_suffix ) {
		if ( 'woocommerce_page_fswp' !== $hook_suffix ) {
			return;
		}

		wp_enqueue_style( WC_PARCELAS_SLUG . '-admin', WC_PARCELAS_URL . 'public/styles/admin.css', [], WC_PARCELAS_VERSION );
		wp_enqueue_style( 'wp-color-picker' );

		wp_enqueue_script( WC_PARCELAS_SLUG . '-admin', WC_PARCELAS_URL . 'public/scripts/admin.js', [ 'jquery', 'wp-color-picker' ], WC_PARCELAS_VERSION, false );
	}

	/**
	 * @return void
	 */
	public function wc_missing_notice() {
		$class   = 'error';
		$message = sprintf( __( 'Não é possível habilitar o plugin %s enquanto o %s não estiver instalado e ativado.', 'wc-parcelas' ), '<strong>' . WC_PARCELAS_NAME . '</strong>', '<a href="//wordpress.org/plugins/woocommerce/" target="_blank">WooCommerce</a>' );

		echo "<div class='$class'>";
		echo "<p>$message</p>";
		echo "</div>";
	}
}