/* global jQuery, wp */
( function ( $ ) {
	'use strict';

	$( function () {
		// WP colour pickers.
		$( '.diamond-color-picker' ).wpColorPicker();

		// Media picker buttons.
		$( '.diamond-media-button' ).on( 'click', function ( e ) {
			e.preventDefault();
			var target = $( this ).data( 'target' );
			var frame = wp.media( {
				title: 'Select an image',
				button: { text: 'Use this image' },
				library: { type: 'image' },
				multiple: false
			} );
			frame.on( 'select', function () {
				var attachment = frame.state().get( 'selection' ).first().toJSON();
				$( '#' + target ).val( attachment.url ).trigger( 'change' );
			} );
			frame.open();
		} );

		// Mark "Custom" preset active if any colour changes.
		$( '.diamond-color-picker' ).on( 'change input', function () {
			$( '.diamond-preset' ).removeClass( 'is-active' );
			$( 'input[name="diamond[preset]"]' ).val( 'custom' );
		} );
	} );
} )( jQuery );
