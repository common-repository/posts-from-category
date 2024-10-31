<?php
/**
 * Admin page
 *
 * @package PostsFromCategory
 */

use Nilambar\Welcome\Welcome;
use Nilambar\AdminNotice\Notice;

/**
 * Register admin page.
 *
 * @since 1.0.0
 */
function pfc_register_welcome_page() {
	$obj = new Welcome( 'plugin', 'posts-from-category' );

	$obj->set_page(
		array(
			'page_title'     => esc_html__( 'Posts From Category', 'posts-from-category' ),
			/* translators: %s: version. */
			'page_subtitle'  => sprintf( esc_html__( 'Version: %s', 'posts-from-category' ), PFC_VERSION ),
			'menu_title'     => esc_html__( 'Posts From Category', 'posts-from-category' ),
			'menu_slug'      => 'pfc-options',
			'capability'     => 'manage_options',
			'menu_icon'      => 'dashicons-editor-ul',
			'top_level_menu' => true,
		)
	);

	$obj->set_quick_links(
		array(
			array(
				'text' => 'Support',
				'url'  => 'https://wordpress.org/support/plugin/posts-from-category/',
				'type' => 'primary',
			),
			array(
				'text' => 'Leave a Review',
				'url'  => 'https://wordpress.org/support/plugin/posts-from-category/reviews/#new-post',
				'type' => 'secondary',
			),
			array(
				'text' => 'Buy Me a Coffee',
				'url'  => 'https://www.buymeacoffee.com/maneshtimilsina',
				'type' => 'secondary',
			),
		)
	);

	$obj->set_admin_notice(
		array(
			'screens' => array( 'dashboard' ),
		)
	);

	$obj->add_tab(
		array(
			'id'    => 'welcome',
			'title' => 'Welcome',
			'type'  => 'grid',
			'items' => array(
				array(
					'title'       => 'How to Use (Using Widget)',
					'icon'        => 'dashicons dashicons-welcome-widgets-menus',
					'description' => '<ol>
          <li>Go to Appearance >> Widgets</li>
          <li>Find MT: Posts From Category widget</li>
          <li>Add the widget to the sidebar you want to use</li>
          <li>Fill in the desired fields and we are good to go</li>
          </ol>',
				),
				array(
					'title'       => 'How to Use (Using Shortcode)',
					'icon'        => 'dashicons dashicons-admin-generic',
					'description' => '<code>[pfc layout="layout-one" cat="0" order_by="date" order="DESC" post_number="5" length="10" readmore="Read More" show_date="true" show_image="true" image_size="full"]</code>',
					'button_text' => 'Click here to see shortcode parameters',
					'button_url'  => 'https://wordpress.org/plugins/posts-from-category/',
					'is_new_tab'  => true,
				),
			),
		)
	);

	$obj->set_sidebar(
		array(
			'render_callback' => 'pfc_render_welcome_page_sidebar',
		)
	);

	$obj->run();
}

add_action( 'wp_welcome_init', 'pfc_register_welcome_page' );

/**
 * Render welcome sidebar.
 *
 * @since 6.0.0
 *
 * @param Welcome $welcome_object Instance of Welcome.
 */
function pfc_render_welcome_page_sidebar( $welcome_object ) {
	$welcome_object->render_sidebar_box(
		array(
			'title'        => 'Leave a Review',
			'content'      => $welcome_object->get_stars() . sprintf( 'Are you enjoying %1$s? We would appreciate a review.', $welcome_object->get_name() ),
			'button_text'  => 'Submit Review',
			'button_url'   => 'https://wordpress.org/support/plugin/posts-from-category/reviews/#new-post',
			'button_class' => 'button',
		),
		$welcome_object
	);

	$welcome_object->render_sidebar_box(
		array(
			'title'        => 'Buy Me a Coffee',
			'content'      => 'Would you like to support the advancement of this plugin?',
			'button_text'  => 'Buy Me a Coffee',
			'button_url'   => 'https://www.buymeacoffee.com/maneshtimilsina',
			'button_class' => 'button',
		),
		$welcome_object
	);
}

/**
 * Register admin notice.
 *
 * @since 6.0.1
 */
function pfc_register_admin_notice() {
	Notice::init(
		array(
			'slug' => 'posts-from-category',
			'name' => esc_html__( 'Posts From Category', 'posts-from-category' ),
		)
	);
}

add_action( 'admin_init', 'pfc_register_admin_notice' );
