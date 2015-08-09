<?php

class RandomText {

	function __construct() {
		add_action( 'init', array( $this, 'init' ) );
		
		add_action( 'add_meta_boxes', array( $this, 'date_range' ) );
		add_action( 'save_post', array( $this, 'save_post' ) );
		
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_css_js' ) ); 
		add_action( 'admin_footer', array( $this, 'admin_footer' ) );
	}
	
	/**
	 * Init actions and filters.
	 *
	 */
	function init() {
	
		$this->register_custom_posts();
	}
	
	/**
	 * Load CSS and JS for admin.
	 *
	 */
	function admin_css_js() {
		global $post;
	
		if ( get_post_type( $post ) !== 'elm_texts' )
			return; 
			
		wp_enqueue_script( 'jquery' );
		wp_enqueue_script( 'jquery-ui-core' );
		wp_enqueue_script( 'jquery-ui-datepicker' );
		
		wp_enqueue_style('jquery-style', 'http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.2/themes/smoothness/jquery-ui.css');
	}
	
	/**
	 * Admin footer JS.
	 *
	 */
	function admin_footer() {
		global $post;
	
		if ( get_post_type( $post ) !== 'elm_texts' )
			return; 
?>
	<script type="text/javascript">
	jQuery(document).ready(function() {
		jQuery('#date_from').datepicker({
			dateFormat : 'dd-mm-yy'
		});
		
		jQuery('#date_to').datepicker({
			dateFormat : 'dd-mm-yy'
		});
	});
	</script>
<?php
	}
	
	/**
	 * Adds a box to the random texts post type for date range.
	 */
	function date_range() {
		add_meta_box(
				'date_range',
				__( 'Date range', 'elm' ),
				array( $this, 'date_range_callback' ),
				'elm_texts',
				'side'
		);
	}
	
	/**
	 * Date range box callback.
	 *
	 */
	function date_range_callback() {
		global $post;
	
		@$date_from = get_post_meta( $post->ID, '_date_from', true );
		@$date_to = get_post_meta( $post->ID, '_date_to', true );
?>
	<p>
		<label for="date_from"><?php _e('From:', 'elm'); ?></label><br />
		<input id="date_from" name="date_from" value="<?php echo $date_from; ?>" />
	</p>
	
	<p>
		<label for="date_to"><?php _e('To:', 'elm'); ?></label><br />
		<input id="date_to" name="date_to" value="<?php echo $date_to; ?>" />
	</p>
	
<?php
	}

	/**
	 * Save post.
	 *
	 */
	function save_post( $post_id ) {
		update_post_meta( $post_id, '_date_from', @$_POST['date_from'] );
		update_post_meta( $post_id, '_date_to', @$_POST['date_to']  );
	}
	
	/**
	 * Get random text.
	 *
	 */
	function get_random_text() {
	
		$texts = new WP_Query( 
			array( 
				'post_type' => 'elm_texts', 
				'post_status' => 'publish', 
				'orderby' => 'rand', 
				'posts_per_page' => '1',
				'meta_query' => array(
					array(
						'key' => '_date_from',
						'value' => date('d-m-Y'),
						'compare' => '<='
					),
					array(
						'key' => '_date_to',
						'value' => date('d-m-Y'),
						'compare' => '>='
					)
				)
			) 
		);
				
		if ( $texts->have_posts() ) :
		
			while ( $texts->have_posts() ) : $texts->the_post();
		
			return get_the_content();
					
			endwhile;
				
		endif;
	}
	
	/**
	 * Add new custom post type. This is the main post type for texts.
	 *
	 */
	function register_custom_posts() {
		$labels = array(
			'name'               => _x( 'Texts', 'elm' ),
			'singular_name'      => _x( 'Text', 'elm' ),
			'menu_name'          => _x( 'Texts', 'elm' ),
			'name_admin_bar'     => _x( 'Texts', 'elm' ),
			'add_new'            => _x( 'Add New', 'elm' ),
			'add_new_item'       => __( 'Add New Text', 'elm' ),
			'new_item'           => __( 'New Text', 'elm' ),
			'edit_item'          => __( 'Edit Text', 'elm' ),
			'view_item'          => __( 'View Text', 'elm' ),
			'all_items'          => __( 'All Texts', 'elm' ),
			'search_items'       => __( 'Search Text', 'elm' ),
			'parent_item_colon'  => __( 'Parent Text:', 'elm' ),
			'not_found'          => __( 'No text found.', 'elm' ),
			'not_found_in_trash' => __( 'No text found in Trash.', 'elm' )
		);

		$args = array(
			'labels'             => $labels,
			'public'             => true,
			'publicly_queryable' => true,
			'show_ui'            => true,
			'show_in_menu'       => true,
			'query_var'          => true,
			'rewrite'            => array( 'slug' => 'texts' ),
			'capability_type'    => 'post',
			'has_archive'        => true,
			'hierarchical'       => false,
			'menu_position'      => null,
			'supports'           => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt', 'comments' )
		);

		register_post_type( 'elm_texts', $args );
	}
	
	/**
	 * Install plugin.
	 *
	 */
	function install() {
		if ( get_option( 'elm_random_text' ) != 'installed' ) {
			update_option( 'elm_random_text', 'installed' );
		}
	}
}

