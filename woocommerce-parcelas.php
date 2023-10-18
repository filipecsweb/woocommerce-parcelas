<?php
/**
 * Plugin Name: WooCommerce Parcelas
 * Plugin URI: https://wordpress.org/plugins/woocommerce-parcelas/
 * Description: Adiciona quantidade de parcelas e o valor de cada parcela nas páginas que listam os produtos e na página individual do produto.
 * Author: Filipe Seabra
 * Author URI: https://filipeseabra.me/
 * Version: 1.3
 * Text Domain: wc-parcelas
 * Domain Path: /languages/
 */

defined( 'ABSPATH' ) || exit;

define( 'WC_PARCELAS_PATH', plugin_dir_path( __FILE__ ) );
define( 'WC_PARCELAS_URL', plugin_dir_url( __FILE__ ) );
define( 'WC_PARCELAS_VERSION', '1.3' );
define( 'WC_PARCELAS_NAME', 'WooCommerce Parcelas' );
define( 'WC_PARCELAS_SLUG', 'wc-parcelas' );

/**
 * The code that runs during plugin activation
 * This action is documented in includes/class-wc-parcelas-activator.php
 */
function woocommerce_parcelas_activate() {
	require_once WC_PARCELAS_PATH . 'includes/class-wc-parcelas-activator.php';
	Woocommerce_Parcelas_Activator::activate();
}

/**
 * The code that runs during plugin deactivation
 * This action is documented in includes/class-wc-parcelas-deactivator.php
 */
function woocommerce_parcelas_deactivate() {
	require_once WC_PARCELAS_PATH . 'includes/class-wc-parcelas-deactivator.php';
	Woocommerce_Parcelas_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'woocommerce_parcelas_activate' );
register_deactivation_hook( __FILE__, 'woocommerce_parcelas_deactivate' );

/**
 * The core plugin class
 *
 * @since  1.2.5
 */
class WC_Parcelas {
	/**
	 * Instance of this class
	 *
	 * @var    object
	 */
	protected static $instance = null;

	/**
	 * Initialize plugin actions and filters
	 */
	private function __construct() {
		/**
		 * Check if WooCommerce is active
		 */
		if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
			/**
			 * Add plugin text domain
			 */
			add_action( 'init', array( $this, 'load_plugin_textdomain' ) );

			/**
			 * Add plugin action links
			 */
			add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), array( $this, 'plguin_action_links' ) );

			/**
			 * Add plugin Stylesheet and JavaScript, in admin
			 */
			add_action( 'admin_enqueue_scripts', array( $this, 'admin_stylesheet_and_javascript' ) );

			/**
			 * Include plugin files
			 */
			$this->includes();
		} else {
			add_action( 'admin_notices', array( $this, 'wc_missing_notice' ) );
		}
	}

	public static function get_instance() {
		if ( null == self::$instance ) {
			self::$instance = new self;
		}

		return self::$instance;
	}

	/**
	 * Call plugin text domain
	 */
	public function load_plugin_textdomain() {
		load_plugin_textdomain( 'wc-parcelas', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
	}

	/**
	 * Call plugin action links
	 *
	 * @param    $links
	 *
	 * @return    array    $links
	 */
	public function plguin_action_links( $links ) {
		$settings_url = admin_url( 'admin.php?page=fswp' );

		$links[] = '<a href="' . esc_url( $settings_url ) . '">' . __( 'Configurações', 'wc-parcelas' ) . '</a>';

		return $links;
	}

	/**
	 * Load plugin Stylesheet and JavaScript
	 *
	 * @param  string  $hook
	 */
	public function admin_stylesheet_and_javascript( $hook ) {
		if ( 'woocommerce_page_fswp' != $hook ) {
			return;
		}

		/**
		 * Call plugin Stylesheet
		 */
		wp_enqueue_style( WC_PARCELAS_SLUG . '-admin', WC_PARCELAS_URL . 'admin/css/wc-parcelas-admin.css', array(), WC_PARCELAS_VERSION );
		wp_enqueue_style( 'wp-color-picker' );

		/**
		 * Call plugin JavaScript
		 */
		wp_enqueue_script( WC_PARCELAS_SLUG . '-admin', WC_PARCELAS_URL . 'admin/js/wc-parcelas-admin.js', array( 'jquery', 'wp-color-picker' ), WC_PARCELAS_VERSION, false );
	}

	/**
	 * Includes
	 */
	private function includes() {
		include_once WC_PARCELAS_PATH . 'admin/class-wc-parcelas-settings.php';

		include_once WC_PARCELAS_PATH . 'admin/class-wc-parcelas-meta-box.php';

		include_once WC_PARCELAS_PATH . 'public/class-wc-parcelas-public.php';
	}

	/**
	 * WooCommerce missing notice
	 */
	public function wc_missing_notice() {
		$class   = 'error';
		$message = sprintf( __( 'Não é possível habilitar o plugin %s enquanto o %s não estiver instalado e ativado.', 'wc-parcelas' ), '<strong>' . WC_PARCELAS_NAME . '</strong>', '<a href="//wordpress.org/plugins/woocommerce/" target="_blank">WooCommerce</a>' );

		echo "<div class='$class'>";
		echo "<p>$message</p>";
		echo "</div>";
	}
}

add_action( 'plugins_loaded', array( 'WC_Parcelas', 'get_instance' ) );