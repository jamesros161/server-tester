<?php
/**
 * File: server-tester.php
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
		// Sets PHP Superglobals to class properties.
		$this->set_global_props();

		// Loads and instantiates classes.
		$this->load_classes();

		// Register Filters.
		$this->register_filters();

		// Register Actions.
		$this->register_actions();
	}

	/**
	 * Load Classes.
	 *
	 * @since 0.1
	 */
	public function load_classes() {
		// Require Class Files.
		require_once ST_BASEDIR . '/inc/class-server-tester-timeout.php';
		require_once ST_BASEDIR . '/inc/class-server-tester-partials.php';
		require_once ST_BASEDIR . '/inc/class-server-tester-pages.php';

		// Instantiate Classes.
		$this->timeout  = new Server_Tester_Timeout( $this );
		$this->partials = new Server_Tester_Partials( $this );
		$this->pages    = new Server_Tester_Pages( $this );
	}

	/**
	 * Register Filters.
	 *
	 * @since 0.1
	 */
	public function register_filters() {
		// Add Filters.
		add_filter( 'server_tester_sapi', array( $this, 'php_sapi_filter' ), 10, 1 );
	}

	/**
	 * Register Actions.
	 *
	 * @since 0.1
	 */
	public function register_actions() {
		// Add Actions.
		add_action( 'admin_menu', array( $this, 'add_submenu_page' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
		add_action( 'wp_ajax_timeout_test', array( $this->timeout, 'run_test' ) );
	}

	/**
	 * Enqueue Scripts.
	 *
	 * @since 0.1
	 *
	 * @param string $hook Hook this script is being called on.
	 */
	public function enqueue_scripts( $hook ) {
		if ( false !== strpos( $hook, 'server-tester' ) ) {
			wp_register_script( 'server_tester_admin_script', ST_BASEURL . '/assets/js/main.js', array( 'jquery' ), ST_VERSION, true );

			$data = array(
				'isFcgi' => apply_filters( 'server_tester_sapi', 'fcgi' ),
			);

			wp_localize_script( 'server_tester_admin_script', 'ST', $data );

			wp_enqueue_script( 'server_tester_admin_script' );
		}

	}

	/**
	 * Register Admin Page.
	 *
	 * @since 0.1
	 */
	public function add_submenu_page() {
		add_management_page(
			'Server Tester',
			'Server Tester',
			'manage_options',
			'server-tester-page',
			array( $this->pages, 'main_admin' )
		);
	}

	/**
	 * PHP SAPI Filter.
	 *
	 * @since 0.1
	 *
	 * @param string $sapi_to_check SAPI Type to filter against.
	 */
	public function php_sapi_filter( $sapi_to_check ) {
		$sapi = php_sapi_name();
		if ( ! $sapi_to_check || false !== strpos( $sapi, strtolower( $sapi_to_check ) ) ) {
			return $sapi;
		} else {
			return false;
		}
	}

	/**
	 * Validate Post Data.
	 *
	 * @since 0.1
	 * @param string $post_action Action to validate against.
	 */
	public function validate_post_data( $post_action ) {
		if (
			isset( $_POST['action'] ) &&
			isset( $_POST['nonce'] ) &&
			$_POST['action'] === $post_action &&
			wp_verify_nonce( $_POST['nonce'], $post_action )
		) {
			$post_data = $_POST;
			return $post_data;
		} else {
			$post_data = new WP_Error( 'Nonce Verification Failed' );
		}

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

		for ( $i = 0; $i < $length; $i++ ) {
			$random_string .= $characters[ wp_rand( 0, $characters_length - 1 ) ];
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
