<?php

if ( ! defined( 'ABSPATH' ) ) { return; } // Exit if accessed directly



/**
 * Add AWV_Box_Widget widget.
 */
class AWV_Box_Widget extends WP_Widget {

	/**
	 * Register widget with WordPress.
	 */
	function __construct() {
		parent::__construct(
			'awv_widget', // Base ID
			esc_html__( 'AWV Widget', 'awv-plugin' ), // Name
			array( 'description' => esc_html__( 'Display AWV boxes for required post type', 'awv-plugin' ), ) // Args
		);
	}

	/**
	 * Front-end display of widget.
	 *
	 * @see WP_Widget::widget()
	 *
	 * @param array $args     Widget arguments.
	 * @param array $instance Saved values from database.
	 */
	public function widget( $args, $instance ) {
		if ( is_singular() ) {
			$post_type = get_post_type();
			if ( $post_type == $instance['widget_post_type'] ) {
				echo $args['before_widget'];
				if ( ! empty( $instance['title'] ) ) {
					echo $args['before_title'] . apply_filters( 'widget_title', $instance['title'] ) . $args['after_title'];
				}
				echo do_shortcode( $instance['widget_content'] );
				echo $args['after_widget'];
			}
		}
	}

	/**
	 * Back-end widget form.
	 *
	 * @see WP_Widget::form()
	 *
	 * @param array $instance Previously saved values from database.
	 */
	public function form( $instance ) {
		$post_types = awv_get_post_types();

		$title = ! empty( $instance['title'] ) ? $instance['title'] : esc_html__( 'AWV title', 'awv-plugin' );
		$widget_post_type = ! empty( $instance['widget_post_type'] ) ? $instance['widget_post_type'] : '';
		$widget_content   = ! empty( $instance['widget_content'] ) ? $instance['widget_content'] : '';
		?>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Title:', 'awv-plugin' ); ?></label> 
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'widget_post_type' ) ); ?>"><?php esc_html_e( 'Post type:', 'awv-plugin' ); ?></label>
			<select id="<?php echo esc_attr( $this->get_field_id( 'widget_post_type' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'widget_post_type' ) ); ?>">
				<?php foreach ( $post_types as $post_type_slug => $post_type_item ) { ?>
					<option value="<?php echo esc_attr( $post_type_slug ); ?>" <?php selected( $post_type_slug, $widget_post_type ); ?>><?php echo esc_html( $post_type_item->labels->singular_name ); ?></option>
				<?php } ?>
			</select>
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'widget_content' ) ); ?>"><?php esc_html_e( 'Content:', 'awv-plugin' ); ?></label>
			<textarea id="<?php echo esc_attr( $this->get_field_id( 'widget_content' ) ); ?>" class="widefat" rows="8" cols="20" name="<?php echo esc_attr( $this->get_field_name( 'widget_content' ) ); ?>"><?php echo esc_attr( $widget_content ); ?></textarea>
		</p>
		<?php 
	}

	/**
	 * Sanitize widget form values as they are saved.
	 *
	 * @see WP_Widget::update()
	 *
	 * @param array $new_instance Values just sent to be saved.
	 * @param array $old_instance Previously saved values from database.
	 *
	 * @return array Updated safe values to be saved.
	 */
	public function update( $new_instance, $old_instance ) {
		$instance = array();
		$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? sanitize_text_field( $new_instance['title'] ) : '';
		$instance['widget_post_type'] = ( ! empty( $new_instance['widget_post_type'] ) ) ? sanitize_text_field( $new_instance['widget_post_type'] ) : '';
		$instance['widget_content'] = ( ! empty( $new_instance['widget_content'] ) ) ? sanitize_text_field( $new_instance['widget_content'] ) : '';

		return $instance;
	}
}


// Register and load the widget
function awv_load_widgets() {
	register_widget( 'AWV_Box_Widget' );
}
add_action( 'widgets_init', 'awv_load_widgets' );