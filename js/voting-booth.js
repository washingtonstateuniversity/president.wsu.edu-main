( function( $, voting_booth ) {
	"use strict";

	$( ".wsu-voting-booth" ).on( "change", "input[name='vote']", function() {
		$( this ).closest( ".wsu-voting-booth" ).find( "button" ).prop( "disabled", false );
	} );

	$( ".wsu-voting-booth" ).on( "submit", function( e ) {
		e.preventDefault();

		var submit_button = $( this ).find( "button" );
		var tally_total = $( this ).find( ".vote-tally span" );
		var voting_options = $( this ).find( "label" );
		var thanks_message = $( this ).find( ".thank-you" );

		submit_button.prop( "disabled", true );

		var data = {
			action: "wsu_cast_vote",
			nonce: voting_booth.nonce,
			post_id: $( this ).data( "post-id" ),
			vote: $( this ).find( "input[name='vote']:checked" ).val()
		};

		$.post( voting_booth.ajax_url, data, function( response ) {
			var response_data = $.parseJSON( response );

			tally_total.fadeOut( 200, function() {
				$( this ).text( response_data.new_tally );
			} ).fadeIn( 200 );

			voting_options.fadeOut( 200 );
			submit_button.fadeOut( 200 );
			thanks_message.attr( "aria-hidden", false );
		} );
	} );

}( jQuery, window.voting_booth ) );
