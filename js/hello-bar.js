jQuery(function( $ ){

	// Show Hello Bar after 200px
	$( document ).on( 'scroll', function() {

		if( $( document ).scrollTop() > 10 ) {//change value when you want it to appear 

			$( '.hello-bar' ).fadeIn();

		} else {

			$( '.hello-bar' ).fadeOut();

		}

	});

});