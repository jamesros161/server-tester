<?php
/**
 * File: index.php
 *
 * Creates tests for testing various issues on a server.
 *
 * phpcs:disable WordPress
 * phpcs:disable WordPress
 *
 * @since 0.1
 *
 * @package server_tester
 */

define( 'BASEDIR', dirname( __FILE__ ) );

// Require Main File.
require_once BASEDIR . '/inc/class-server-tester.php';
$tester = new Server_Tester();

echo $tester->partials->header . $tester->partials->home . $tester->partials->footer;


