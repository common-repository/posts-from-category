<?php
/**
 * Widget
 *
 * @package PostsFromCategory
 */

/**
 * PFC widget class.
 *
 * @since 1.0.0
 */
class PFCWidget extends WP_Widget {

	/**
	 * Constructor.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		global $control_ops;

		$widget_ops = array(
			'classname'   => 'pfc-widget',
			'description' => esc_html__( 'Display posts from selected category', 'posts-from-category' ),
		);

		parent::__construct( 'PFCWidget', esc_html__( 'MT: Posts From Category', 'posts-from-category' ), $widget_ops, $control_ops );

		$this->alt_option_name = 'widget_pfc';
	}

	/**
	 * Echo the widget content.
	 *
	 * @since 1.0.0
	 *
	 * @param array $args     Display arguments.
	 * @param array $instance Widget instance.
	 */
	public function widget( $args, $instance ) {
		$title         = apply_filters( 'widget_title', empty( $instance['title'] ) ? '' : $instance['title'], $instance, $this->id_base );
		$layout        = ! empty( $instance['layout'] ) ? $instance['layout'] : '';
		$post_cat      = ! empty( $instance['post_cat'] ) ? absint( $instance['post_cat'] ) : 0;
		$post_tag      = ! empty( $instance['post_tag'] ) ? absint( $instance['post_tag'] ) : 0;
		$post_order_by = ! empty( $instance['post_order_by'] ) ? $instance['post_order_by'] : '';
		$post_order    = ! empty( $instance['post_order'] ) ? $instance['post_order'] : '';
		$post_num      = ! empty( $instance['post_num'] ) ? absint( $instance['post_num'] ) : 5;
		$post_exclude  = ! empty( $instance['post_exclude'] ) ? $instance['post_exclude'] : '';
		$post_length   = ! empty( $instance['post_length'] ) ? absint( $instance['post_length'] ) : '';
		$readmore_text = ! empty( $instance['readmore_text'] ) ? $instance['readmore_text'] : '';
		$date          = ! empty( $instance['date'] ) ? (bool) $instance['date'] : false;
		$thumbnail     = ! empty( $instance['thumbnail'] ) ? (bool) $instance['thumbnail'] : false;
		$post_thumbs   = ! empty( $instance['post_thumbs'] ) ? $instance['post_thumbs'] : '';

		echo $args['before_widget']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		?>

		<div class="pfc-posts-wrap">
			<?php
			if ( ! empty( $title ) ) {
				echo $args['before_title'] . esc_html( $title ) . $args['after_title']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			}
			?>
			<div class="pfc-posts-inner">
				<?php
				$display_args = array(
					'layout'      => $layout,
					'cat'         => $post_cat,
					'tag'         => $post_tag,
					'order_by'    => $post_order_by,
					'order'       => $post_order,
					'post_number' => $post_num,
					'length'      => $post_length,
					'readmore'    => $readmore_text,
					'show_date'   => $date,
					'show_image'  => $thumbnail,
					'image_size'  => $post_thumbs,
					'exclude'     => $post_exclude,
				);

				pfc_render_posts_markup( $display_args );
				?>
			</div><!-- .pfc-posts-inner -->
		</div><!-- .pfc-posts-wrap -->
		<?php
		echo $args['after_widget']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}

