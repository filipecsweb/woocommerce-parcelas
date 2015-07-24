<?php
/**
 * Plugin Name: WooCommerce Parcelas
 * Plugin URI: //wordpress.org/plugins/woocommerce-parcelas/
 * Description: Adiciona quantidade de parcelas e o valor de cada parcela, nas páginas que listam todos os produtos e na página individual de cada produto.
 * Author: Filipe Seabra
 * Author URI: //www.filipecsweb.com.br/
 * Version: 1.2.5.2
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
define('WC_PARCELAS_VERSION', '1.2.5.2');
define('PLUGIN_NAME', 'woocommerce-parcelas');

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
		add_action('admin_enqueue_scripts', array($this, 'admin_stylesheets_and_javascript'));

		/**
		 * Include plugin files
		 */
		$this->includes();
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
		$settings_url = admin_url('options-general.php?page=fswp');

		$links[] = '<a href="'.esc_url($settings_url).'">'.__('Settings', 'woocommerce').'</a>';

		return $links;
	}

	/**
	 * Load plugin Stylesheet and JavaScript
	 */
	public function admin_stylesheets_and_javascript(){
		/**
		 * Call plugin Stylesheet
		 */
		wp_enqueue_style(PLUGIN_NAME.'-admin-css', WC_PARCELAS_URL.'admin/css/woocommerce-parcelas-admin.css', array(), WC_PARCELAS_VERSION, 'all');		

		/**
		 * Call plugin JavaScript
		 */
		wp_enqueue_script(PLUGIN_NAME.'-admin-js', WC_PARCELAS_URL.'admin/js/woocommerce-parcelas-admin.js', array('jquery'), WC_PARCELAS_VERSION, false);	
	}

	/**
	 * Includes
	 */
	private function includes(){
		include_once WC_PARCELAS_PATH.'admin/class-woocommerce-parcelas-settings.php';

		$fswp = new Woocommerce_Parcelas_Settigns();
		$fswp_settings = get_option($fswp->option_name);
		
		if(isset($fswp_settings['fswp_ativar']) && $fswp_settings['fswp_ativar']){
			include_once WC_PARCELAS_PATH.'public/class-woocommerce-parcelas-public.php';
		}		
	}
}

endif;

add_action('plugins_loaded', array('WC_Parcelas', 'get_instance'));