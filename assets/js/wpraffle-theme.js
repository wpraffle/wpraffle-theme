/* global Swiper, Fancybox, wprThemeData */
( function () {
	'use strict';

	document.addEventListener( 'DOMContentLoaded', function () {
		initMobileNav();
		initCarousels();
		initFancybox();
		initStickyHeader();
		initHeaderScrollEffect();
		initFaqAccordion();
		initCountdown();
	} );

	/**
	 * Mobile nav: toggle the off-canvas menu.
	 */
	function initMobileNav() {
		var toggle = document.querySelector( '.wpr-menu-toggle' );
		var nav = document.querySelector( '.wpr-nav' );
		if ( ! toggle || ! nav ) {
			return;
		}

		toggle.addEventListener( 'click', function () {
			nav.classList.toggle( 'is-open' );
			var open = nav.classList.contains( 'is-open' );
			toggle.setAttribute( 'aria-expanded', open ? 'true' : 'false' );
			toggle.innerHTML = open ? '<i class="fa-solid fa-xmark"></i>' : '<i class="fa-solid fa-bars"></i>';
			document.body.style.overflow = open ? 'hidden' : '';
		} );

		// Close on link click (handy for one-pagers).
		nav.querySelectorAll( 'a' ).forEach( function ( link ) {
			link.addEventListener( 'click', function () {
				if ( nav.classList.contains( 'is-open' ) ) {
					nav.classList.remove( 'is-open' );
					toggle.setAttribute( 'aria-expanded', 'false' );
					toggle.innerHTML = '<i class="fa-solid fa-bars"></i>';
					document.body.style.overflow = '';
				}
			} );
		} );
	}

	/**
	 * Swiper carousels. Any element with [data-wpr-carousel] becomes a slider.
	 */
	function initCarousels() {
		if ( typeof Swiper === 'undefined' ) {
			return;
		}
		document.querySelectorAll( '[data-wpr-carousel]' ).forEach( function ( el ) {
			var config = {
				slidesPerView: 1.2,
				spaceBetween: 16,
				grabCursor: true,
				pagination: { el: el.querySelector( '.swiper-pagination' ), clickable: true },
				navigation: { nextEl: el.querySelector( '.swiper-button-next' ), prevEl: el.querySelector( '.swiper-button-prev' ) },
				breakpoints: {
					576: { slidesPerView: 2.2, spaceBetween: 16 },
					992: { slidesPerView: 3, spaceBetween: 24 },
					1200: { slidesPerView: 4, spaceBetween: 24 }
				}
			};
			try {
				var opts = el.getAttribute( 'data-wpr-carousel-options' );
				if ( opts ) {
					Object.assign( config, JSON.parse( opts ) );
				}
			} catch ( e ) { /* swallow bad JSON */ }

			/* eslint-disable no-new */
			new Swiper( el, config );
		} );
	}

	/**
	 * Fancybox 6 lightbox for [data-fancybox] galleries.
	 */
	function initFancybox() {
		if ( typeof Fancybox === 'undefined' ) {
			return;
		}
		Fancybox.bind( '[data-fancybox]', {
			Toolbar: { display: { left: [ 'infobar' ], middle: [], right: [ 'slideshow', 'fullscreen', 'thumbs', 'close' ] } }
		} );
	}

	/**
	 * Add a shadow to the sticky header once the page scrolls.
	 */
	function initStickyHeader() {
		var header = document.querySelector( '.wpr-header' );
		if ( ! header ) {
			return;
		}
		var onScroll = function () {
			if ( window.scrollY > 10 ) {
				header.classList.add( 'is-scrolled' );
			} else {
				header.classList.remove( 'is-scrolled' );
			}
		};
		window.addEventListener( 'scroll', onScroll, { passive: true } );
		onScroll();
	}

	/**
	 * v1.1.0: Header scroll effects (shrink / hide / shadow) driven by the
	 * headerScroll setting passed via wprThemeData.
	 */
	function initHeaderScrollEffect() {
		var mode = ( typeof wprThemeData !== 'undefined' && wprThemeData.headerScroll ) ? wprThemeData.headerScroll : 'shrink';
		var header = document.querySelector( '.wpr-header' );
		if ( ! header || 'none' === mode ) {
			return;
		}
		var lastY = 0;

		var onScroll = function () {
			var y = window.scrollY;

			if ( 'hide' === mode ) {
				if ( y > lastY && y > 120 ) {
					header.classList.add( 'hide-up' );
				} else {
					header.classList.remove( 'hide-up' );
				}
			}
			// 'shrink' + 'shadow' handled by CSS via .is-scrolled (initStickyHeader).
			lastY = y;
		};
		window.addEventListener( 'scroll', onScroll, { passive: true } );
	}

	/**
	 * v1.1.0: FAQ accordion toggle.
	 */
	function initFaqAccordion() {
		document.querySelectorAll( '.wprt-faq-question' ).forEach( function ( btn ) {
			btn.addEventListener( 'click', function () {
				var answer = btn.nextElementSibling;
				var expanded = btn.getAttribute( 'aria-expanded' ) === 'true';
				// Close all others (single-open accordion).
				document.querySelectorAll( '.wprt-faq-question' ).forEach( function ( other ) {
					other.setAttribute( 'aria-expanded', 'false' );
					if ( other.nextElementSibling ) {
						other.nextElementSibling.style.display = 'none';
					}
				} );
				if ( ! expanded ) {
					btn.setAttribute( 'aria-expanded', 'true' );
					if ( answer ) {
						answer.style.display = 'block';
					}
				}
			} );
		} );
	}

	/**
	 * v1.1.0: Homepage countdown banner.
	 */
	function initCountdown() {
		var el = document.querySelector( '.wprt-countdown-banner' );
		if ( ! el ) {
			return;
		}
		var target = el.getAttribute( 'data-draw-date' );
		if ( ! target ) {
			return;
		}
		var end = new Date( target ).getTime();

		var tick = function () {
			var now = Date.now();
			var diff = end - now;
			if ( diff < 0 ) {
				diff = 0;
			}
			var days = Math.floor( diff / 86400000 );
			var hours = Math.floor( ( diff % 86400000 ) / 3600000 );
			var mins = Math.floor( ( diff % 3600000 ) / 60000 );
			var secs = Math.floor( ( diff % 60000 ) / 1000 );

			var set = function ( cls, val ) {
				var node = el.querySelector( cls );
				if ( node ) {
					node.textContent = String( val ).padStart( 2, '0' );
				}
			};
			set( '.wprt-cd-days', days );
			set( '.wprt-cd-hours', hours );
			set( '.wprt-cd-mins', mins );
			set( '.wprt-cd-secs', secs );
		};
		tick();
		setInterval( tick, 1000 );
	}
} )();
