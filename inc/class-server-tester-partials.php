<?php
/**
 * File: class-server-tester-partials.php
 *
 * phpcs:disable WordPress
 * phpcs:disable WordPress
 *
 * @since 0.1
 *
 * @package server_tester
 */

/**
 * Class: Server_Tester_Partials.
 *
 * @since 0.1
 */
class Server_Tester_Partials {
	/**
	 * Header.
	 *
	 * @since 0.1
	 * @var string
	 */
	public $header;

	/**
	 * Footer.
	 *
	 * @since 0.1
	 * @var string
	 */
	public $footer;

	/**
	 * Home.
	 *
	 * @since 0.1
	 * @var string
	 */
	public $home;

	/**
	 * Debug_Container.
	 *
	 * @since 0.1
	 * @var string
	 */
	public $debug_container;

	/**
	 * Constructor.
	 *
	 * @since 0.1
	 *
	 * @param Server_Tester $tester Instance of Server_Tester.
	 */
	public function __construct( Server_Tester $tester ) {
		$this->debug_container = '
		<div id="debug_container">
			<h3>Form Post Debug</h3>
			<div class="form_post_debug">
				<pre>' . htmlspecialchars( json_encode( $tester->get_form_post(), JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES ) ) . '</pre>
			</div>
			<h3>Session Debug</h3>
			<div class="session_debug">
				<pre>' . htmlspecialchars( json_encode( $tester->get_session(), JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES ) ) . '</pre>
			</div>
			<h3>Request Debug</h3>
			<div class="request_debug">
				<pre>' . htmlspecialchars( json_encode( $tester->get_request(), JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES ) ) . '</pre>
			</div>
			<h3>Server Debug</h3>
			<div class="server_debug">
				<pre>' . htmlspecialchars( json_encode( $tester->get_server(), JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES ) ) . '</pre>
			</div>
		</div>
		';

		$this->nonce_error_header  = 'HTTP/1.1 418 I\'m a teapot';
		$this->nonce_error_message = '
			<div class="error_message">
				<h1>HTTP/1.1 418 I\'m a teapot</h1>
				<p>
					The server refueses to brew coffee because it is, permenantly, a teapot.
					If the server were a combination teapot / coffee machine, and was out of coffee.
					This would have been a 503 error.
				</p>
			</div>
		';

		$this->header = include BASEDIR . '/templates/header.php';
		$this->footer = include BASEDIR . '/templates/footer.php';
		$this->home   = include BASEDIR . '/templates/home.php';
	}
}
