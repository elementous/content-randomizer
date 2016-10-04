<?php

class Elm_Randomizer {

	function __construct() {
		add_action( 'init', array( $this, 'init' ) );
		add_action( 'init', array( $this, 'update_check' ) );
		
		add_action( 'add_meta_boxes', array( $this, 'date_range' ) );
		add_action( 'save_post', array( $this, 'save_post' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_css_js' ) ); 
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_js_and_css' ) );
		add_action( 'admin_footer', array( $this, 'admin_footer' ) );
		add_action( 'wp_footer', array( $this, 'slideshow_js' ) );
	}
	
	/**
	 * Init actions and filters.
	 *
	 */
	function init() {
		$this->register_cp_and_tax();
	}
	
	/**
	 * Load CSS and JS for admin.
	 * @global $post
	 *
	 */
	function admin_css_js() {
		global $post;
	
		if ( get_post_type( $post ) !== 'elm_texts' )
			return; 
			
		wp_enqueue_script( 'jquery' );
		wp_enqueue_script( 'jquery-ui-core' );
		wp_enqueue_script( 'jquery-ui-datepicker' );
		
		wp_enqueue_style( 'jquery-style', 'http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.2/themes/smoothness/jquery-ui.css' );
	}
	
	/**
	 * Admin footer JS.
	 * @global $post
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

	<style type="text/css">
		.date-input {
			border: 1px solid #E1E1E1;
		}
	</style>
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
	 * @global $post
	 *
	 */
	function date_range_callback() {
		global $post;
	
		@$date_from = get_post_meta( $post->ID, '_date_from', true );
		@$date_to = get_post_meta( $post->ID, '_date_to', true );
?>
	<p>
		<label for="date_from"><?php _e('From:', 'elm'); ?></label><br />
		<input id="date_from" name="date_from" class="date-input" value="<?php echo $date_from; ?>" />
	</p>
	
	<p>
		<label for="date_to"><?php _e('To:', 'elm'); ?></label><br />
		<input id="date_to" name="date_to" class="date-input" value="<?php echo $date_to; ?>" />
	</p>
	
<?php
	}

	/**
	 * Save post.
	 * @param $post_id
	 *
	 */
	function save_post( $post_id ) {
		update_post_meta( $post_id, '_date_from', @$_POST['date_from'] );
		update_post_meta( $post_id, '_date_to', @$_POST['date_to']  );
	}
	
	/**
	 * Get slides HTML for Owl carousel.
	 *
	 * @param $type string text|video|image
	 * @param $category_id string category ID
	 *
	 */
	function get_owl_slides( $type = 'text', $category_id = 'all' ) {
		$args = array( 'post_type' => 'elm_texts', 'post_status' => 'publish', 'posts_per_page' => -1 );
		
		if ( $category_id != 'all' ) 
			$args['tax_query'] = array(
				array(
					'taxonomy' => 'randomizer_category',
					'field' => 'term_id',
					'terms' => $category_id
				)
			);
	
		$randomizer = new WP_Query( 
			apply_filters( 'elm_rt_get_random_text_args', $args )
		);
		
		$output = '';
		
		if ( $randomizer->posts ) :
				$output .= '<div class="randomizer-slideshow owl-carousel owl-theme">';
				
				foreach( $randomizer->posts as $k => $post ) :

					setup_postdata( $post );
					
					if ( $type == 'text' || $type == 'video' ) {
						$output .= '<div class="item">' . apply_filters( 'elm_rt_get_the_content', get_the_content() ) . '</div>';
					} else if ( $type == 'image' ) {
						$output .= '<div class="item">' . apply_filters( 'elm_rt_get_the_post_thumbnail', get_the_post_thumbnail( $post->ID, apply_filters( 'elm_rt_thumbnail_size', 'medium' ) ) ) . '</div>';
					}
				
				endforeach;
				
				$output .= '</div>';
				
		endif;
		
		return $output;
	}
	
	/**
	 * Get random object HTML.
	 *
	 * @param $type string text|video|image
	 * @param $category_id string category ID
	 *
	 */
	function get_random( $type = 'text', $category_id = 'all' ) {
		$args = array( 'post_type' => 'elm_texts', 'post_status' => 'publish', 'posts_per_page' => -1 );
		
		if ( $category_id != 'all' ) 
			$args['tax_query'] = array(
				array(
					'taxonomy' => 'randomizer_category',
					'field' => 'term_id',
					'terms' => $category_id
				)
			);
	
		$randomizer = new WP_Query( 
			apply_filters( 'elm_rt_get_random_text_args', $args )
		);
		
		$output = '';
		
		if ( $randomizer->posts ) :
				foreach( $randomizer->posts as $post ) { 
					$date_from = get_post_meta( $post->ID, '_date_from', true );
					$date_to = get_post_meta( $post->ID, '_date_to', true );
					
					if ( !empty( $date_from ) || !empty( $date_to ) ) {
			
						if ( empty( $date_from ) )
							$date_from = date('d-m-Y');
							
						if ( empty( $date_to ) )
							$date_to = date('d-m-Y');
						
						if ( $date_from <= date('d-m-Y') && $date_to >= date('d-m-Y') ) {
							$random_ids[] = $post->ID;
						}
						
					} else {
						$random_ids[] = $post->ID;
					}
				}
				
				$random_key = array_rand( $random_ids, 1 );
				$random_post_id = $random_ids[$random_key];
				
				$post = get_post( $random_post_id );
				setup_postdata( $post );
				
				if ( $type == 'text' || $type == 'video' ) {
					$output .= apply_filters( 'elm_rt_get_the_content', get_the_content() );
				} else if ( $type == 'image' ) {
					$output .= apply_filters( 'elm_rt_get_the_post_thumbnail', get_the_post_thumbnail( $random_post_id, apply_filters( 'elm_rt_thumbnail_size', 'medium' ) ) );
				}
				
		endif;
		
		return $output;
	}
	
	/**
	 * Add new custom post type. This is the main post type for texts.
	 *
	 */
	function register_cp_and_tax() {
		$permalinks = get_option( 'elm_randomizer_permalinks' );
		
		// Default values
		$custom_post_type_slug = 'randomizer';
		$taxonomy_slug = 'randomizer-category';
		
		if ( $permalinks ) {
			//$custom_post_type_slug = $permalinks['custom_post_type_base'];
			if ( $permalinks['custom_post_type_base'] )
				$custom_post_type_slug = $permalinks['custom_post_type_base'];
				
			if ( $permalinks['taxonomy_base'] )
				$taxonomy_slug = $permalinks['taxonomy_base'];	
		}
		
		// Register randomizer custom post
		$labels = array(
			'name'               => __( 'Items', 'elm' ),
			'singular_name'      => __( 'Item', 'elm' ),
			'menu_name'          => __( 'Randomizer', 'elm' ),
			'name_admin_bar'     => __( 'Items', 'elm' ),
			'add_new'            => __( 'Add New', 'elm' ),
			'add_new_item'       => __( 'Add New Item', 'elm' ),
			'new_item'           => __( 'New Item', 'elm' ),
			'edit_item'          => __( 'Edit Item', 'elm' ),
			'view_item'          => __( 'View Item', 'elm' ),
			'all_items'          => __( 'All Items', 'elm' ),
			'search_items'       => __( 'Search Item', 'elm' ),
			'parent_item_colon'  => __( 'Parent Item:', 'elm' ),
			'not_found'          => __( 'No item found.', 'elm' ),
			'not_found_in_trash' => __( 'No item found in Trash.', 'elm' )
		);

		$args = array(
			'labels'             => $labels,
			'public'             => true,
			'publicly_queryable' => true,
			'show_ui'            => true,
			'show_in_menu'       => true,
			'query_var'          => true,
			'rewrite'            => array( 'slug' => $custom_post_type_slug ),
			'capability_type'    => 'post',
			'has_archive'        => true,
			'hierarchical'       => false,
			'menu_position'      => null,
			'supports'           => array( 'title', 'editor', 'thumbnail' )
		);

		register_post_type( 'elm_texts', apply_filters( 'elm_rt_custom_post_args', $args ) );
		
		// Register randomizer categories
		$labels = array(
			'name'              => __( 'Categories', 'elm' ),
			'singular_name'     => __( 'Category', 'elm' ),
			'search_items'      => __( 'Search Categories', 'elm' ),
			'all_items'         => __( 'All Categories', 'elm' ),
			'parent_item'       => __( 'Parent Category', 'elm' ),
			'parent_item_colon' => __( 'Parent Category:', 'elm' ),
			'edit_item'         => __( 'Edit Category', 'elm' ),
			'update_item'       => __( 'Update Category', 'elm' ),
			'add_new_item'      => __( 'Add New Category', 'elm' ),
			'new_item_name'     => __( 'New Category Name', 'elm' ),
			'menu_name'         => __( 'Categories', 'elm' ),
		);

		$args = array(
			'hierarchical'      => true,
			'labels'            => $labels,
			'show_ui'           => true,
			'show_admin_column' => true,
			'query_var'         => true,
			'rewrite'           => array( 'slug' => $taxonomy_slug ),
		);

		register_taxonomy( 'randomizer_category', array( 'elm_texts' ), $args );
	}
	
	/**
	* Enqueues JavaScript and CSS files.
	*/
	function enqueue_js_and_css() {
		$disable_slideshow = apply_filters( 'elm_rt_load_owl', false );
		
		if ( $disable_slideshow === false ) {
			wp_enqueue_style( 'elm-owl-css', ELM_RT_PLUGIN_URL . '/assets/css/owl.carousel.min.css' );
			wp_enqueue_script( 'elm-owl-carousel', ELM_RT_PLUGIN_URL . '/assets/js/owl.carousel.min.js', array( 'jquery' ), '1.0', true );
		}
	}
	
	/**
	 * Add Randomizer slideshow JavaScript.
	 *
	 */
	function slideshow_js() {
	
	$disable_slideshow = apply_filters( 'elm_rt_load_owl', false );
		
		if ( $disable_slideshow === false ) {
?>
		<script type="text/javascript">
			jQuery( document ).ready(function( $ ) {
				 jQuery(".randomizer-slideshow").owlCarousel({
					<?php
					  $args = apply_filters( 'elm_rt_slideshow_args', 'singleItem:true, items:1, autoplay:true, autoplayTimeout:3000, autoplayHoverPause:true, dots:false, loop:true' );
					  
					  echo $args;
					?>
				  });
			});
		</script>
<?php
		}
	}
	
	/**
	 * Install plugin.
	 *
	 */
	function install() {
		if ( get_option( 'elm_randomizer' ) != 'installed' ) {
			update_option( 'elm_randomizer', 'installed' );
			update_option( 'elm_randomizer_version', ELM_RT_VERSION );
			update_option( 'elm_randomizer_permalinks', '' );
		}
		
		// Register custom post and taxonomy here so that we can flush permalinks
		$this->register_cp_and_tax();
		
		flush_rewrite_rules();
	}
	
	/**
	 * Handle updates.
	 *
	 */
	function update_check() {
		if ( ! get_option( 'elm_randomizer_version' ) ) {
			update_option( 'elm_randomizer_version', ELM_RT_VERSION );
			update_option( 'elm_randomizer_permalinks', '' );
		}
	}
}

