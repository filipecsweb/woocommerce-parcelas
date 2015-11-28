<?php
/**
 * This file contains CSS (already minified) that is updated through admin
 *
 * @author 		Filipe Seabra <eu@filipecsweb.com.br>
 * @since 		1.2.8
 * @version 	1.2.8 	
 */

$absolute_path = explode('wp-content', $_SERVER['SCRIPT_FILENAME']);
$wp_load = $absolute_path[0] . 'wp-load.php';
require_once($wp_load);

header('Content-type: text/css');
header('Cache-control: must-revalidate');

function verify($value){
	if(empty($value) || !$value || $value === 'inherit')
		return false;
	else
		return true;
}

$settings = new WooCommerce_Parcelas_Settings();

$options = get_option($settings->option_name);

$selectors_for_style = array(
	// Installments in loop
	'.products .fswp_installments_price .price.fswp_calc .fswp_installment_prefix',
	'.products .fswp_installments_price .price.fswp_calc .amount',
	'.products .fswp_installments_price .price.fswp_calc .fswp_installment_suffix',

	// Installments in single
	'.summary .fswp_installments_price .price.fswp_calc .fswp_installment_prefix',
	'.summary .fswp_installments_price .price.fswp_calc .amount',
	'.summary .fswp_installments_price .price.fswp_calc .fswp_installment_suffix',

	// In cash in loop
	'.products .fswp_in_cash_price .price.fswp_calc .fswp_in_cash_prefix',
	'.products .fswp_in_cash_price .price.fswp_calc .amount',
	'.products .fswp_in_cash_price .price.fswp_calc .fswp_in_cash_suffix',

	// In cash in single
	'.summary .fswp_in_cash_price .price.fswp_calc .fswp_in_cash_prefix',
	'.summary .fswp_in_cash_price .price.fswp_calc .amount',
	'.summary .fswp_in_cash_price .price.fswp_calc .fswp_in_cash_suffix'
);

$style_sections = $settings->get_fswp_style_sections();

foreach($style_sections as $k => $section){
	$id = explode('-', $section);

	foreach($settings->style as $prop => $data){
		$style[$k][$prop] = $options[$id[1].'_'.$prop];
	}
}

foreach($selectors_for_style as $k => $selector){
	echo $selector . "{";

	foreach($style[$k] as $prop => $value){
		if(verify($value))
			echo $prop . ":" . "$value" . ";";
	}

	echo "}";
}

$selectors_for_position = array(
	// Alignment in loop
	'.products .fswp_installments_price .price.fswp_calc,.products .fswp_in_cash_price .price.fswp_calc',

	// Alignment in single
	'.summary .fswp_installments_price .price.fswp_calc,.summary .fswp_in_cash_price .price.fswp_calc'
);

$alignments = array(
	isset($options['in_loop_alignment']) ? $options['in_loop_alignment'] : 'inherit',
	isset($options['in_single_alignment']) ? $options['in_single_alignment'] : 'inherit'
);

foreach($selectors_for_position as $k => $selector){
	echo $selector . "{";
	echo "text-align:" . $alignments[$k] . ";";
	echo "}";
}

exit;