<?php
/**
 * Plugin Name: WooCommerce Parcelas
 * Plugin URI: //wordpress.org/plugins/woocommerce-parcelas/
 * Description: Adiciona quantidade de parcelas e o valor de cada parcela, nas páginas que listam todos os produtos e na página individual de cada produto.
 * Author: Filipe Seabra
 * Author URI: //filipecsweb.com.br/
 * Version: 1.2.8.3
 * License: GPLv2 or later
 * License URI: //www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain: woocommerce-parcelas
 * Domain Path: /languages
 */

if(!defined('ABSPATH')){
	exit;
}

define('WC_PARCELAS_PATH',	plugin_dir_path(__FILE__));
define('WC_PARCELAS_URL',	plugin_dir_url(__FILE__));
define('WC_PARCELAS_VERSION', '1.2.8.3');
define('WC_PARCELAS_NAME', 'WooCommerce Parcelas');
define('WC_PARCELAS_SLUG', 'woocommerce-parcelas');

/**
 * The code that runs during plugin activation
 * This action is documented in includes/class-woocommerce-parcelas-activator.php
 */
function woocommerce_parcelas_activate(){
	require_once WC_PARCELAS_PATH.'includes/class-woocommerce-parcelas-activator.php';
	Woocommerce_Parcelas_Activator::activate();
}

/**
 * The code that runs during plugin deactivation
 * This action is documented in includes/class-woocommerce-parcelas-deactivator.php
 */
function woocommerce_parcelas_deactivate(){
	require_once WC_PARCELAS_PATH.'includes/class-woocommerce-parcelas-deactivator.php';
	Woocommerce_Parcelas_Deactivator::deactivate();
}

register_activation_hook(__FILE__, 'woocommerce_parcelas_activate');
register_deactivation_hook(__FILE__, 'woocommerce_parcelas_deactivate');

if(!class_exists('WC_Parcelas')):

/**
 * The core plugin class
 *
 * @since 1.2.5
 * @author Filipe Seabra <eu@filipecsweb.com.br>
 */
class WC_Parcelas{
	/**
	 * Instance of this class
	 *
	 * @var 	object
	 */
	protected static $instance = null;

	/**
	 * Initialize plugin actions and filters
	 */
	private function __construct(){
		/**
		 * Check if WooCommerce is active
		 */
		if(in_array('woocommerce/woocommerce.php', apply_filters('active_plugins', get_option('active_plugins')))){
		    /**
			 * Add plugin text domain
			 */
			add_action('init', array($this, 'load_plugin_textdomain'));

			/**
			 * Add plugin action links
			 */
			add_filter('plugin_action_links_'.plugin_basename(__FILE__), array($this, 'plguin_action_links'));

			/**
			 * Add plugin Stylesheet and JavaScript, in admin
			 */
			add_action('admin_enqueue_scripts', array($this, 'admin_stylesheet_and_javascript'));

			/**
			 * Include plugin files
			 */
			$this->includes();
		}
		else{
			add_action('admin_notices', array($this, 'wc_missing_notice'));
		}		
	}

	public static function get_instance(){
		// If the single instance hasn't been set, set it now.
		if(null == self::$instance){
			self::$instance = new self;
		}

		return self::$instance;
	}

	/**
	 * Call plugin text domain
	 */
	public function load_plugin_textdomain(){
		load_plugin_textdomain('woocommerce-parcelas', false, dirname(plugin_basename(__FILE__)).'/languages/');
	}

	/**
	 * Call plugin action links
	 *
	 * @param 	$links
	 * @return 	array 	$links
	 */
	public function plguin_action_links($links){
		$settings_url = admin_url('admin.php?page=fswp');

		$links[] = '<a href="'.esc_url($settings_url).'">'.__('Configurações', 'woocommerce-parcelas').'</a>';

		return $links;
	}

	/**
	 * Load plugin Stylesheet and JavaScript
	 */
	public function admin_stylesheet_and_javascript($hook){
		if('woocommerce_page_fswp' != $hook){
			return;
		}

		/**
		 * Call plugin Stylesheet
		 */
		wp_enqueue_style(WC_PARCELAS_SLUG.'-admin', WC_PARCELAS_URL.'admin/css/woocommerce-parcelas-admin.css', array(), WC_PARCELAS_VERSION, 'all');		
		wp_enqueue_style('wp-color-picker');

		/**
		 * Call plugin JavaScript
		 */
		wp_enqueue_script(WC_PARCELAS_SLUG.'-admin', WC_PARCELAS_URL.'admin/js/woocommerce-parcelas-admin.js', array('jquery', 'wp-color-picker'), WC_PARCELAS_VERSION, false);	
	}

	/**
	 * Includes
	 */
	private function includes(){
		include_once WC_PARCELAS_PATH.'admin/class-woocommerce-parcelas-settings.php';

        include_once WC_PARCELAS_PATH.'admin/class-woocommerce-parcelas-meta-box.php';

		include_once WC_PARCELAS_PATH.'public/class-woocommerce-parcelas-public.php';
	}

	/**
	 * WooCommerce missing notice
	 */
	public function wc_missing_notice(){
		$class = 'error';
		$message = sprintf(__('Não é possível habilitar o plugin %s enquanto o %s não estiver instalado e ativado.', 'woocommerce-parcelas'), '<strong>'.WC_PARCELAS_NAME.'</strong>', '<a href="//wordpress.org/plugins/woocommerce/" target="_blank">WooCommerce</a>');

		echo "<div class='$class'>";
		echo 	"<p>$message</p>";
		echo "</div>";
	}
}

endif;

add_action('plugins_loaded', array('WC_Parcelas', 'get_instance'));