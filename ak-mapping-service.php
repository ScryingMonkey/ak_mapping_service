<?php
/**
 * @package AK_Mapping_Service
 * @version 0.0.2
 */
 /*
 * Plugin Name: AK Mapping Service
 * Description: Provide functions to call the AK Mapping API.
 * Plugin URI: https://www.advancedkiosks.com
 * Version:     0.0.2
 * Author:      Clif Boyd
 * License:     GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 */

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

if(file_exists(dirname(__FILE__) . '/vendor/autoload.php')){
    require_once dirname(__FILE__) . '/vendor/autoload.php';
}

function activate_ak_plugin(){
    Inc\Base\Activate::activate();
}
register_activation_hook( __FILE__, 'activate_ak_plugin');

function deactivate_ak_plugin(){
    Inc\Base\Deactivate::deactivate();
}
register_deactivation_hook( __FILE__, 'deactivate_ak_plugin');

if( class_exists('Inc\\Init')){
    Inc\Init::register_services();
}

// TODO: Change define( 'WP_DEBUG', true ); to false.  wp-config-sample.php