<?php
/**
 * Plugin Name: Server Tester
 * Plugin URI: https://gitbub/jamesros161/server-tester
 * Version: 0.1.1
 * Author: BoldGrid <support@boldgrid.com>
 * Author URI: https://www.boldgrid.com/
 * Description: Debug tool for testing server issues
 * Text Domain: server-tester
 * Domain Path: /languages
 * License: GPL
 *
 * @package server-tester
 */

if ( ! defined( 'WPINC' ) ) {
	die();
}

if ( ! defined( 'ST_BASEDIR' ) ) {
	define( 'ST_BASEDIR', plugin_dir_path( __FILE__ ) );
}

if ( ! defined( 'ST_PLUGIN_NAME' ) ) {
	define( 'ST_PLUGIN_NAME', 'server-tester' );
}

if ( ! defined( 'ST_BASEURL' ) ) {
	define( 'ST_BASEURL', plugin_dir_url( __FILE__ ) );
}

if ( ! defined( 'ST_VERSION' ) ) {
	define( 'ST_VERSION', '0.1' );
}

require_once ST_BASEDIR . '/inc/class-server-tester.php';

$tester = new Server_Tester();

add_filter( 'server_tester_get_tester', 'get_tester', 10, 0 );

/**
 * Get Tester.
 *
 * Callback function for server_tester_get_tester filter.
 *
 * @return Server_Tester
 */
function get_tester() {
	if ( ! isset( $tester ) ) {
		$tester = new Server_Tester();
	} else {
		return $tester;
	}
}
