<?php
/**
 * Do in cash payment calculation
 *
 * @author        Filipe Seabra <filipecseabra@gmail.com>
 * @since         1.2.6
 * @version       1.2.8.2
 */
if (! defined('ABSPATH'))
{
    exit;
}

$prefix = $this->settings['in_cash_prefix'];
$discount_value = $this->settings['in_cash_discount'];
$discount_type = $this->settings['in_cash_discount_type'];
$suffix = $this->settings['in_cash_suffix'];

if ('variable' == $product->get_type())
{
    /**
     * Deal with in cash value in single variable product
     * that has children with different prices
     */
    if ($product->get_variation_price('min') != $product->get_variation_price('max'))
    {
        if (is_product())
        {
            /**
             * Calculate and display in cash value for each child in variation product
             */
            add_action('woocommerce_before_single_variation', array($this, 'fswp_variable_in_cash_calculation'), 100);
        }
    }
}

/**
 * Get product final price
 *
 * @var     string $price
 */
$price = $product->get_price_including_tax();

$factor = str_replace(',', '.', $discount_value);

if ($discount_type == 0)
{ // %
    $factor = 1 - ($factor / 100);

    $discount_price = $price * $factor;
} elseif ($discount_type == 1)
{ // Fixed
    $discount_price = $price - $discount_value;
}

$in_cash_html = "<div class='fswp_in_cash_price $class'>";
$in_cash_html .= "<p class='price fswp_calc'>";
$in_cash_html .= "<span class='fswp_in_cash_prefix'>" . $prefix . "</span> " . wc_price($discount_price) . " <span class='fswp_in_cash_suffix'>" . $suffix . "</span>";
$in_cash_html .= "</p>";
$in_cash_html .= "</div>";

echo apply_filters('fswp_in_cash_calc_output', $in_cash_html, $prefix, $discount_price, $suffix);