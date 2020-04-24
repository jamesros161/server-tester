<?php
/**
 * File: server-tester.php
 *
 * phpcs:disable WordPress
 * phpcs:disable WordPress
 *
 * @since 0.1
 *
 * @package server_tester
 */

// Load Classes.
require_once BASEDIR . '/inc/class-server-tester-timeout.php';
require_once BASEDIR . '/inc/class-server-tester-partials.php';

/**
 * Class: Server_Tester.
 *
 * @since 0.1
 */
class Server_Tester {
	/**
	 * Session.
	 *
	 * @since 0.1
	 * @var array
	 */
	public $session;

	/**
	 * Request.
	 *
	 * @since 0.1
	 * @var array
	 */
	public $request;

	/**
	 * Server.
	 *
	 * @since 0.1
	 * @var array
	 */
	public $server;

	/**
	 * Form Post.
	 *
	 * @since 0.1
	 * @var array
	 */
	public $form_post;

	/**
	 * Timeout.
	 *
	 * @since 0.1
	 * @var Server_Tester_Timout
	 */
	public $timeout;

	/**
	 * Partials.
	 *
	 * @since 0.1
	 * @var Server_Tester_Partials
	 */
	public $partials;

	/**
	 * Constructor.
	 *
	 * @since 0.1
	 */
	public function __construct() {
		$this->set_global_props();
		$this->timeout  = new Server_Tester_Timeout( $this );
		$this->partials = new Server_Tester_Partials( $this );
	}

	/**
	 * Is Valid Referrer.
	 *
	 * @since 0.1
	 */
	public function is_valid_referrer() {
		return isset( $this->server['HTTP_REFERER'] ) && false !== strpos( $this->server['HTTP_REFERER'], $this->server['HTTP_HOST'] );
	}

	/**
	 * Generate Random String.
	 *
	 * @since 0.1
	 *
	 * @param integer $length Length of string to create.
	 * @return string
	 */
	public function generate_random_string( $length = 10 ) {
		$characters        = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$characters_length = strlen( $characters );
		$random_string     = '';

		for ($i = 0; $i < $length; $i++) {
			$random_string .= $characters[rand( 0, $characters_length - 1 )];
		}
		return $random_string;
	}

	/**
	 * Set Global Props.
	 *
	 * @since 0.1
	 */
	private function set_global_props() {

		if ( isset( $_SESSION ) ) {
			$this->session = $_SESSION;
		} else {
			session_start();
			$this->session = $_SESSION;
		}

		if ( isset( $_POST ) ) {
			$this->form_post = $_POST;
		}

		if ( isset( $_REQUEST ) ) {
			$this->request = $_REQUEST;
		}

		if ( isset( $_SERVER ) ) {
			$this->server = $_SERVER;
		}
	}

	/**
	 * Get Session.
	 *
	 * @since 0.1
	 *
	 * @return array.
	 */
	public function get_session() {
		return $this->session;
	}

	/**
	 * Get Form Post.
	 *
	 * @since 0.1
	 *
	 * @return array.
	 */
	public function get_form_post() {
		return $this->form_post;
	}

	/**
	 * Get Request.
	 *
	 * @since 0.1
	 *
	 * @return array.
	 */
	public function get_request() {
		return $this->request;
	}

	/**
	 * Get server.
	 *
	 * @since 0.1
	 *
	 * @return array.
	 */
	public function get_server() {
		return $this->server;
	}
}
