<?php
/**
 * Build functions that will modify frontend
 *
 * @since    1.0.0
 * @author   Filipe Seabra
 * @version  1.2.5
 */
class Woocommerce_Parcelas_Public extends Woocommerce_Parcelas_Settigns{
    public function __construct(){
        /**
         * Add installments to WooCommerce loop pages
         */
        add_action('woocommerce_after_shop_loop_item_title', array($this, 'fswp_in_loop'), 20);

        /**
         * Add installments to Woocommerce single product page
         */
        add_action('woocommerce_single_product_summary', array($this, 'fswp_in_single'), 10);

        /**
         * Add installments to variable product page that has variations with different prices
         */
        add_action('woocommerce_before_single_variation', array($this, 'output_installment_on_variation_change'));

        /**
         * Add JavaScript that will handle installments in variable product page
         */
        add_action('woocommerce_after_single_variation', array($this, 'js_after_single_variation'));
    }

    /**
     * Calculates the installments price.
     *
     * @return string with the installments price based in installments quantity.
     */
    public function fswp_calc(){
        $product = get_product();

        $fswp_settings = get_option($this->option_name);

        $fswp_prefixo = $fswp_settings['fswp_prefixo']; // Prefixo
        $fswp_parcelas = $fswp_settings['fswp_parcelas']; // Qtde de parcelas
        $fswp_sufixo = $fswp_settings['fswp_sufixo']; // Sufixo
        $fswp_valor_minimo = isset($fswp_settings['fswp_valor_minimo']) ? str_replace(',', '.', $fswp_settings['fswp_valor_minimo']) : 0; // Valor mínimo

        if($product->has_child()){
            if($product->get_variation_price('min') != $product->get_variation_price('max')){
                $fswp_prefixo = apply_filters('variable_product_with_different_prices_prefix', __('A partir de', 'woocommerce-parcelas'));
            }
        }

        if($product->get_price_including_tax()){
            $preco = $product->get_price_including_tax();

            if($preco <= $fswp_valor_minimo){
                $output = '';
            }
            elseif($preco > $fswp_valor_minimo){
                $preco_parcelado = $preco / $fswp_parcelas;
                $preco_parcelado_formatado = woocommerce_price($preco / $fswp_parcelas);

                if($preco_parcelado < $fswp_valor_minimo){
                    while($fswp_parcelas > 1 && $preco_parcelado < $fswp_valor_minimo){
                        $fswp_parcelas--;
                        $preco_parcelado = $preco / $fswp_parcelas;
                        $preco_parcelado_formatado = woocommerce_price($preco / $fswp_parcelas);
                    }

                    if($preco_parcelado > $fswp_valor_minimo){
                        $output  = "<div class='fswp_calc_wrapper'>";
                        $output .= "<p class='price fswp_calc'>".sprintf(__('<span class="fswp_prefixo">%s %sx de</span> ', 'woocommerce-parcelas'), $fswp_prefixo, $fswp_parcelas).$preco_parcelado_formatado." <span class='fswp_sufixo'>".$fswp_sufixo."</span></p>";
                        $output .= "</div>";                    
                    }
                    else{
                        $output = '';
                    }
                }
                else{
                    $output  = "<div class='fswp_calc_wrapper'>";
                    $output .= "<p class='price fswp_calc'>".sprintf(__('<span class="fswp_prefixo">%s %sx de </span>', 'woocommerce-parcelas'), $fswp_prefixo, $fswp_parcelas).$preco_parcelado_formatado." <span class='fswp_sufixo'>".$fswp_sufixo."</span></p>";
                    $output .= "</div>";
                }      
            }            

            return $output;
        }
    }

    /**
     * Displays the installments price on loop
     * 
     * @return string with the installments price based in installments quantity.
     */
    public function fswp_in_loop(){
        do_action('before_installments_in_loop');

        echo $this->fswp_calc();

        do_action('after_installments_in_loop');
    }

    /**
     * Displays the installments price on single product.
     *
     * @return string with the installments price based in installments quantity.
     */
    public function fswp_in_single(){
        do_action('before_installments_in_single');

        echo $this->fswp_calc();

        do_action('after_installments_in_single');
    }

    /**
    * @return space to attach correct installments price, when variable product has different prices
    */
    public function output_installment_on_variation_change(){
        echo "<div class='fswp_variable_installment'></div>";
    }

    /**
    * @return javascript to calculate and attach correct installments price, when variable product has different prices
    */
    public function js_after_single_variation(){
        $product = get_product();

        $fswp_settings = get_option($this->option_name);

        if($product->get_variation_price('min') != $product->get_variation_price('max')){
            $fswp_x_de = __('x de', 'woocommerce-parcelas');
            $sep_dec = get_option('woocommerce_price_decimal_sep');
            $sep_mil = get_option('woocommerce_price_thousand_sep');
            $cur_symbol = get_woocommerce_currency_symbol();            
    ?>
            <script type="text/javascript">
                // Below variables are being used on variable.js file
                var fswp_prefixo = <?php echo "'".$fswp_settings['fswp_prefixo']."'"; ?>;
                var fswp_parcelas = <?php echo $fswp_settings['fswp_parcelas']; ?>;
                var fswp_sufixo = <?php echo "'".$fswp_settings['fswp_sufixo']."'"; ?>;
                var fswp_valor_minimo = <?php echo $fswp_settings['fswp_valor_minimo']; ?>;                
                var fswp_x_de = <?php echo "'".$fswp_x_de."'"; ?>;
                var sep_dec = <?php echo "'".$sep_dec."'"; ?>;
                var sep_mil = <?php echo "'".$sep_mil."'"; ?>;
                var cur_symbol = <?php echo "'".$cur_symbol."'"; ?>
                
                function formatMoney(number, c, d, m){
                    c = isNaN(c = Math.abs(c)) ? 2 : c, 
                    d = d == undefined ? "." : d, 
                    m = m == undefined ? "," : m, 
                    s = number < 0 ? "-" : "", 
                    i = parseInt(number = Math.abs(+number || 0).toFixed(c)) + "", 
                    j = (j = i.length) > 3 ? j % 3 : 0;
                   return s + (j ? i.substr(0, j) + m : "") + i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + m) + (c ? d + Math.abs(number - i).toFixed(c).slice(2) : "");
                 };       
            </script>
    <?php
            wp_enqueue_script('woocommerce-parcelas-variable', WC_PARCELAS_URL.'public/js/woocommerce-parcelas-variable.js', WC_PARCELAS_VERSION, false, true);
        }
    }
}

new Woocommerce_Parcelas_Public();