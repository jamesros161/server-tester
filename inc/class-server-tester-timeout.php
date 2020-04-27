<?php
/**
 * File: class-server-tester-timeout.php
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
		$form_data = $this->tester->validate_post_data( 'timeout_test' );
		$test      = isset( $form_data['test'] ) ? $form_data['test'] : false;
		if ( false === $test ) {
			wp_die();
		}
		switch ( $test ) {
			case 'php_timeout':
				return $this->test_php_timeout();
			case 'ini_set_timeout':
				echo $this->test_ini_set();//phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				break;
			case 'gateway_timeout':
				echo $this->test_gateway_timeout();//phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
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
		$form_data = $this->tester->validate_post_data( 'timeout_test' );
		$response  = array();

		if ( ! is_wp_error( $form_data ) ) {
			$new_execution_timeout = $form_data['time'];
			ini_set( 'max_execution_time', $new_execution_timeout ); //phpcs:ignore WordPress.PHP.IniSet.max_execution_time_Blacklisted
			$new_get = ini_get( 'max_execution_time' );

			$test_duration = (int) $new_get - 1;

			$data     = $this->test_php_timeout( $test_duration );
			$response = array(
				'result' => $new_get,
				'data'   => $data,
			);
		} else {
			$response = $form_data;
		}

		return wp_json_encode( $response );
	}

	/**
	 * Test Gateway Timeout.
	 *
	 * @since 0.1
	 */
	public function test_gateway_timeout() {
		$starting_execution_timeout = ini_get( 'max_execution_time' );
		$start_time                 = date_create();
		$post_data                  = $this->tester->validate_post_data( 'timeout_test' );
		$timeout_limit              = isset( $post_data['time'] ) ? (int) $post_data['time'] : false;
		$time                       = 0;

		for ( ;; ) {
			$current_time = microtime( true );
			$duration     = microtime( true ) - $_SERVER['REQUEST_TIME_FLOAT'];
			$seconds      = (int) ceil( $duration );

			if ( $seconds > $time ) {
				$time = $seconds;
				ini_set( 'max_execution_time', (int) $starting_execution_timeout + $time ); //phpcs:ignore WordPress.PHP.IniSet.max_execution_time_Blacklisted
			}

			if ( $seconds >= $timeout_limit ) {
				$response = array(
					'result' => $time,
					'data'   => 'The Test ran for ' . $seconds . ' without a gateway timeout. To determine the timeout limit, increase the runtime limit option.',
				);
				return wp_json_encode( $response );
			}
		}

		$response = array(
			'result' => $time,
		);

		return wp_json_encode( $response );
	}
}
