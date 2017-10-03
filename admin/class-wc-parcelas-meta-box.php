<?php

/**
 * Add meta box within product page
 *
 * @since       1.2.7
 * @author      Filipe Seabra <filipecseabra@gmail.com>
 * @version     1.2.8
 */
class Woocommerce_Parcelas_Meta_Box extends Woocommerce_Parcelas_Settings {

    /**
     * @var     array $fswp_post_meta_key
     */
    public $fswp_post_meta_key = 'fswp_post_meta';

    /**
     * @var     string $disable_in_cash_key
     */
    public $disable_in_cash_key = 'disable_in_cash';

    /**
     * @var     string $disable_installments_key
     */
    public $disable_installments_key = 'disable_installments';

    public function __construct()
    {
        /**
         * Add WooCommperce Parcelas Meta Box
         */
        add_action('add_meta_boxes', array($this, 'add_fswp_product_meta_box'));

        /**
         * Save WooCommerce Parcelas Meta Box
         */
        add_action('save_post', array($this, 'save_fswp_product_meta_box'));
    }

    /**
     * Get fswp post meta value
     *
     * @param   string $value meta_value name
     *
     * @return  string  $fswp_post_meta_data[$value]    meta_value value
     */
    public function get_fswp_post_meta_data($value)
    {
        $post_id = get_the_ID();

        if (null != get_post_meta($post_id, $this->fswp_post_meta_key))
        {
            $fswp_post_meta_data = get_post_meta($post_id, $this->fswp_post_meta_key, true);

            return $fswp_post_meta_data[$value];
        }

        return false;
    }

    public function add_fswp_product_meta_box()
    {
        add_meta_box(
            'fswp_product_meta_box',
            __('Parcelamento e pagamento à vista', 'wc-parcelas'),
            array($this, 'fswp_product_meta_box_callback'),
            'product',
            'normal',
            'low',
            array(
                'values' => array(
                    $this->disable_in_cash_key      => __('Desativar preço à vista para este produto', 'wc-parcelas'),
                    $this->disable_installments_key => __('Desativar preço parcelado para este produto', 'wc-parcelas')
                )
            )
        );
    }

    public function fswp_product_meta_box_callback($post, $metabox)
    {
        wp_nonce_field('fswp_product_meta_box_nonce_context', 'fswp_product_meta_box_nonce_name');

        foreach ($metabox['args']['values'] as $value => $label)
        {
            echo "<p>";
            echo "<input type='hidden' name='$this->fswp_post_meta_key[$value]' value='0' />";
            echo "<input type='checkbox' id='$this->fswp_post_meta_key[$value]' name='$this->fswp_post_meta_key[$value]' value='1' " . checked(1, $this->get_fswp_post_meta_data($value), false) . " />";
            echo "<label for='$this->fswp_post_meta_key[$value]'>" . $label . "</label>";
            echo "</p>";
        }
    }

    public function save_fswp_product_meta_box($post_id)
    {
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)
            return;

        if (! isset($_POST['fswp_product_meta_box_nonce_name']) || ! wp_verify_nonce($_POST['fswp_product_meta_box_nonce_name'], 'fswp_product_meta_box_nonce_context'))
            return;

        if (! current_user_can('edit_posts'))
            return;

        /**
         * Add or update post meta
         */
        if (null != get_post_meta($post_id, $this->fswp_post_meta_key))
        {
            update_post_meta($post_id, $this->fswp_post_meta_key, $_POST[$this->fswp_post_meta_key]);
        } else
        {
            if (isset($_POST[$this->fswp_post_meta_key]))
            {
                add_post_meta($post_id, $this->fswp_post_meta_key, $_POST[$this->fswp_post_meta_key], true);
            }
        }
    }
}

new Woocommerce_Parcelas_Meta_Box();