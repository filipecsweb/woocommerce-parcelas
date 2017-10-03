<?php
/**
 * Do installments calculation
 *
 * @author        Filipe Seabra <filipecseabra@gmail.com>
 * @since         1.2.6
 * @version       1.2.8.2
 */
if (! defined('ABSPATH'))
{
    exit;
}

$prefix = $this->settings['installment_prefix'];
$installments = $this->settings['installment_qty'];
$suffix = $this->settings['installment_suffix'];
$min_value = isset($this->settings['installment_minimum_value']) ? str_replace(',', '.', $this->settings['installment_minimum_value']) : 0;
$formatted_installments_price = '';

if ('variable' == $product->get_type())
{
    /**
     * Deal with installments in single variable product
     * that has children with different prices
     */
    if ($product->get_variation_price('min') != $product->get_variation_price('max'))
    {
        /**
         * Change prefix name for variable product
         *
         * @var     string $prefix
         */
        $prefix = apply_filters('fswp_variable_with_different_prices_prefix', __('A partir de', 'wc-parcelas'));
    }

    if (is_product())
    {
        /**
         * Calculate and display installmentes for each child in variation product
         */
        add_action('woocommerce_before_single_variation', array($this, 'fswp_variable_installment_calculation'), 99);
    }
} elseif ('grouped' == $product->get_type())
{
    /**
     * Change prefix name for grouped product
     *
     * @var     string $prefix
     */
    $prefix = apply_filters('fswp_grouped_prefix', __('A partir de', 'wc-parcelas'));
}

/**
 * Get product final price
 *
 * @var     string $price
 */
$price = $product->get_price_including_tax();

if ($price <= $min_value)
{
    $installments_html = '';
} elseif ($price > $min_value)
{
    $installments_price = $price / $installments;
    $formatted_installments_price = wc_price($price / $installments);

    if ($installments_price < $min_value)
    {
        while ($installments > 2 && $installments_price < $min_value)
        {
            $installments--;
            $installments_price = $price / $installments;
            $formatted_installments_price = wc_price($price / $installments);
        }

        if ($installments_price >= $min_value)
        {
            $installments_html = "<div class='fswp_installments_price $class'>";
            $installments_html .= "<p class='price fswp_calc'>" . sprintf(__('<span class="fswp_installment_prefix">%s %sx de</span> ', 'wc-parcelas'), $prefix, $installments) . $formatted_installments_price . " <span class='fswp_installment_suffix'>" . $suffix . "</span></p>";
            $installments_html .= "</div>";
        } else
        {
            $installments_html = '';
        }
    } else
    {
        $installments_html = "<div class='fswp_installments_price $class'>";
        $installments_html .= "<p class='price fswp_calc'>" . sprintf(__('<span class="fswp_installment_prefix">%s %sx de </span>', 'wc-parcelas'), $prefix, $installments) . $formatted_installments_price . " <span class='fswp_installment_suffix'>" . $suffix . "</span></p>";
        $installments_html .= "</div>";
    }
}

echo apply_filters('fswp_installments_calc_output', $installments_html, $prefix, $installments, $formatted_installments_price, $suffix);