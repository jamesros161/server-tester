/**
 * File: ajax.js
 *
 * @since 0.1
 *
 * @package server_tester
 */
( function ( $ ) {
	$( document ).ready( function() {
		isPhpFcgi();
		$( '#ini_set_form' ).find( 'input[type=submit]' ).on( 'click', iniSetForm );
		$( '#test_gateway_timeout' ).find( 'input[type=submit]' ).on( 'click', gatewayTimeout );
	} );

	function isPhpFcgi() {
		$.ajax( {
			method: "POST",
			url: ajaxurl,
			data: {
				'action' : 'timeout_test',
				'test'   : 'is_php_fcgi',
				'ajax'   : 'true',
			},
			contentType: 'application/json',
			dataType: 'json',
			success: function( result ) {
				console.log( 'PHP SAPI: ' . result );
				if ( ! result.includes( 'fcgi' ) ) {
					console.log( 'PHP SAPI is not FCGI' );
					$( '#test_gateway_timeout').find('input[type=submit]').attr( 'disabled', true );
					$( '#test_gateway_timeout').find('p').html( 'This test is only available on FCGI php handlers.' );
				} else {
					console.log( 'PHP SAPI is FCGI' );
				}
			},
			error: function( error ) {
				console.log( error );
			},
		} );
	}

	function iniSetForm( event ) {
		console.log( 'Start Timeout Test');
		var inputs = $( event.currentTarget ).closest( 'form').find( 'input' );
		var postData = {};
		$( inputs ).each( function() {
			if ( $( this ).attr( 'type') !== 'submit' ) {
				var name  = $( this ).attr( 'name' );
				var value = $( this ).val();
				postData[ name ] = value;
			} else {
				$( this).attr("disabled", true);
			}
		} );

		spinner = $( '#ini_set_form' ).find( '.spinner' );
		$( spinner ).show();

		event.preventDefault();
		$.ajax( {
			type: "POST",
			url: "wp-admin/admin-ajax.php",
			data: postData,
			success: function( result ) {
				var result;
				try {
					result = $.parseJSON( result );
					$( spinner ).hide();
					$( '#ini_set_result' ).html( result.data );
				}
				catch(err) {
					result = $.parseHTML( result );
					var seconds,
						messageArray = $( result ).find( 'th' )[0].childNodes[1].data.split(' ');
					$( messageArray ).each( function(i) {
						if ( this == 'seconds' ) {
							seconds = messageArray[ i - 1 ];
						}
					} );
					$( spinner ).hide();
					$( '#ini_set_result' ).html( 'Max Execution timeout at ' + seconds + ' seconds.' );
				}
			},
			error: function( error ) {
				$( spinner ).hide();
				$( '#ini_set_result' ).html( 'Test Failed. See Console Log for details.' );
				console.log( error );
			},
			timeout: ( parseInt( postData.time ) + 10 ) * 1000
		} );
	}

	function gatewayTimeout( event ) {
		console.log( 'Start Gateway Timeout Test');
		event.preventDefault();

		var inputs = $( event.currentTarget ).closest( 'form').find( 'input' ),
			postData = {},
			startTime,
			startDate,
			endTime,
			endDate;

		$( inputs ).each( function() {
			if ( $( this ).attr( 'type') !== 'submit' ) {
				var name  = $( this ).attr( 'name' );
				var value = $( this ).val();
				postData[ name ] = value;
			} else {
				$( this).attr("disabled", true);
			}
		} );
		startDate = new Date();
		startTime = startDate.getTime();

		spinner = $( '#test_gateway_timeout' ).find( '.spinner' );
		$( spinner ).show();

		$.ajax( {
			type: "POST",
			url: "/timeout.php",
			data: postData,
			success: function( result ) {
				endTime = getTime();
				duration = Math.floor( ( endTime - startTime ) / 1000 );
				$( spinner ).hide();
			},
			error: function( error ) {
				endDate = new Date();
				endTime = endDate.getTime();
				duration = Math.floor( ( endTime - startTime ) / 1000 );
				$( spinner ).hide();
				$( '#gateway_test_result' ).html( 'Gateway Timeout occured at ' + duration + ' seconds.' );
			},
		} );
	}
} ( jQuery ) );