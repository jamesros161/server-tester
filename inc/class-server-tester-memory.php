<?php
/**
 * File: class-server-tester-memory.php
 *
 * @since 0.2
 *
 * @package server_tester
 */

/**
 * Class: Server_Tester_Memory.
 *
 * Tests for timeout issues.
 *
 * @since 0.1
 */
class Server_Tester_Memory {
	/**
	 * Tester.
	 *
	 * @since 0.1
	 * @var Server_Tester
	 */
	public $tester;

	/**
	 * Constructor.
	 *
	 * @since 0.1
	 *
	 * @param Server_Tester $tester Server_Tester instance.
	 */
	public function __construct( Server_Tester $tester ) {
		$this->tester = $tester;
	}

	/**
	 * Run Test.
	 *
	 * @since 0.1
	 */
	public function run_test() {
		$form_data = $this->tester->validate_post_data( 'memory_test' );
		$test      = isset( $form_data['test'] ) ? $form_data['test'] : false;
		error_log( $test );
		if ( false === $test ) {
			wp_die();
		}
		switch ( $test ) {
			case 'ini_set_memory':
				echo $this->test_ini_set();//phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				break;
			default:
				return false;
		}
		wp_die();
	}

	/**
	 * Test PHP Timeout.
	 *
	 * @since 0.1
	 *
	 * @param integer $test_duration Duration of test.
	 */
	public function test_php_timeout( $test_duration = 90 ) {
		$start_time = date_create();

		register_shutdown_function(
			function() {
				return error_get_last()['message'];
			}
		);

		$time = 0;
		for ( ;; ) {
			$current_time = microtime( true );
			$duration     = microtime( true ) - $_SERVER['REQUEST_TIME_FLOAT'];
			$seconds      = (int) ceil( $duration );

			if ( $seconds > $time ) {
				$time = $seconds;
			}

			if ( $seconds === (int) $test_duration ) {
				return 'Result: A script executing for ' . $test_duration . ' seconds did not timeout.';
			}
		}
	}

	/**
	 * Test ini_set( max_execution_time ).
	 *
	 * @since 0.1
	 */
	public function test_ini_set() {
		$form_data = $this->tester->validate_post_data( 'memory_test' );
		$response  = array();

		if ( ! is_wp_error( $form_data ) ) {
			$test_limit = $form_data['memory'];
			ini_set( 'memory_limit', $test_limit . 'M' ); //phpcs:ignore WordPress.PHP.IniSet.max_execution_time_Blacklisted
			$test_limit_bytes = $test_limit * 1048576;
			$random_bytes = random_bytes( $test_limit_bytes - memory_get_usage( true ) - 1024 );

			$response = array(
				'data' => size_format( memory_get_usage( true ), 2 ),
			);
		} else {
			$response = $form_data;
		}

		return wp_json_encode( $response );
	}
}
