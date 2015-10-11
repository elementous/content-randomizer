<?php

/**
 * Randomizer Widget.
 *
 */
class Randomizer_Widget extends WP_Widget {

	/**
	 * Register widget with WordPress
	 */
	function __construct() {
		parent::__construct(
			'randomizer_widget', // Base ID
			__('Randomizer', 'elm'), // Name
			array( 'description' => __( 'Display random content.', 'elm' ), ) // Args
		);
	}

	/**
	 * Front-end display of widget
	 *
	 * @see WP_Widget::widget()
	 *
	 * @param array $args     Widget arguments
	 * @param array $instance Saved values from database
	 */
	public function widget( $args, $instance ) {
		echo $args['before_widget'];
	
		$title = apply_filters( 'widget_title', $instance['title'] );
		if ( ! empty( $title ) )
			echo $args['before_title'] . $title . $args['after_title'];
			
		global $randomizer;
		
		echo $randomizer->get_random( $instance['type'], $instance['category'] );
		
		echo $args['after_widget'];
?>
<?php
	}

	/**
	 * Back-end widget form
	 *
	 * @see WP_Widget::form()
	 *
	 * @param array $instance Previously saved values from database
	 */
	public function form( $instance ) {
		if ( isset( $instance[ 'title' ] ) ) {
			$title = $instance[ 'title' ];
		} else {
			$title = __( 'Random Text', 'elm' );
		}
		?>
		<p>
		<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label> 
		<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
		</p>
		<p>
		<label for="<?php echo $this->get_field_id( 'type' ); ?>"><?php _e( 'Type:' ); ?></label><br />
		<select id="<?php echo $this->get_field_id( 'type' ); ?>" name="<?php echo $this->get_field_name( 'type' ); ?>" class="widefat">
		<?php
		$options = array( 'text' => __('Text', 'elm'), 'image' => __('Image', 'elm'), 'video' => __('Video', 'elm') );
				
		foreach ( $options as $key => $value ) :
			$selected = ( $instance[ 'type' ] == $key ) ? 'selected' : '';
		
			echo '<option value="'. $key .'" '. $selected .'>'. $value .'</option>' . "\r\n";
		endforeach;
		?>
		</select> 
		</p>
		<p>
		<label for="<?php echo $this->get_field_id( 'category' ); ?>"><?php _e( 'Category:' ); ?></label><br />
		<select id="<?php echo $this->get_field_id( 'category' ); ?>" name="<?php echo $this->get_field_name( 'category' ); ?>" class="widefat">
		<?php
		$options = get_terms( 'randomizer_category', array( 'hide_empty' => false ) );
		
		$selected = ( $instance[ 'category' ] == 'all' ) ? 'selected' : '';
		echo '<option value="all" '. $selected .'>'. __('All', 'elm') .'</option>' . "\r\n"; 
		
		if ( $options ) :
			foreach ( $options as $term ) :
				$selected = ( $instance[ 'category' ] == $term->term_id ) ? 'selected' : '';
			
				echo '<option value="'. $term->term_id .'" '. $selected .'>'. $term->name .'</option>' . "\r\n";
			endforeach;
		endif;
		?>
		</select> 
		</p>
		<?php 
	}

	/**
	 * Sanitize widget form values as they are saved
	 *
	 * @see WP_Widget::update()
	 *
	 * @param array $new_instance Values just sent to be saved
	 * @param array $old_instance Previously saved values from database
	 *
	 * @return array Updated safe values to be saved
	 */
	public function update( $new_instance, $old_instance ) {
		$instance = array();
		$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
		$instance['type'] = ( ! empty( $new_instance['type'] ) ) ? strip_tags( $new_instance['type'] ) : '';
		$instance['category'] = ( ! empty( $new_instance['category'] ) ) ? strip_tags( $new_instance['category'] ) : '';
		
		return $instance;
	}
}

/**
 * Randomizer Widget.
 *
 */
class RandomizerSlideshow_Widget extends WP_Widget {

