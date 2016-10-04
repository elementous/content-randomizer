<?php
/*
  Plugin Name: Content Randomizer
  Plugin URI: https://www.elementous.com
  Description: This plugin allows you to add texts, images, videos and display them in a random order or slideshow.
  Author: Elementous
  Author URI: https://www.elementous.com
  Version: 1.2.3
*/

define( 'ELM_RT_VERSION', '1.2.3' );
define( 'ELM_RT_PLUGIN_PATH', dirname( __FILE__ ) );
define( 'ELM_RT_INCLUDES_PATH', ELM_RT_PLUGIN_PATH . '/includes' );
define( 'ELM_RT_PLUGIN_FOLDER', basename( ELM_RT_PLUGIN_PATH ) );
define( 'ELM_RT_PLUGIN_URL', plugins_url() . '/' . ELM_RT_PLUGIN_FOLDER );

require ELM_RT_PLUGIN_PATH . '/randomizer.php';
require ELM_RT_INCLUDES_PATH . '/widgets.php';
require ELM_RT_INCLUDES_PATH . '/shortcodes.php';
require ELM_RT_INCLUDES_PATH . '/permalink-settings.php';

$randomizer = new Elm_Randomizer();

// Install plugin
register_activation_hook( __FILE__, array( $randomizer, 'install' ) );
