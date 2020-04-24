/**
 * File: accordian.js
 *
 * @since 0.1
 *
 * @package server_tester
 */

 $( document ).ready( function() {
	 $( "#debug_container").accordion(
		{
			heightStyle: "content",
			collapsible: true
		}
	 );
 } );