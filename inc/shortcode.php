<?php
/**
 * Shortcode
 *
 * @package PostsFromCategory
 */

/**
 * Register shortcode.
 *
 * @since 1.0.0
 *
 * @param array $atts Shortcode attributes.
 * @return string Shortcode content.
 */
function pfc_init_shortcode( $atts ) {
	$atts = shortcode_atts( pfc_get_default_args(), $atts, 'pfc' );

	ob_start();

	pfc_render_posts_markup( $atts );

	return ob_get_clean();
}

add_shortcode( 'pfc', 'pfc_init_shortcode' );