	/**
	 * Update widget instance.
	 *
	 * @since 1.0.0
	 *
	 * @param array $new_instance New settings for this instance.
	 * @param array $old_instance Old settings for this instance.
	 * @return array Settings to save or bool false to cancel saving.
	 */
	public function update( $new_instance, $old_instance ) {
		$instance = $old_instance;

		$instance['title']         = sanitize_text_field( $new_instance['title'] );
		$instance['layout']        = sanitize_text_field( $new_instance['layout'] );
		$instance['post_cat']      = absint( $new_instance['post_cat'] );
		$instance['post_tag']      = absint( $new_instance['post_tag'] );
		$instance['post_order_by'] = sanitize_text_field( $new_instance['post_order_by'] );
		$instance['post_order']    = sanitize_text_field( $new_instance['post_order'] );
		$instance['post_num']      = absint( $new_instance['post_num'] );
		$instance['post_exclude']  = sanitize_text_field( $new_instance['post_exclude'] );
		$instance['post_length']   = absint( $new_instance['post_length'] );
		$instance['readmore_text'] = sanitize_text_field( $new_instance['readmore_text'] );
		$instance['date']          = (bool) $new_instance['date'] ? true : false;
		$instance['thumbnail']     = (bool) $new_instance['thumbnail'] ? true : false;
		$instance['post_thumbs']   = sanitize_text_field( $new_instance['post_thumbs'] );

		return $instance;
	}

