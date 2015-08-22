<?php

/**
 * Random text shortcode.
 * Display random text or featured image.
 * @param $atts array
 *
*/
function elm_randomtext_shortcode( $atts ) {
	global $random_text;
	
	$atts = shortcode_atts(
		array(
			'type' => 'text',
		), $atts, 'elm_randomtext' );
	
	return $random_text->get_random_text( $atts['type'] );
}

add_shortcode( 'elm_randomtext', 'elm_randomtext_shortcode' );