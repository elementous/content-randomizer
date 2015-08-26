<?php
/*
  Plugin Name: Content Randomizer
  Plugin URI: https://www.elementous.com
  Description: This plugin allows you to add texts or images and display them in a random order.
  Author: Elementous
  Author URI: https://www.elementous.com
  Version: 1.0
*/

define( 'ELM_RT_VERSION', '1.1' );
define( 'ELM_RT_PLUGIN_PATH', dirname( __FILE__ ) );
define( 'ELM_RT_INCLUDES_PATH', ELM_RT_PLUGIN_PATH . '/includes' );
define( 'ELM_RT_PLUGIN_FOLDER', basename( ELM_RT_PLUGIN_PATH ) );
define( 'ELM_RT_PLUGIN_URL', plugins_url() . '/' . ELM_RT_PLUGIN_FOLDER );

require ELM_RT_PLUGIN_PATH . '/random-text.php';
require ELM_RT_INCLUDES_PATH . '/widgets.php';
require ELM_RT_INCLUDES_PATH . '/shortcodes.php';

$random_text = new RandomText();

// Install plugin
register_activation_hook( __FILE__, array( $random_text, 'install' ) );
