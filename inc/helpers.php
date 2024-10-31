<?php
/**
 * Helper functions
 *
 * @package PostsFromCategory
 */

/**
 * Render attributes.
 *
 * @since 1.0.0
 *
 * @param array $attributes Attributes.
 * @param bool  $display Whether to echo or not.
 * @return string Attributes string.
 */
function pfc_render_attr( $attributes, $display = true ) {
	$html = '';

	if ( empty( $attributes ) ) {
		return $html;
	}

	foreach ( $attributes as $name => $value ) {
		$esc_value = '';

		if ( 'class' === $name && is_array( $value ) ) {
			$value = join( ' ', array_unique( $value ) );
		}

		if ( false !== $value && 'href' === $name ) {
			$esc_value = esc_url( $value );
		} elseif ( false !== $value ) {
			$esc_value = esc_attr( $value );
		}

		if ( ! in_array( $name, array( 'class', 'id', 'title', 'style', 'name' ), true ) ) {
			$html .= false !== $value ? sprintf( ' %s="%s"', esc_html( $name ), $esc_value ) : esc_html( " {$name}" );
		} else {
			$html .= $value ? sprintf( ' %s="%s"', esc_html( $name ), $esc_value ) : '';
		}
	}

	if ( ! empty( $html ) && true === $display ) {
		echo $html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}

	return $html;
}

/**
 * Returns post excerpt.
 *
 * @since 1.0.0
 *
 * @param int     $length      Excerpt length in words.
 * @param WP_Post $post_object The post object.
 * @return string Post excerpt.
 */
function pfc_custom_limit_words( $length = 0, $post_object = null ) {
	global $post;

	if ( is_null( $post_object ) ) {
		$post_object = $post;
	}

	$length = absint( $length );
	if ( 0 === $length ) {
		return;
	}

	$source_content = $post_object->post_content;

	if ( ! empty( $post_object->post_excerpt ) ) {
		$source_content = $post_object->post_excerpt;
	}

	$source_content  = strip_shortcodes( $source_content );
	$trimmed_content = wp_trim_words( $source_content, $length, '&hellip;' );

	return $trimmed_content;
}

/**
 * Render select dropdown.
 *
 * @since 1.0.0
 *
 * @param array  $main_args     Main arguments.
 * @param string $callback      Callback method.
 * @param array  $callback_args Callback arguments.
 * @return string Rendered markup.
 */
function pfc_render_select_dropdown( $main_args, $callback, $callback_args = array() ) {
	$defaults = array(
		'id'          => '',
		'name'        => '',
		'class'       => '',
		'selected'    => 0,
		'echo'        => true,
		'add_default' => false,
	);

	$r = wp_parse_args( $main_args, $defaults );

	$output = '';

	$choices = array();

	if ( is_callable( $callback ) ) {
		$choices = call_user_func_array( $callback, $callback_args );
	}

	if ( ! empty( $choices ) || true === $r['add_default'] ) {
		$select_attrs = array(
			'id'   => $r['id'],
			'name' => $r['name'],
		);

		if ( ! empty( $r['class'] ) ) {
			$select_attrs['class'] = array( $r['class'] );
		}

		$output = '<select ' . pfc_render_attr( $select_attrs, false ) . '>' . "\n";

		if ( true === $r['add_default'] ) {
			$output .= '<option value="">' . esc_html__( 'Default', 'posts-from-category' ) . '</option>' . "\n";
		}

		if ( ! empty( $choices ) ) {
			foreach ( $choices as $key => $choice ) {
				$output .= '<option value="' . esc_attr( $key ) . '" ';
				$output .= selected( $r['selected'], $key, false );
				$output .= '>' . esc_html( $choice ) . '</option>\n';
			}
		}
		$output .= "</select>\n";
	}

	if ( $r['echo'] ) {
		echo $output; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}

	return $output;
}

/**
 * Returns post order by options.
 *
 * @since 1.0.0
 *
 * @return array Options array.
 */
function pfc_get_order_by_options() {
	$choices = array(
		'author'        => esc_html__( 'Author', 'posts-from-category' ),
		'title'         => esc_html__( 'Post Title', 'posts-from-category' ),
		'ID'            => esc_html__( 'Post ID', 'posts-from-category' ),
		'date'          => esc_html__( 'Date', 'posts-from-category' ),
		'menu_order'    => esc_html__( 'Menu Order', 'posts-from-category' ),
		'comment_count' => esc_html__( 'Number of Comments', 'posts-from-category' ),
		'rand'          => esc_html__( 'Random', 'posts-from-category' ),
	);

	return $choices;
}

/**
 * Returns post order options.
 *
 * @since 1.0.0
 *
 * @return array Options array.
 */
function pfc_get_order_type_options() {
	$choices = array(
		'ASC'  => esc_html__( 'Ascending', 'posts-from-category' ),
		'DESC' => esc_html__( 'Descending', 'posts-from-category' ),
	);

	return $choices;
}

/**
 * Returns image sizes options.
 *
 * @since 1.0.0
 *
 * @param bool  $add_disable True for adding No Image option.
 * @param array $allowed Allowed image size options.
 * @param bool  $show_dimension Enable or disable dimension.
 * @return array Image size options.
 */