	/**
	 * Output the settings update form.
	 *
	 * @since 1.0.0
	 *
	 * @param array $instance Current settings.
	 */
	public function form( $instance ) {
		$instance = wp_parse_args(
			(array) $instance,
			array(
				'title'         => '',
				'layout'        => 'layout-one',
				'post_cat'      => '',
				'post_tag'      => '',
				'post_order_by' => 'date',
				'post_order'    => 'DESC',
				'post_num'      => 5,
				'post_exclude'  => '',
				'post_length'   => 10,
				'readmore_text' => esc_html__( 'Read More', 'posts-from-category' ),
				'date'          => true,
				'thumbnail'     => true,
				'post_thumbs'   => 'thumbnail',
			)
		);
		?>

		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Title:', 'posts-from-category' ); ?></label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $instance['title'] ); ?>" />
		</p>

		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'layout' ) ); ?>"><?php esc_html_e( 'Select Layout:', 'posts-from-category' ); ?></label>
		</p>

		<p>
			<input type="radio" <?php checked( $instance['layout'], 'layout-one' ); ?> id="<?php echo esc_attr( $this->get_field_id( 'layout-one' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'layout' ) ); ?>" value="layout-one" />
			<label for="<?php echo esc_attr( $this->get_field_id( 'layout-one' ) ); ?>"><img src="<?php echo esc_url( PFC_URL . '/assets/images/layout-one.png' ); ?>" class="layout-one"></label>

			<input type="radio" <?php checked( $instance['layout'], 'layout-two' ); ?> id="<?php echo esc_attr( $this->get_field_id( 'layout-two' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'layout' ) ); ?>" value="layout-two" />
			<label for="<?php echo esc_attr( $this->get_field_id( 'layout-two' ) ); ?>"><img src="<?php echo esc_url( PFC_URL . '/assets/images/layout-two.png' ); ?>" class="layout-two"></label>
		</p>

		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'post_cat' ) ); ?>"><?php esc_html_e( 'Select Category:', 'posts-from-category' ); ?></label>
			<?php
				$cat_args = array(
					'orderby'         => 'name',
					'hide_empty'      => 1,
					'class'           => 'widefat',
					'taxonomy'        => 'category',
					'name'            => $this->get_field_name( 'post_cat' ),
					'id'              => $this->get_field_id( 'post_cat' ),
					'selected'        => absint( $instance['post_cat'] ),
					'show_option_all' => esc_html__( 'All Categories', 'posts-from-category' ),
				);

				wp_dropdown_categories( $cat_args );
				?>
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'post_tag' ) ); ?>"><?php esc_html_e( 'Select Tag:', 'posts-from-category' ); ?></label>
			<?php
				$cat_args = array(
					'orderby'         => 'name',
					'hide_empty'      => 1,
					'class'           => 'widefat',
					'taxonomy'        => 'post_tag',
					'name'            => $this->get_field_name( 'post_tag' ),
					'id'              => $this->get_field_id( 'post_tag' ),
					'selected'        => absint( $instance['post_tag'] ),
					'show_option_all' => esc_html__( 'All Tags', 'posts-from-category' ),
				);

				wp_dropdown_categories( $cat_args );
				?>
		</p>

		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'post_order_by' ) ); ?>"><?php esc_html_e( 'Order By:', 'posts-from-category' ); ?></label>
			<?php
				$dropdown_args = array(
					'id'       => $this->get_field_id( 'post_order_by' ),
					'name'     => $this->get_field_name( 'post_order_by' ),
					'selected' => $instance['post_order_by'],
					'class'    => 'widefat',
				);

				pfc_render_select_dropdown( $dropdown_args, 'pfc_get_order_by_options' );
				?>
		</p>

		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'post_order' ) ); ?>"><?php esc_html_e( 'Order:', 'posts-from-category' ); ?></label>
			<?php
				$dropdown_args = array(
					'id'       => $this->get_field_id( 'post_order' ),
					'name'     => $this->get_field_name( 'post_order' ),
					'selected' => $instance['post_order'],
					'class'    => 'widefat',
				);

				pfc_render_select_dropdown( $dropdown_args, 'pfc_get_order_type_options' );
				?>
		</p>

		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'post_num' ) ); ?>"><?php esc_html_e( 'Number of Posts:', 'posts-from-category' ); ?></label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'post_num' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'post_num' ) ); ?>" type="number" value="<?php echo absint( $instance['post_num'] ); ?>" />
		</p>

		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'post_exclude' ) ); ?>"><?php esc_html_e( 'Exclude Posts:', 'posts-from-category' ); ?></label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'post_exclude' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'post_exclude' ) ); ?>" type="text" value="<?php echo esc_attr( $instance['post_exclude'] ); ?>" />
			<small><?php esc_html_e( 'Enter post id separated with comma to exclude multiple posts', 'posts-from-category' ); ?></small>
		</p>

		<p>
			<label for="<?php echo esc_attr( $this->get_field_name( 'post_length' ) ); ?>"><?php esc_html_e( 'Excerpt Length:', 'posts-from-category' ); ?></label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'post_length' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'post_length' ) ); ?>" type="number" value="<?php echo absint( $instance['post_length'] ); ?>" />
			<small><?php esc_html_e( 'Use 0 to hide Excerpt/Desc', 'posts-from-category' ); ?></small>
		</p>

		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'readmore_text' ) ); ?>"><?php esc_html_e( 'Read More Text:', 'posts-from-category' ); ?></label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'readmore_text' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'readmore_text' ) ); ?>" type="text" value="<?php echo esc_attr( $instance['readmore_text'] ); ?>" />
			<small><?php esc_html_e( 'Leave this field empty to hide read more button', 'posts-from-category' ); ?></small>
		</p>

		<p>
			<input class="checkbox" type="checkbox" <?php checked( $instance['date'] ); ?> id="<?php echo esc_attr( $this->get_field_id( 'date' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'date' ) ); ?>" />
			<label for="<?php echo esc_attr( $this->get_field_id( 'date' ) ); ?>"><?php esc_html_e( 'Show Posted Date', 'posts-from-category' ); ?></label>
		</p>

		<p>
			<input class="checkbox" type="checkbox" <?php checked( $instance['thumbnail'] ); ?> id="<?php echo esc_attr( $this->get_field_id( 'thumbnail' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'thumbnail' ) ); ?>" />
			<label for="<?php echo esc_attr( $this->get_field_id( 'thumbnail' ) ); ?>"><?php esc_html_e( 'Display/Select Thumbnail', 'posts-from-category' ); ?></label>
		</p>

		<p>
			<?php
				$dropdown_args = array(
					'id'       => $this->get_field_id( 'post_thumbs' ),
					'name'     => $this->get_field_name( 'post_thumbs' ),
					'selected' => $instance['post_thumbs'],
					'class'    => 'widefat',
				);

				pfc_render_select_dropdown( $dropdown_args, 'pfc_get_image_sizes_options', array( 'add_disable' => false ) );
				?>
		</p>

		<?php
	}
}
