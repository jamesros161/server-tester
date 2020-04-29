/**
 * File: ajax.js
 *
 * @since 0.1
 *
 * @package server_tester
 */

( function ( $ ) {
	/**
	 * Run This on page load.
	 *
	 * @since 0.1
	 */
	$( document ).ready( function() {
		isPhpFcgi();
		$( 'form' ).find( '.button' ).on( 'click', iniSetForm );
	} );
	/**
	 * isPhpFcgi
	 *
	 * Determines if the current SAPI is FCGI or not.
	 *
	 * @since 0.1
	 */
	function isPhpFcgi() {
		if ( ! ST.isFcgi ) {
			console.log( 'PHP SAPI is not FCGI' );
			$( '#test_gateway_timeout').find('.button').attr( 'disabled', true );
			$( '#test_gateway_timeout').find('p').html( 'This test is only available on FCGI php handlers.' );
		} else {
			console.log( 'PHP SAPI is FCGI' );
		}
	}

	/**
	 * Parse Input Fields
	 *
	 * Handles input fields and preps them for Ajax.
	 *
	 * @since 0.1
	 * @param {array} inputs
	 */
	function parseInputFields( inputs, postData ) {
		$( inputs ).each( function() {
			if ( $( this ).attr( 'type') !== 'submit' ) {
				var name  = $( this ).attr( 'name' );
				var value = $( this ).val();
				postData[ name ] = value;
			}
		} );

		return postData;
	}

	/**
	 * iniSetForm
	 *
	 * Ajax Callback for testing php_ini_set()
	 *
	 * @since 0.1
	 *
	 * @param {object} event
	 */
	function iniSetForm( event ) {
		var submitButton = event.currentTarget,
			form         = $( submitButton ).closest( 'form' ),
			target = {
				'submitButton'    : submitButton,
				'form'            : form,
				'inputs'          : $( form ).find( 'input' ),
				'spinner'         : $( form ).find( '.spinner' ),
				'resultContainer' : $( form ).find( '.results' ),
				'postData'        : {}
			};
		startDate = new Date();
		target.startTime = startDate.getTime();

		$( '.button' ).attr( 'disabled', true );

		target.postData = parseInputFields( target.inputs, target.postData );

		$( target.spinner ).addClass( 'is-active');
		$( target.resultContainer ).html('');

		event.preventDefault();

		$.ajax( {
			type: "POST",
			url: ajaxurl,
			data: target.postData,
			target: target,
			success: ajaxSuccess,
			error: ajaxError,
			timeout: ( parseInt( target.postData.time ) + 10 ) * 1000
		} );
	}

	/**
	 * ajaxSuccess
	 *
	 * Handles Successful Ajax Calls
	 *
	 * @since 0.1
	 *
	 * @param {object} result          The result to handle.
	 */
	function ajaxSuccess( result ) {
		result = $.parseJSON( result );
		$( this.target.spinner ).removeClass( 'is-active');
		$( this.target.resultContainer ).html( result.data );

		console.log( 'End Timeout Test: Success');
		$( '.button' ).attr( "disabled", false );
	}

	/**
	 * Handles Failed Ajax Calls
	 *
	 * @since 0.1
	 *
	 * @param {object} error          The error to handle.
	 */
	function ajaxError( error ) {
		$( this.target.spinner ).removeClass( 'is-active');
		$( '.button' ).attr( "disabled", false );

		endDate = new Date();
		endTime = endDate.getTime();
		duration = Math.floor( ( endTime - this.target.startTime ) / 1000 );

		if ( error.status === 504 ) {
			$( this.target.resultContainer ).html( 'Gateway Timeout occured after ' + duration + ' seconds.' );
		} else {
			endDate = new Date();
			endTime = endDate.getTime();
			duration = Math.floor( ( endTime - this.target.startTime ) / 1000 );
			$( this.target.resultContainer ).html( 'Test Failed after ' + duration + ' seconds. See Console Log for details.' );
			console.log( 'End Timeout Test: Failed');
			console.log( error );
		}
	}
} ( jQuery ) );