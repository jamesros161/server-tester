<?php
/**
 * File: timeout.php
 *
 * Handles Timeout Submissions.
 *
 * @since 0.1
 *
 * @package server_tester
 */

// Require Main File.
require_once ST_BASEDIR . '/inc/class-server-tester.php';
$tester            = new Server_Tester();
$referrer_is_valid = $tester->is_valid_referrer();
$result            = '';
if ( ! $referrer_is_valid ) {
	header( $tester->partials->nonce_error_header );
	$content_markup = $tester->partials->nonce_error_message;
} else {
	$result = $tester->timeout->run_test();
	if ( false !== $result ) {
		$content_markup = '
			<div class="test_result">
				<h3>Test Results</h3>
				<p>' . $result . '</p>
			</div>
		';
	} else {
		$content_markup = '
			<div class="test_result">
				<h3>Test Results</h3>
				<p>The requested test failed to run.</p>
			</div>
		';
	}

	$content_markup .= $tester->partials->debug_container;
}
if ( isset( $tester->form_post['ajax'] ) ) {
	echo $result;
} else {
	echo $tester->partials->header . $content_markup . $tester->partials->footer;
}


