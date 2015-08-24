<?php
/**
 * Build functions that will modify frontend
 *
 * @since    1.0.0
 * @author   Filipe Seabra
 * @version  1.2.6
 */
class Woocommerce_Parcelas_Public extends Woocommerce_Parcelas_Settigns{
    /**
     * Is installments option enabled?
     *
     * @var     string  $allow_installments
     */
    public $allow_installments;

    /**
     * Is in cash option enabled?
     *
     * @var     string  $allow_in_cash
     */
    public $allow_in_cash;

    public function __construct(){
        /**
         * Keep array of settings
         */
        $this->settings = get_option($this->option_name);

        $this->allow_installments = $this->settings['enable_installments'];

        $this->allow_in_cash = $this->settings['enable_in_cash'];

        if($this->allow_installments != 1 && $this->allow_in_cash != 1){
            return;
        }
        else if($this->allow_installments || $this->allow_installments){
            add_action('woocommerce_before_single_variation', array($this, 'fswp_variable_product_js_variables'), 98);
        }

        /**
         * Add installments to WooCommerce loop pages
         */
        add_action($this->settings['fswp_in_loop_position'], array($this, 'fswp_in_loop'), $this->settings['fswp_in_loop_position_level']);

        /**
         * Add installments to Woocommerce single product page
         */
        add_action($this->settings['fswp_in_single_position'], array($this, 'fswp_in_single'), $this->settings['fswp_in_single_position_level']);
    }    

    /**
     * Displays the installments price on loop.
     * 
     * @return void.
     */
    public function fswp_in_loop(){
        do_action('before_installments_in_loop');

        /**
         * Get product data
         *
         * @var     object  $product
         */
        $product = get_product();

        if(!$product->get_price_including_tax()){
            return;
        }        

        if($this->allow_installments){
            include 'installments-calc.php';
        }        

        if($this->allow_in_cash){
            include 'in-cash-calc.php';
        }

        do_action('after_installments_in_loop');
    }

    /**
     * Display the installments price on single product.
     *
     * @return void.
     */
    public function fswp_in_single(){        
        do_action('before_installments_in_single');

        /**
         * Get product data
         *
         * @var     object  $product
         */
        $product = get_product();

        if(!$product->get_price_including_tax()){
            return;
        }

        /**
         * If installment option is enabled in backend
         */
        if($this->allow_installments){
            include 'installments-calc.php'; 
        }        

        /**
         * If in cash option is enabled in backend
         */
        if($this->allow_in_cash){
            include 'in-cash-calc.php';                                       
        }

        do_action('after_installments_in_single');
    }

    /**
     *
     */
    public function fswp_variable_product_js_variables(){
?>
        <script type="text/javascript">
            var x_de = <?php echo "'".__('x de', 'woocommerce-parcelas')."'"; ?>;
            var dec_sep = <?php echo "'".get_option('woocommerce_price_decimal_sep')."'"; ?>;
            var th_sep = <?php echo "'".get_option('woocommerce_price_thousand_sep')."'"; ?>;
            var cur_symbol = <?php echo "'".get_woocommerce_currency_symbol()."'"; ?>
            
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
    }

    /**
     * Calculate and display installments when child products have different prices
     *
     * @return  void
     */
    public function fswp_variable_installment_calculation(){
?>
        <script type="text/javascript">
            var installment_prefix = <?php echo "'".$this->settings['installment_prefix']."'"; ?>;
            var installment_qty = <?php echo $this->settings['installment_qty']; ?>;
            var installment_suffix = <?php echo "'".$this->settings['installment_suffix']."'"; ?>;
            var installment_minimum_value = <?php echo isset($this->settings['installment_minimum_value']) ? str_replace(',', '.', $this->settings['installment_minimum_value']) : 0; ?>;                                    
        </script>

        <div class='fswp_variable_installment_calculation'></div>

        <script type="text/javascript" src="<?php echo WC_PARCELAS_URL.'public/js/variable-installment-calculation.js' ?>"></script>
<?php
    }

    /**
     * 
     */
    public function fswp_variable_in_cash_calculation(){
?>
        <script type="text/javascript">
            var in_cash_prefix = <?php echo "'".$this->settings['in_cash_prefix']."'"; ?>;
            var in_cash_discount = <?php echo "'".$this->settings['in_cash_discount']."'"; ?>;
            var in_cash_discount_type = <?php echo $this->settings['in_cash_discount_type']; ?>;
            var in_cash_suffix = <?php echo "'".$this->settings['in_cash_suffix']."'"; ?>;
        </script>        

        <div class='fswp_variable_in_cash_calculation'></div>

        <script type="text/javascript" src="<?php echo WC_PARCELAS_URL.'public/js/variable-in-cash-calculation.js' ?>"></script>
<?php
    }
}

new Woocommerce_Parcelas_Public();