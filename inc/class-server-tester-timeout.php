<?php
/**
 * File: class-server-tester-timeout.php
 *
 * phpcs:disable WordPress
 * phpcs:disable WordPress
 *
 * @since 0.1
 *
 * @package server_tester
 */

/**
 * Class: Timeout_Tester.
 *
 * Tests for timeout issues.
 *
 * @since 0.1
 */
class Server_Tester_Timeout {
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
		$test = isset( $this->tester->form_post['test'] ) ? $this->tester->form_post['test'] : false;
		if ( false === $test ) {
			return $test;
		}
		error_log( $test );
		switch ( $test ) {
			case 'php_timeout':
				return $this->test_php_timeout();
			case 'ini_set_timeout':
				return $this->test_ini_set();
			case 'gateway_timeout':
				return $this->test_gateway_timeout();
			case 'is_php_fcgi':
				return php_sapi_name();
			default:
				return false;
		}
	}

	/**
	 * Test PHP Timeout.
	 *
	 * @since 0.1
	 */
	public function test_php_timeout() {
		$start_time = date_create();
		$time       = 0;

		register_shutdown_function(
			function() {
				return error_get_last()['message'];
			}
		);

		error_log( 'Started Testing Max Execution Time:' );

		$default_timeout = 90;
		$timeout         = isset( $this->tester->form_post['time'] ) ? $this->tester->form_post['time'] : $default_timeout;

		for (;;) {
			$current_time = date_create();
			$duration     = date_diff( $current_time, $start_time );
			$seconds      = $duration->s;
			if ( $seconds > $time ) {
				$time = $seconds;
			}

			if ( $time == (int) $timeout ) {
				return 'Result: A script executing for ' . $timeout . ' seconds did not timeout.';
			}
		}

		return error_get_last()['message'];
	}

	/**
	 * Test ini_set( max_execution_time ).
	 *
	 * @since 0.1
	 */
	public function test_ini_set() {
		$new_execution_timeout = $this->tester->form_post['time'];
		ini_set( 'max_execution_time', $new_execution_timeout );
		$new_get = ini_get( 'max_execution_time' );

		$this->tester->form_post['time'] = (int) $new_get - 1;

		$data     = $this->test_php_timeout();
		$response = array(
			'result' => $new_get,
			'data'   => $data,
		);

		return json_encode( $response );
	}

	/**
	 * Test Gateway Timeout.
	 *
	 * @since 0.1
	 */
	public function test_gateway_timeout() {
		$starting_execution_timeout = ini_get( 'max_execution_time' );
		$start_time = date_create();
		$time       = 0;

		for (;;) {
			$current_time = date_create();
			$duration     = date_diff( $current_time, $start_time );
			$seconds      = $duration->s;
			if ( $seconds > $time ) {
				$time = $seconds;
				ini_set( 'max_execution_time', (int) $starting_execution_timeout + $time );
			}
		}

		$data     = $this->test_php_timeout();
		$response = array(
			'result' => $time,
		);

		return json_encode( $response );
	}
}
