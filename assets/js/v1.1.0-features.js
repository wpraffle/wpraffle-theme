/* global wprThemeData, jQuery */
( function () {
	'use strict';

	document.addEventListener( 'DOMContentLoaded', function () {
		initDarkMode();
		initPromoDismiss();
		initMobileCtaScroll();
		initSocialProof();
		initAgeGate();
	} );

	/* -----------------------------------------------------------------
	 * Dark mode toggle
	 * ----------------------------------------------------------------- */
	function initDarkMode() {
		var configured = ( typeof wprThemeData !== 'undefined' && wprThemeData.darkMode ) ? wprThemeData.darkMode : 'off';
		if ( 'off' === configured ) {
			document.documentElement.removeAttribute( 'data-theme' );
			document.documentElement.style.colorScheme = '';
			return;
		}

		var media = window.matchMedia ? window.matchMedia( '(prefers-color-scheme: dark)' ) : null;
		var stored = localStorage.getItem( 'wprt-theme' );
		var current = document.documentElement.getAttribute( 'data-theme' );

		if ( 'dark' !== current && 'light' !== current ) {
			if ( 'dark' === stored || 'light' === stored ) {
				current = stored;
			} else if ( 'auto' === configured && media ) {
				current = media.matches ? 'dark' : 'light';
			} else {
				current = 'light';
			}
			applyMode( current, false );
		}

		var actions = document.querySelector( '.wpr-header__actions' );
		if ( ! actions || actions.querySelector( '.wprt-dark-toggle' ) ) {
			return;
		}

		var toggle = document.createElement( 'button' );
		toggle.className = 'wprt-dark-toggle wprt-mode-toggle';
		toggle.type = 'button';
		toggle.innerHTML =
			'<span class="wprt-mode-toggle__track" aria-hidden="true">' +
				'<span class="wprt-mode-toggle__icon wprt-mode-toggle__icon--sun"><i class="fa-solid fa-sun"></i></span>' +
				'<span class="wprt-mode-toggle__icon wprt-mode-toggle__icon--moon"><i class="fa-solid fa-moon"></i></span>' +
				'<span class="wprt-mode-toggle__thumb"></span>' +
			'</span>';

		syncToggle();
		toggle.addEventListener( 'click', function () {
			var isDark = 'dark' === document.documentElement.getAttribute( 'data-theme' );
			applyMode( isDark ? 'light' : 'dark', true );
			syncToggle();
		} );
		actions.insertBefore( toggle, actions.firstChild );

		// In Auto mode, follow OS changes until the visitor makes an explicit
		// selection. Once a choice is stored, it wins on future visits.
		if ( 'auto' === configured && media ) {
			var systemChange = function ( event ) {
				if ( localStorage.getItem( 'wprt-theme' ) ) {
					return;
				}
				applyMode( event.matches ? 'dark' : 'light', false );
				syncToggle();
			};
			if ( media.addEventListener ) {
				media.addEventListener( 'change', systemChange );
			} else if ( media.addListener ) {
				media.addListener( systemChange );
			}
		}

		function applyMode( mode, remember ) {
			document.documentElement.setAttribute( 'data-theme', mode );
			document.documentElement.style.colorScheme = mode;
			if ( remember ) {
				localStorage.setItem( 'wprt-theme', mode );
			}
			document.dispatchEvent( new CustomEvent( 'wprt:modechange', { detail: { mode: mode } } ) );
		}

		function syncToggle() {
			if ( ! toggle ) {
				return;
			}
			var isDark = 'dark' === document.documentElement.getAttribute( 'data-theme' );
			toggle.setAttribute( 'aria-pressed', isDark ? 'true' : 'false' );
			toggle.setAttribute( 'aria-label', isDark ? 'Switch to day mode' : 'Switch to night mode' );
			toggle.setAttribute( 'title', isDark ? 'Day mode' : 'Night mode' );
		}
	}

	/* -----------------------------------------------------------------
	 * Promo bar dismiss
	 * ----------------------------------------------------------------- */
	function initPromoDismiss() {
		var close = document.querySelector( '.wprt-promo-bar__close' );
		if ( ! close ) {
			return;
		}
		close.addEventListener( 'click', function () {
			document.cookie = 'wprt_promo_dismissed=1; max-age=' + ( 60 * 60 * 24 ) + '; path=/';
			close.closest( '.wprt-promo-bar' ).style.display = 'none';
		} );
	}

	/* -----------------------------------------------------------------
	 * Mobile CTA hide on scroll down, show on scroll up
	 * ----------------------------------------------------------------- */
	function initMobileCtaScroll() {
		var bar = document.querySelector( '.wprt-mobile-cta' );
		if ( ! bar ) {
			return;
		}
		document.body.classList.add( 'has-mobile-cta' );
		var lastY = 0;
		window.addEventListener( 'scroll', function () {
			var y = window.scrollY;
			if ( y > lastY && y > 200 ) {
				bar.style.transform = 'translateY(100%)';
			} else {
				bar.style.transform = 'translateY(0)';
			}
			lastY = y;
		}, { passive: true } );
		bar.style.transition = 'transform 0.3s ease';
	}

	/* -----------------------------------------------------------------
	 * Social proof toasts — polls the AJAX endpoint periodically
	 * ----------------------------------------------------------------- */
	function initSocialProof() {
		var container = document.getElementById( 'wprt-social-proof' );
		if ( ! container || typeof wprThemeData === 'undefined' ) {
			return;
		}
		// Defensive minimum: never allow a polling interval below 10s, even if
		// the saved setting is missing/0/garbage. A 0 interval would hammer
		// admin-ajax and freeze the page.
		var rawFreq = parseInt( wprThemeData.socialProofFreq, 10 );
		var freq = ( isNaN( rawFreq ) || rawFreq < 10 ? 30 : rawFreq ) * 1000;
		var pos = wprThemeData.socialProofPos || 'bottom-left';
		container.classList.add( 'pos-' + pos );

		var showToast = function () {
			// Pause when the tab is hidden or a previous toast is still on screen
			// (avoids stacking + redundant requests).
			if ( document.hidden ) {
				return;
			}
			if ( container.querySelector( '.wprt-toast:not(.is-leaving)' ) ) {
				return;
			}
			fetch( wprThemeData.ajaxUrl + '?action=wprt_social_proof&nonce=' + wprThemeData.nonce )
				.then( function ( r ) { return r.json(); } )
				.then( function ( res ) {
					if ( ! res.success ) {
						return;
					}
					var d = res.data;
					var toast = document.createElement( 'div' );
					toast.className = 'wprt-toast';
					toast.innerHTML =
						'<div class="wprt-toast__icon"><i class="fa-solid fa-ticket"></i></div>' +
						'<div class="wprt-toast__body">' +
						'<strong>' + d.name + '</strong> ' +
						'entered the ' + d.prize + ' draw' +
						'<span class="wprt-toast__time">' + d.time + ' ago</span>' +
						'</div>';
					container.appendChild( toast );
					setTimeout( function () {
						toast.classList.add( 'is-leaving' );
						setTimeout( function () { toast.remove(); }, 300 );
					}, 5000 );
				} )
				.catch( function () {} );
		};

		// Initial delay + then poll. Pause polling entirely when the tab is hidden.
		setTimeout( showToast, 8000 );
		document.addEventListener( 'visibilitychange', function () {
			if ( ! document.hidden ) { setTimeout( showToast, 2000 ); }
		} );
		setInterval( showToast, freq );
	}

	/* -----------------------------------------------------------------
	 * Age gate
	 * ----------------------------------------------------------------- */
	function initAgeGate() {
		var gate = document.getElementById( 'wprt-age-gate' );
		if ( ! gate ) {
			return;
		}
		var yes = gate.querySelector( '.wprt-age-gate__yes' );
		if ( yes ) {
			yes.addEventListener( 'click', function () {
				var days = parseInt( yes.getAttribute( 'data-duration' ) || '30', 10 );
				document.cookie = 'wprt_age_verified=1; max-age=' + ( days * 86400 ) + '; path=/';
				gate.classList.add( 'is-hidden' );
			} );
		}
	}
} )();