	/**
	 * Register widget with WordPress
	 */
	function __construct() {
		parent::__construct(
			'randomizerslideshow_widget', // Base ID
			__('Randomizer Slideshow', 'elm'), // Name
			array( 'description' => __( 'Display content slideshow.', 'elm' ), ) // Args
		);
	}

	/**
	 * Front-end display of widget
	 *
	 * @see WP_Widget::widget()
	 *
	 * @param array $args     Widget arguments
	 * @param array $instance Saved values from database
	 */
	public function widget( $args, $instance ) {
		echo $args['before_widget'];
	
		$title = apply_filters( 'widget_title', $instance['title'] );
		if ( ! empty( $title ) )
			echo $args['before_title'] . $title . $args['after_title'];
			
		global $randomizer;
		
		echo $randomizer->get_owl_slides( $instance['type'], $instance['category'] );

		echo $args['after_widget'];
?>
<?php
	}

	/**
	 * Back-end widget form
	 *
	 * @see WP_Widget::form()
	 *
	 * @param array $instance Previously saved values from database
	 */
	public function form( $instance ) {
		if ( isset( $instance[ 'title' ] ) ) {
			$title = $instance[ 'title' ];
		} else {
			$title = __( 'Slideshow', 'elm' );
		}
		?>
		<p>
		<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label> 
		<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
		</p>
		<p>
		<label for="<?php echo $this->get_field_id( 'type' ); ?>"><?php _e( 'Type:' ); ?></label><br />
		<select id="<?php echo $this->get_field_id( 'type' ); ?>" name="<?php echo $this->get_field_name( 'type' ); ?>" class="widefat">
		<?php
		$options = array( 'text' => __('Text', 'elm'), 'image' => __('Image', 'elm'), 'video' => __('Video', 'elm') );
				
		foreach ( $options as $key => $value ) :
			$selected = ( $instance[ 'type' ] == $key ) ? 'selected' : '';
		
			echo '<option value="'. $key .'" '. $selected .'>'. $value .'</option>' . "\r\n";
		endforeach;
		?>
		</select> 
		</p>
		<p>
		<label for="<?php echo $this->get_field_id( 'category' ); ?>"><?php _e( 'Category:' ); ?></label><br />
		<select id="<?php echo $this->get_field_id( 'category' ); ?>" name="<?php echo $this->get_field_name( 'category' ); ?>" class="widefat">
		<?php
		$options = get_terms( 'randomizer_category', array( 'hide_empty' => false ) );
		
		$selected = ( $instance[ 'category' ] == 'all' ) ? 'selected' : '';
		echo '<option value="all" '. $selected .'>'. __('All', 'elm') .'</option>' . "\r\n"; 
		
		if ( $options ) :
			foreach ( $options as $term ) :
				$selected = ( $instance[ 'category' ] == $term->term_id ) ? 'selected' : '';
			
				echo '<option value="'. $term->term_id .'" '. $selected .'>'. $term->name .'</option>' . "\r\n";
			endforeach;
		endif;
		?>
		</select> 
		</p>
		<?php 
	}

	/**
	 * Sanitize widget form values as they are saved
	 *
	 * @see WP_Widget::update()
	 *
	 * @param array $new_instance Values just sent to be saved
	 * @param array $old_instance Previously saved values from database
	 *
	 * @return array Updated safe values to be saved
	 */
	public function update( $new_instance, $old_instance ) {
		$instance = array();
		$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
		$instance['type'] = ( ! empty( $new_instance['type'] ) ) ? strip_tags( $new_instance['type'] ) : '';
		$instance['category'] = ( ! empty( $new_instance['category'] ) ) ? strip_tags( $new_instance['category'] ) : '';
		
		return $instance;
	}
}

add_action('widgets_init',
     create_function('', 'return register_widget("Randomizer_Widget");')
);
add_action('widgets_init',
     create_function('', 'return register_widget("RandomizerSlideshow_Widget");')
);
