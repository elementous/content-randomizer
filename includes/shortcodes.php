<?php

/**
 * Random object shortcode.
 *
 * Display random object.
 *
 * @param $atts array
 *
*/
function elm_random_shortcode( $atts ) {
	global $randomizer;
	
	$atts = shortcode_atts(
		array(
			'type' => 'text',
			'category' => 'all'
		), $atts, 'elm_randomtext' );
	
	return $randomizer->get_random( $atts['type'], $atts['category'] );
}

add_shortcode( 'elm_random', 'elm_random_shortcode' );

/**
 * Slideshow.
 *
 * Display slideshow.
 *
 * @param $atts array
 *
*/
function elm_slideshow_shortcode( $atts ) {
	global $randomizer;
	
	$atts = shortcode_atts(
		array(
			'type' => 'text',
			'category' => 'all'
		), $atts, 'elm_randomtext' );
	
	return $randomizer->get_owl_slides( $atts['type'], $atts['category'] );
}

add_shortcode( 'elm_random', 'elm_random_shortcode' );
add_shortcode( 'elm_slideshow', 'elm_slideshow_shortcode' );

// Used for compatibility with older versions
add_shortcode( 'elm_randomtext', 'elm_random_shortcode' );

