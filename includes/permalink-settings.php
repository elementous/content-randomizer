<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Elm_Randomizer_Permalink_Settings' ) ) :

class Elm_Randomizer_Permalink_Settings {
	
	/**
	 * Hook in tabs.
	 */
	public function __construct() {
		add_action( 'admin_init', array( $this, 'admin_init' ) );
	}
	
	/**
	 * Admin init.
	 */
	public function admin_init() {
		$this->settings_init();
		$this->settings_save();
	}
	
	/**
	 * Init our settings.
	 */
	public function settings_init() {
		// Add a section to the permalinks page
		add_settings_section( 'randomizer-permalink', __( 'Randomizer permalink base', 'elm' ), array( $this, 'settings' ), 'permalink' );

		// Add our settings
		add_settings_field(
			'elm_randomizer_cp_slug',            // id
			__( 'Randomizer post base', 'elm' ),   // setting title
			array( $this, 'post_base_input' ),  // display callback
			'permalink',                                    // settings page
			'randomizer-permalink'                                      // settings section
		);
		add_settings_field(
			'elm_randomizer_taxonomy_slug',                 // id
			__( 'Randomizer taxonomy slug', 'elm' ),        // setting title
			array( $this, 'taxonomy_slug_input' ),       // display callback
			'permalink',                                    // settings page
			'randomizer-permalink'                                      // settings section
		);
	}
	
	/**
	 * Show a slug input box.
	 */
	public function post_base_input() {
		$permalinks = get_option( 'elm_randomizer_permalinks' );
		?>
		<input name="elm_randomizer_cp_slug" type="text" class="regular-text code" value="<?php if ( isset( $permalinks['custom_post_type_base'] ) ) echo esc_attr( $permalinks['custom_post_type_base'] ); ?>" placeholder="<?php echo esc_attr_x('randomizer', 'slug', 'elm') ?>" />
		<?php
	}
	
	/**
	 * Show a slug input box.
	 */
	public function taxonomy_slug_input() {
		$permalinks = get_option( 'elm_randomizer_permalinks' );
		?>
		<input name="elm_randomizer_taxonomy_slug" type="text" class="regular-text code" value="<?php if ( isset( $permalinks['taxonomy_base'] ) ) echo esc_attr( $permalinks['taxonomy_base'] ); ?>" placeholder="<?php echo esc_attr_x('randomizer-category', 'slug', 'elm') ?>" />
		<?php
	}
	
	/**
	 * Show the settings.
	 */
	public function settings() {
		echo wpautop( __( 'These settings control the permalinks used for randomizer posts. These settings only apply when not using "default" permalinks above.', 'elm' ) );
	}
	
	/**
	 * Save the settings.
	 */
	public function settings_save() {
		if ( ! is_admin() ) {
			return;
		}
		
		// We need to save the options ourselves; settings api does not trigger save for the permalinks page
		if ( isset( $_POST['permalink_structure'] ) || isset( $_POST['custom_post_type_base'] ) && isset( $_POST['taxonomy_base'] ) ) {
			$permalinks = get_option( 'elm_randomizer_permalinks' );

			if ( ! $permalinks ) {
				$permalinks = array();
			}
			
			$permalinks['custom_post_type_base']    = untrailingslashit( esc_attr( $_POST['elm_randomizer_cp_slug'] ) );
			$permalinks['taxonomy_base']    = untrailingslashit( esc_attr( $_POST['elm_randomizer_taxonomy_slug'] ) );
			
			update_option( 'elm_randomizer_permalinks', $permalinks );
			
			// Flush permalinks
			global $wp_rewrite;
			$wp_rewrite->flush_rules( false );
		}
	}
}

endif;

return new Elm_Randomizer_Permalink_Settings();