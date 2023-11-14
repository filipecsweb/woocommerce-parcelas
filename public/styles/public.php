<?php
/**
 * This file contains CSS (already minified) that is updated through admin
 */

$absolute_path = explode( 'wp-content', $_SERVER['SCRIPT_FILENAME'] );

require_once( $absolute_path[0] . 'wp-load.php' );

header( 'Content-type: text/css' );
header( 'Cache-control: must-revalidate' );

$selectors_for_style = [
	// Installments in loop
	'.loop.fswp_installments_price .price.fswp_calc .fswp_installment_prefix',
	'.loop.fswp_installments_price .price.fswp_calc .amount',
	'.loop.fswp_installments_price .price.fswp_calc .fswp_installment_suffix',

	// Installments in single
	'.single.fswp_installments_price .price.fswp_calc .fswp_installment_prefix',
	'.single.fswp_installments_price .price.fswp_calc .amount',
	'.single.fswp_installments_price .price.fswp_calc .fswp_installment_suffix',

	// In cash in loop
	'.loop.fswp_in_cash_price .price.fswp_calc .fswp_in_cash_prefix',
	'.loop.fswp_in_cash_price .price.fswp_calc .amount',
	'.loop.fswp_in_cash_price .price.fswp_calc .fswp_in_cash_suffix',

	// In cash in single
	'.single.fswp_in_cash_price .price.fswp_calc .fswp_in_cash_prefix',
	'.single.fswp_in_cash_price .price.fswp_calc .amount',
	'.single.fswp_in_cash_price .price.fswp_calc .fswp_in_cash_suffix'
];

$style_sections = wcParcelas()->settings->get_fswp_style_sections();

foreach ( $style_sections as $k => $section ) {
	$id = explode( '-', $section );

	foreach ( wcParcelas()->settings->style as $prop => $data ) {
		$style[ $k ][ $prop ] = wcParcelas()->settings->getOption( $id[1] . '_' . $prop );
	}
}

foreach ( $selectors_for_style as $k => $selector ) {
	echo $selector . "{";

	foreach ( $style[ $k ] as $prop => $value ) {
		if ( ! empty( $value ) && 'inherit' !== $value ) {
			echo $prop . ":" . "$value" . "!important;";
		}
	}

	echo "}";
}

$selectors_for_position = [
	// Alignment in loop
	'.loop.fswp_installments_price .price.fswp_calc,.loop.fswp_in_cash_price .price.fswp_calc',

	// Alignment in single
	'.single.fswp_installments_price .price.fswp_calc,.single.fswp_in_cash_price .price.fswp_calc'
];

$alignments = [
	wcParcelas()->settings->getOption( 'in_loop_alignment' ) ? wcParcelas()->settings->getOption( 'in_loop_alignment' ) : 'inherit',
	wcParcelas()->settings->getOption( 'in_single_alignment' ) ? wcParcelas()->settings->getOption( 'in_single_alignment' ) : 'inherit'
];

foreach ( $selectors_for_position as $k => $selector ) {
	echo $selector . "{";
	echo "text-align:" . $alignments[ $k ] . ";";
	echo "}";
}