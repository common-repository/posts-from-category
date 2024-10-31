<?php
/**
 * Plugin Name: Posts From Category
 * Version: 6.0.4
 * Plugin URI: https://maneshtimilsina.com/plugins/posts-from-category/
 * Description: Plugin to display posts from specific category. It comes with multiple layout option to display post list. Option to select category or exclude post is available wgich provide advance filter to your requirement. You can enable or disable posted date, thumbnail, read more button and more easily from widget.
 * Text Domain: posts-from-category
 * Author: Manesh Timilsina
 * Author URI: https://maneshtimilsina.com/
 * License: GNU/GPL http://www.gnu.org/copyleft/gpl.html
 *
 * @package PostsFromCategory
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'PFC_VERSION', '6.0.4' );
define( 'PFC_DIR', rtrim( plugin_dir_path( __FILE__ ), '/' ) );
define( 'PFC_URL', rtrim( plugin_dir_url( __FILE__ ), '/' ) );

if ( ! defined( 'WP_WELCOME_DIR' ) ) {
	define( 'WP_WELCOME_DIR', PFC_DIR . '/vendor/ernilambar/wp-welcome' );
}

if ( ! defined( 'WP_WELCOME_URL' ) ) {
	define( 'WP_WELCOME_URL', PFC_URL . '/vendor/ernilambar/wp-welcome' );
}

// Load autoload.
if ( file_exists( PFC_DIR . '/vendor/autoload.php' ) ) {
	require_once PFC_DIR . '/vendor/autoload.php';
	require_once PFC_DIR . '/vendor/ernilambar/wp-welcome/init.php';
}

// Init.
require_once PFC_DIR . '/inc/init.php';
