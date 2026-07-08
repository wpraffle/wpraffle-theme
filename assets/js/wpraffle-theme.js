/* global Swiper, Fancybox, diamondData */
( function () {
	'use strict';

	document.addEventListener( 'DOMContentLoaded', function () {
		initMobileNav();
		initCarousels();
		initFancybox();
		initStickyHeader();
	} );

	/**
	 * Mobile nav: toggle the off-canvas menu.
	 */
	function initMobileNav() {
		var toggle = document.querySelector( '.diamond-menu-toggle' );
		var nav = document.querySelector( '.diamond-nav' );
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
	 * Swiper carousels. Any element with [data-diamond-carousel] becomes a slider.
	 */
	function initCarousels() {
		if ( typeof Swiper === 'undefined' ) {
			return;
		}
		document.querySelectorAll( '[data-diamond-carousel]' ).forEach( function ( el ) {
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
				var opts = el.getAttribute( 'data-diamond-carousel-options' );
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
		var header = document.querySelector( '.diamond-header' );
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
} )();