function pfc_get_image_sizes_options( $add_disable = true, $allowed = array(), $show_dimension = true ) {
	global $_wp_additional_image_sizes;

	$choices = array();

	if ( true === $add_disable ) {
		$choices['disable'] = esc_html__( 'No Image', 'posts-from-category' );
	}

	$choices['thumbnail'] = esc_html__( 'Thumbnail', 'posts-from-category' );
	$choices['medium']    = esc_html__( 'Medium', 'posts-from-category' );
	$choices['large']     = esc_html__( 'Large', 'posts-from-category' );
	$choices['full']      = esc_html__( 'Full (original)', 'posts-from-category' );

	if ( true === $show_dimension ) {
		foreach ( array( 'thumbnail', 'medium', 'large' ) as $key => $_size ) {
			$choices[ $_size ] = $choices[ $_size ] . ' (' . get_option( $_size . '_size_w' ) . 'x' . get_option( $_size . '_size_h' ) . ')';
		}
	}

	if ( ! empty( $_wp_additional_image_sizes ) && is_array( $_wp_additional_image_sizes ) ) {
		foreach ( $_wp_additional_image_sizes as $key => $size ) {
			$choices[ $key ] = $key;
			if ( true === $show_dimension ) {
				$choices[ $key ] .= ' (' . $size['width'] . 'x' . $size['height'] . ')';
			}
		}
	}

	if ( ! empty( $allowed ) ) {
		foreach ( $choices as $key => $value ) {
			if ( ! in_array( $key, $allowed, true ) ) {
				unset( $choices[ $key ] );
			}
		}
	}

	return $choices;
}

/**
 * Return default PFC args.
 *
 * @since 1.0.0
 *
 * @return array Default arguments.
 */
function pfc_get_default_args() {
	return array(
		'layout'      => 'layout-one',
		'cat'         => '',
		'tag'         => '',
		'order_by'    => 'date',
		'order'       => 'DESC',
		'post_number' => 5,
		'exclude'     => '',
		'length'      => 10,
		'readmore'    => esc_html__( 'Read More', 'posts-from-category' ),
		'show_date'   => true,
		'show_image'  => true,
		'image_size'  => 'thumbnail',
	);
}

/**
 * Converts a comma- or space-separated list of scalar values to an array.
 *
 * @since 1.0.0
 *
 * @param array|string $values_list List of values.
 * @return array Array of values.
 */
function pfc_parse_list( $values_list ) {
	if ( ! is_array( $values_list ) ) {
		return preg_split( '/[\s,]+/', $values_list, -1, PREG_SPLIT_NO_EMPTY );
	}

	// Validate all entries of the list are scalar.
	$values_list = array_filter( $values_list, 'is_scalar' );

	return $values_list;
}

/**
 * Cleans up an array, comma- or space-separated list of IDs.
 *
 * @since 1.0.0
 *
 * @param array|string $values_list List of IDs.
 * @return int[] Sanitized array of IDs.
 */
function pfc_parse_id_list( $values_list ) {
	$values_list = pfc_parse_list( $values_list );

	return array_unique( array_map( 'absint', $values_list ) );
}

/**
 * Render PFC content.
 *
 * @since 1.0.0
 *
 * @param array $args Display arguments.
 */
function pfc_render_posts_markup( $args = array() ) {
	$args = wp_parse_args( $args, pfc_get_default_args() );

	$args['show_date']  = rest_sanitize_boolean( $args['show_date'] );
	$args['show_image'] = rest_sanitize_boolean( $args['show_image'] );
	$args['order']      = strtolower( $args['order'] );

	$exclude_ids = array();

	if ( 0 !== strlen( $args['exclude'] ) ) {
		$exclude_ids = pfc_parse_id_list( $args['exclude'] );
	}

	$query_args = array(
		'orderby'             => $args['order_by'],
		'order'               => $args['order'],
		'no_found_rows'       => true,
		'ignore_sticky_posts' => true,
		'post_status'         => 'publish',
		'posts_per_page'      => absint( $args['post_number'] ), // phpcs:ignore WordPress.WP.PostsPerPage
	);

	if ( count( $exclude_ids ) > 0 ) {
		$query_args['post__not_in'] = $exclude_ids;
	}

	if ( absint( $args['cat'] ) > 0 ) {
		$query_args['cat'] = absint( $args['cat'] );
	}

	if ( absint( $args['tag'] ) > 0 ) {
		$query_args['tag_id'] = absint( $args['tag'] );
	}

	$the_query = new WP_Query( $query_args );
	?>

	<?php if ( $the_query->have_posts() ) : ?>

		<div class="pfc-posts-main">

			<?php while ( $the_query->have_posts() ) : ?>
				<?php $the_query->the_post(); ?>

				<div class="pfc-post <?php echo esc_attr( $args['layout'] ); ?>">
					<?php if ( true === $args['show_image'] && has_post_thumbnail() ) : ?>
						<div class="news-thumb">
							<a href="<?php the_permalink(); ?>"><?php the_post_thumbnail( $args['image_size'] ); ?></a>
						</div>
					<?php endif; ?>

					<?php
					if ( true === $args['show_image'] && has_post_thumbnail() ) {
						$align_class = 'info-with-space';
					} else {
						$align_class = 'info-without-space';
					}
					?>

					<div class="news-text-wrap <?php echo esc_attr( $align_class ); ?>">
						<h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>

						<?php if ( true === $args['show_date'] ) : ?>
							<span class="posted-date"><?php echo esc_html( get_the_date() ); ?></span>
						<?php endif; ?>

						<?php
						if ( 0 !== absint( $args['length'] ) ) {
							$desc_content = pfc_custom_limit_words( absint( $args['length'] ) );

							if ( 0 !== strlen( $desc_content ) ) {
								echo wp_kses_post( wpautop( $desc_content ) );
							}
						}
						?>

						<?php if ( ! empty( $args['readmore'] ) ) : ?>
							<a href="<?php the_permalink(); ?>" class="read-more"><?php echo esc_html( $args['readmore'] ); ?></a>
						<?php endif; ?>

					</div><!-- .news-text-wrap -->

				</div><!-- .pfc-post -->

			<?php endwhile; ?>

			<?php wp_reset_postdata(); ?>

		</div><!-- .pfc-posts-main -->

	<?php endif; ?>
	<?php
}
