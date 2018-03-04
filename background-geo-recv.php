<?php
/**
 * Plugin Name: Background Geolocation Receiver
 * Plugin URI: https://stillatmylinux.com/
 * Description: A server-side receiver of the location data from the cordova-background-geolocation cordova plugin
 * Version: 0.0.1
 * Author: Stillatmylinux
 * Author URI: https://stillatmylinux.com
 * Requires at least: 4.4
 * Tested up to: 4.9.4
 *
 * Text Domain: bkggeo
 * Domain Path: /i18n/languages/
 * 
 * Requires: https://github.com/transistorsoft/cordova-background-geolocation [Premium] Cordova Background Geolocation
 *
 * @package BackgroundGeo
 * @category Core
 * @author Stillatmylinux
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( !defined( 'BKGGEO_DIR' ) ) {
    define( 'BKGGEO_DIR', plugin_dir_path( __FILE__ ) );
}

if ( !defined( 'BKGGEO_URL' ) ) {
    define( 'BKGGEO_URL', plugins_url( '/', __FILE__ ) );
}

if ( !defined( 'BKGGEO_INC' ) ) {
    define( 'BKGGEO_INC', BKGGEO_DIR . 'inc/' );
}

// Include the main class.
if ( ! class_exists( 'BackgroundGeo' ) ) {

	include_once dirname( __FILE__ ) . '/model/Location.php';
	include_once dirname( __FILE__ ) . '/inc/class-background-geo.php';
	
	$backgroundGeo = new BackgroundGeo();
	$backgroundGeo->hooks();
}