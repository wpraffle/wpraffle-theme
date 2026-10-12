/* global jQuery, wp */
( function ( $ ) {
	'use strict';

	$( function () {


		// v1.4.0 responsive Control Centre navigation.
		$( '.wprt-admin__mobile-toggle' ).on( 'click', function () {
			var open = ! $( 'body' ).hasClass( 'wprt-nav-open' );
			$( 'body' ).toggleClass( 'wprt-nav-open', open );
			$( this ).attr( 'aria-expanded', open ? 'true' : 'false' );
		} );

		$( '.wprt-admin__nav-item' ).on( 'click', function () {
			$( 'body' ).removeClass( 'wprt-nav-open' );
			$( '.wprt-admin__mobile-toggle' ).attr( 'aria-expanded', 'false' );
		} );

		$( window ).on( 'resize.wprtControlNav', function () {
			if ( window.innerWidth >= 992 ) {
				$( 'body' ).removeClass( 'wprt-nav-open' );
				$( '.wprt-admin__mobile-toggle' ).attr( 'aria-expanded', 'false' );
			}
		} );
		// WP colour pickers.
		$( '.wpr-color-picker' ).wpColorPicker();

		// Media picker buttons.
		$( '.wpr-media-button' ).on( 'click', function ( e ) {
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
		$( '.wpr-color-picker' ).on( 'change input', function () {
			$( '.wpr-preset' ).removeClass( 'is-active' );
			$( 'input[name="wpr_settings[preset]"]' ).val( 'custom' );
		} );

		// v1.4.0: Theme Options navigation search.
		$( '#wprt-settings-search' ).on( 'input', function () {
			var query = $.trim( $( this ).val().toLowerCase() );
			var aliases = {};
			if ( window.WPRaffleThemeAdmin && Array.isArray( WPRaffleThemeAdmin.searchIndex ) ) {
				WPRaffleThemeAdmin.searchIndex.forEach( function ( item ) { aliases[ item.tab ] = ( item.label || '' ).toLowerCase(); } );
			}
			$( '.wprt-admin__group' ).each( function () {
				var $group = $( this );
				var visible = 0;
				$group.find( '.wprt-admin__nav-item' ).each( function () {
					var href = $( this ).attr( 'href' ) || '';
					var tabMatch = href.match( /[?&]tab=([^&]+)/ );
					var tab = tabMatch ? tabMatch[1] : '';
					var haystack = ( $( this ).text() + ' ' + ( aliases[ tab ] || '' ) ).toLowerCase();
					var match = ! query || haystack.indexOf( query ) !== -1;
					$( this ).toggle( match );
					if ( match ) { visible++; }
				} );
				$group.toggle( visible > 0 );
			} );
		} );

		// v1.1.0: Repeatable field rows (FAQs / Testimonials).
		$( '.wprt-repeatable' ).each( function () {
			var container = $( this );
			var target    = container.data( 'target' );
			var tpl       = $( '.wprt-template-' + target ).html();

			container.on( 'click', '.wprt-add-row', function () {
				container.find( '.wprt-repeatable-rows' ).append( tpl );
			} );

			container.on( 'click', '.wprt-remove-row', function () {
				var rows = container.find( '.wprt-repeatable-row' );
				if ( rows.length > 1 ) {
					$( this ).closest( '.wprt-repeatable-row' ).remove();
				} else {
					// Clear the fields in the last row instead of removing it.
					$( this ).closest( '.wprt-repeatable-row' ).find( 'input, textarea' ).val( '' );
				}
			} );
		} );

		// Reindex field names on form submit so the array keys are sequential.
		$( '.wpr-form' ).on( 'submit', function () {
			$( '.wprt-repeatable' ).each( function () {
				var target = $( this ).data( 'target' );
				$( this ).find( '.wprt-repeatable-row' ).each( function ( idx ) {
					$( this ).find( 'input, textarea' ).each( function () {
						var name = $( this ).attr( 'name' );
						if ( name ) {
							// Replace the [N] index in the name attribute.
							name = name.replace( /\[\d+\]/, '[' + idx + ']' );
							$( this ).attr( 'name', name );
						}
					} );
				} );
			} );
		} );

		// Media picker for dynamically-added photo fields (delegated).
		$( document ).on( 'click', '.wprt-media-button', function ( e ) {
			e.preventDefault();
			var targetName = $( this ).data( 'target-field' );
			var frame = wp.media( {
				title: 'Select an image',
				button: { text: 'Use this image' },
				library: { type: 'image' },
				multiple: false
			} );
			frame.on( 'select', function () {
				var attachment = frame.state().get( 'selection' ).first().toJSON();
				$( 'input[name="' + targetName + '"]' ).val( attachment.url );
			} );
			frame.open();
		} );

		// v1.2.0 — Drag-and-drop Homepage Builder.
		// jQuery UI Sortable reorders rows; we renumber the hidden order inputs
		// and the visible position badges after every change. The save handler
		// reads sections[key][order], so no PHP change is needed.
		var $builder = $( '#wprt-homepage-builder' );
		if ( $builder.length && $.fn.sortable ) {
			$builder.sortable( {
				items: '.wprt-builder-row',
				handle: '.wprt-builder-row__handle',
				placeholder: 'ui-sortable-placeholder',
				forcePlaceholderSize: true,
				axis: 'y',
				cursor: 'grabbing',
				tolerance: 'pointer',
				stop: renumberBuilder
			} );

			// Reflect the toggle state on the row (dim disabled rows).
			$builder.on( 'change', '.wprt-builder-toggle input', function () {
				$( this ).closest( '.wprt-builder-row' ).toggleClass( 'is-disabled', ! this.checked );
				$( this ).closest( '.wprt-builder-row' ).toggleClass( 'is-enabled', this.checked );
			} );
			// Set initial disabled state.
			$builder.find( '.wprt-builder-row' ).each( function () {
				var on = $( this ).find( '.wprt-builder-toggle input' ).prop( 'checked' );
				$( this ).toggleClass( 'is-disabled', ! on );
			} );

			// Initial numbering.
			renumberBuilder();
		}

		function renumberBuilder() {
			$builder.find( '.wprt-builder-row' ).each( function ( idx ) {
				$( this ).find( '.wprt-builder-order-input' ).val( idx + 1 );
				$( this ).find( '.wprt-builder-row__pos' ).text( idx + 1 );
			} );
		}
	} );
} )( jQuery );

(function () {
    'use strict';
    document.addEventListener('click', function (event) {
        var move = event.target.closest('[data-wprt-move]');
        if (move) {
            var row = move.closest('li'); var list = row.parentNode;
            if (move.dataset.wprtMove === 'up' && row.previousElementSibling) { list.insertBefore(row, row.previousElementSibling); }
            if (move.dataset.wprtMove === 'down' && row.nextElementSibling) { list.insertBefore(row.nextElementSibling, row); }
            move.focus();
        }
        var button = event.target.closest('[data-wprt-preview]');
        if (!button) { return; }
        var panel = button.closest('.wprt-preview-panel');
        var status = panel.querySelector('.wprt-preview-status');
        var body = new FormData(button.closest('form'));
        body.delete('wprt_settings_action'); body.delete('wprt_settings_nonce'); body.delete('wprt_settings_tab');
        body.set('action', 'wprt_preview'); body.set('nonce', button.dataset.nonce);
        button.disabled = true; status.textContent = 'Loading preview…';
        fetch(window.ajaxurl, {method:'POST', credentials:'same-origin', body:body})
            .then(function (response) { if (!response.ok) { throw new Error('Preview request failed'); } return response.json(); })
            .then(function (result) {
                if (!result.success) { throw new Error('Preview request failed'); }
                var url = new URL(result.data.url, window.location.href);
                if (url.origin !== window.location.origin) { throw new Error('Preview must use the same site'); }
                var frame = panel.querySelector('iframe'); frame.src = url.href; frame.hidden = false;
                status.textContent = 'Private preview refreshed. Changes are not published.';
            }).catch(function () { status.textContent = 'Could not load preview. Refresh the settings page and try again.'; })
            .finally(function () { button.disabled = false; });
    });
})();
