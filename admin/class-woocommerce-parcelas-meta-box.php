<?php
/**
 * Add meta box within product page
 *
 * @since       1.2.7
 * @author      Filipe Seabra <eu@filipecsweb.com.br>
 * @version     1.2.7
 */
class Woocommerce_Parcelas_Meta_Box extends Woocommerce_Parcelas_Settings{
    /**
     * @var     $fswp_post_meta_key     array
     */
    public $fswp_post_meta_key = 'fswp_post_meta';

    /**
     * @var     $disable_in_cash_key     string
     */
    public $disable_in_cash_key = 'disable_in_cash';    

    public function __construct(){
        /**
         * Add WooCommperce Parcelas Meta Box
         */
        add_action('add_meta_boxes', array($this, 'add_fswp_product_meta_box'));

        /**
         * Save WooCommerce Parcelas Meta Box
         */
        add_action('save_post', array($this, 'save_fswp_product_meta_box'));        
    }

    public function add_fswp_product_meta_box(){
        add_meta_box(
            'fswp_product_meta_box', 
            __('Parcelamento e pagamento à vista', 'woocommerce-parcelas'), 
            array($this, 'fswp_product_meta_box_callback'), 
            'product', 
            'normal', 
            'low'
        );
    }    

    public function fswp_product_meta_box_callback($post){
        wp_nonce_field('fswp_product_meta_box_nonce_context', 'fswp_product_meta_box_nonce_name');
        
        if(null != get_post_meta($post->ID, $this->fswp_post_meta_key)){
            $fswp_post_meta_data = get_post_meta($post->ID, $this->fswp_post_meta_key, true);

            /**
             * @var     $disable_in_cash_value   string
             */
            $disable_in_cash_value = $fswp_post_meta_data[$this->disable_in_cash_key];
        }

        echo "<input type='hidden' name='$this->fswp_post_meta_key[$this->disable_in_cash_key]' value='0' />";
        echo "<input type='checkbox' id='$this->fswp_post_meta_key[$this->disable_in_cash_key]' name='$this->fswp_post_meta_key[$this->disable_in_cash_key]' value='1' " . checked(1, $disable_in_cash_value, false) . " />";
        echo "<label for='$this->fswp_post_meta_key[$this->disable_in_cash_key]'>" . __('Desativar preço à vista para este produto', 'woocommerce-parcelas') . "</label>";
    }

    public function save_fswp_product_meta_box($post_id){
        if(defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) 
            return;
         
        if(!isset($_POST['fswp_product_meta_box_nonce_name']) || !wp_verify_nonce($_POST['fswp_product_meta_box_nonce_name'], 'fswp_product_meta_box_nonce_context')) 
            return;
         
        if(!current_user_can('edit_posts')) 
            return;

        /**
         * Add or update post meta
         */
        if(null != get_post_meta($post_id, $this->fswp_post_meta_key)){
            update_post_meta($post_id, $this->fswp_post_meta_key, $_POST[$this->fswp_post_meta_key]);         
        }            
        else{
            if(isset($_POST[$this->fswp_post_meta_key])){
                add_post_meta($post_id, $this->fswp_post_meta_key, $_POST[$this->fswp_post_meta_key], true);
            }            
        }
    }
}

new Woocommerce_Parcelas_Meta_Box();