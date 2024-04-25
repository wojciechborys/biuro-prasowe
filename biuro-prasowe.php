<?php
/**
 * Plugin Name: Biuro Prasowe
 * Description: Rozszerzenie standardowych funkcjonalności strony PERN.
 * Version: 1.0
 * Author: Wojciech Borys
 * Author URI: https://www.hyperdata.pl
 */

if ( !defined( 'MY_ACF_PATH' ) ) {
    define( 'MY_ACF_URL', WP_PLUGIN_DIR . '/advanced-custom-fields-pro' );
}

define( 'PLUGIN_DIR', plugin_dir_path(__FILE__));

require_once MY_ACF_URL . '/acf.php';
require_once PLUGIN_DIR . '/load.php';