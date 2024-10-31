<?php
/**
 * Hooks
 *
 * @package PostsFromCategory
 */

/**
 * Load plugin textdomain.
 *
 * @since 1.0.0
 */
function pfc_load_textdomain() {
	load_plugin_textdomain( 'posts-from-category' );
}

add_action( 'plugins_loaded', 'pfc_load_textdomain' );

/**
 * Load frontend assets.
 *
 * @since 1.0.0
 */
function pfc_load_frontend_assets() {
	wp_enqueue_style( 'pfc-style', PFC_URL . '/assets/css/pfc.css', array(), PFC_VERSION );
	wp_enqueue_script( 'pfc-custom', PFC_URL . '/assets/js/pfc.js', array( 'jquery' ), PFC_VERSION, true );
}

add_action( 'wp_enqueue_scripts', 'pfc_load_frontend_assets', 5 );

/**
 * Register widgets.
 *
 * @since 1.0.0
 */
function pfc_register_widgets() {
	register_widget( 'PFCWidget' );
}

add_action( 'widgets_init', 'pfc_register_widgets' );
