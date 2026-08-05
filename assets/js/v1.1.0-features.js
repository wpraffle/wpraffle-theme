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
		var mode = ( typeof wprThemeData !== 'undefined' && wprThemeData.darkMode ) ? wprThemeData.darkMode : 'off';
		if ( 'off' === mode ) {
			return;
		}

		// Auto: detect OS preference if no stored choice.
		var stored = localStorage.getItem( 'wprt-theme' );
		if ( ! stored && 'auto' === mode ) {
			stored = window.matchMedia( '(prefers-color-scheme: dark)' ).matches ? 'dark' : 'light';
		}
		if ( 'dark' === stored ) {
			document.documentElement.setAttribute( 'data-theme', 'dark' );
		}

		// Inject the toggle into the header actions.
		var actions = document.querySelector( '.wpr-header__actions' );
		if ( actions ) {
			var toggle = document.createElement( 'button' );
			toggle.className = 'wprt-dark-toggle wpr-icon-btn';
			toggle.setAttribute( 'aria-label', 'Toggle dark mode' );
			toggle.type = 'button';
			toggle.innerHTML = '<i class="fa-solid fa-moon"></i><i class="fa-solid fa-sun"></i>';
			toggle.addEventListener( 'click', function () {
				var isDark = 'dark' === document.documentElement.getAttribute( 'data-theme' );
				if ( isDark ) {
					document.documentElement.removeAttribute( 'data-theme' );
					localStorage.setItem( 'wprt-theme', 'light' );
				} else {
					document.documentElement.setAttribute( 'data-theme', 'dark' );
					localStorage.setItem( 'wprt-theme', 'dark' );
				}
			} );
			actions.insertBefore( toggle, actions.firstChild );
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
