<?php
/**
 * File: home.php
 *
 * phpcs:disable WordPress
 * phpcs:disable WordPress
 *
 * @since 0.1
 *
 * @package server_tester
 */

return '
		<div class="wrap">
			<div class="test_form">
			<form id="ini_set_form" method="POST">
				<h4 class="test_heading">' . esc_html( 'Test ini_set( \'max_execution_time\') ' ) . '</h4>
				<p>' . esc_html( 'Current max_execution_time value = ' . ini_get( 'max_execution_time' ) . 's' ) . '</p>
				<input type="hidden" name="action" value="timeout_test" />
				<input type="hidden" name="test" value="ini_set_timeout" />
				<input type="hidden" name="ajax" value="true" />
				<input type="hidden" name="nonce" value ="' . wp_create_nonce( 'timeout_test' ) . '" />
				<p>
					<input name="time" type="number" />
					<span>' . esc_html( 'Specify the value you wish to set max_execution_time to for this test. ( Defaults to 90s )' ) . '</span>
				</p>
				<p style="display:flex">
					<button class="button">' . esc_html( 'Start Test' ) . '</button>
					<span class="spinner"></span>
					<span class="results"></span>
				</p>
			</form>
			<form id="test_gateway_timeout" method="POST">
				<h4 class="test_heading">' . esc_html( 'Test Gateway ( NGINX/Apache > FastCGI Timeout )' ) . '</h4>
				<input type="hidden" name="action" value="timeout_test" />
				<input type="hidden" name="test" value="gateway_timeout" />
				<input type="hidden" name="ajax" value="true" />
				<input type="hidden" name="nonce" value ="' . wp_create_nonce( 'timeout_test' ) . '" />
				<p>
					<input name="time" type="number" />
					<span>' . esc_html( 'Specify a runtime limit for this test. If you do not specify a limit, this can (possibly) cause PHP-FPM to crash.' ) . '</span>
				</p>
				<p style="display:flex">
					<button class="button">' . esc_html( 'Start Test' ) . '</button>
					<span class="spinner"></span>
					<span class="results"></span>
				</p>
			</form>
		</div>' . $this->debug_container .
	'</div>';
