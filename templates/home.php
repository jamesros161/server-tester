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

return '<div class="test_form">
		<form id="php_timeout" action="timeout.php" method ="POST">
			<h4 class="test_heading">Test PHP Max Execution Time</h4>
			<p>Current max_execution_time value = ' . ini_get( 'max_execution_time' ) . 's</p>
			<input type="hidden" name="test" value="php_timeout" />
			<input name="time" type="number" />
			<input type="submit" value="Start Test" />
		</form>
		<form id="ini_set_form" method="POST">
			<h4 class="test_heading">Test ini_set( \'max_execution_time\')</h4>
			<p>Current max_execution_time value = ' . ini_get( 'max_execution_time' ) . 's</p>
			<input type="hidden" name="test" value="ini_set_timeout" />
			<input type="hidden" name="ajax" value="true" />
			<input name="time" type="number" />
			<input type="submit" value="Start Test" />
			<p id="ini_set_result"><img class="spinner" width="50px" height="50px" src="/assets/images/spinner.gif" /></p>
		</form>
		<form id="test_gateway_timeout" method="POST">
			<h4 class="test_heading">Test Gateway ( NGINX/Apache > FastCGI Timeout )</h4>
			<input type="hidden" name="test" value="gateway_timeout" />
			<input type="hidden" name="ajax" value="true" />
			<input type="submit" value="Start Test" />
			<p id="gateway_test_result"><img class="spinner" width="50px" height="50px" src="/assets/images/spinner.gif" /></p>
		</form>
	</div>' . $this->debug_container;
