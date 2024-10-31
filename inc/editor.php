<?php
/**
 * Editor customizations
 *
 * @package PostsFromCategory
 */

/**
 * Add custom button to editor.
 *
 * @since 1.0.0
 *
 * @param WP_Screen $current_screen Current WP_Screen object.
 */
function pfc_init_editor_button_add( $current_screen ) {
	if ( method_exists( $current_screen, 'is_block_editor' ) && true === $current_screen->is_block_editor() ) {
		return;
	}

	add_filter( 'mce_external_plugins', 'pfc_add_custom_editor_button' );
	add_filter( 'mce_buttons', 'pfc_register_custom_editor_button' );
}

add_action( 'current_screen', 'pfc_init_editor_button_add' );

/**
 * Add our plugin to the list of TinyMCE external plugins.
 *
 * @since 1.0.0
 *
 * @param array $plugin_array An array of external TinyMCE plugins.
 * @return array Modified plugins list.
 */
function pfc_add_custom_editor_button( $plugin_array ) {
	$plugin_array['pfcbutton'] = PFC_URL . '/assets/js/button.js';

	return $plugin_array;
}

/**
 * Add our plugin to the list of TinyMCE external plugins.
 *
 * @since 1.0.0
 *
 * @param array $buttons First-row list of buttons.
 * @return array Modified buttons list.
 */
function pfc_register_custom_editor_button( $buttons ) {
	array_push( $buttons, 'pfcbutton' );

	return $buttons;
}

/**
 * Generate custom TinyMCE variables which we need for shortcode.
 *
 * @since 1.0.0
 */
function pfc_tinymce_custom_vars() {
	$cat_args = array(
		'orderby'    => 'name',
		'hide_empty' => 1,
		'taxonomy'   => 'category',
	);

	$cat_count = 1;

	$cat_params[0] = array(
		'text'  => esc_html__( 'Select Category', 'posts-from-category' ),
		'value' => 0,
	);

	$post_cats = get_categories( $cat_args );

	foreach ( $post_cats as $cat ) {
		$cat_params[ $cat_count ]['text'] = $cat->name;

		$cat_params[ $cat_count ]['value'] = $cat->term_id;

		++$cat_count;
	}

	// For image sizes.
	$image_size = array();

	$all_sizes = pfc_get_image_sizes_options( false );

	if ( ! empty( $all_sizes ) ) {
		$img_count = 0;

		foreach ( $all_sizes as $key => $value ) {
			$image_size[ $img_count ]['text'] = $value;

			$image_size[ $img_count ]['value'] = $key;

			++$img_count;
		}
	}
	?>

	<script type="text/javascript">
		var tinyMCEObjectPFC =
		<?php
		echo wp_json_encode(
			array(
				'button_name'   => esc_html__( 'PFC', 'posts-from-category' ),
				'button_title'  => esc_html__( 'Posts From Category', 'posts-from-category' ),
				'post_cat_list' => $cat_params,
				'image_sizes'   => $image_size,
			)
		);
		?>
		;
	</script>
	<?php
}

add_action( 'after_wp_tiny_mce', 'pfc_tinymce_custom_vars' );
