<?php
/**
 * File: class-server-tester-pages.php
 *
 *
 * @since 0.1
 *
 * @package server_tester
 */

/**
 * Class: Server_Tester.
 *
 * @since 0.1
 */
class Server_Tester_Pages {
	/**
	 * Main Admin Page.
	 *
	 * @since 0.1
	 * @var string
	 */
	public $main;

	/**
	 * Main Admin Page.
	 *
	 * Renders main admin page.
	 */
	public static function main_admin() {
		$page = include ST_BASEDIR . '/templates/main_admin.php';
		echo $page;
	}
}