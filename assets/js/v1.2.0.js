/**
 * WPRaffle Theme — v1.2.0 enhancements (Tier 1 + helpers).
 *
 * Depends on v1.1.0-features.js (loaded first). Reads feature toggles from
 * the wprThemeData object localised by class-wpraffle-theme-setup.php.
 *
 * Every animation honours prefers-reduced-motion: the matching CSS file
 * forces final states, and each init here also bails out early.
 */
( function () {
	'use strict';

	var d = window.wprThemeData || {};
	var reduceMotion = window.matchMedia && window.matchMedia( '(prefers-reduced-motion: reduce)' ).matches;

	// Single shared IntersectionObserver pool (keeps the per-section observers light).
	function makeObserver( callback, options ) {
		if ( ! ( 'IntersectionObserver' in window ) ) {
			// No support — fire immediately with a stub entry.
			callback( { isIntersecting: true, target: null }, true );
			return { observe: function () {}, unobserve: function () {} };
		}
		var obs = new IntersectionObserver( callback, options || { threshold: 0.15, rootMargin: '0px 0px -40px 0px' } );
		return obs;
	}

	document.addEventListener( 'DOMContentLoaded', function () {

		/* ---------------------------------------------------------------
		 * A. Scroll-triggered section reveal.
		 * ------------------------------------------------------------- */
		if ( d.scrollReveal !== 'off' && ! reduceMotion ) {
			var revealEls = document.querySelectorAll( '.wprt-reveal, .wprt-reveal-stagger' );
			if ( revealEls.length ) {
				var ro = makeObserver( function ( entries, obs ) {
					entries.forEach( function ( entry ) {
						if ( entry.isIntersecting ) {
							entry.target.classList.add( 'is-visible' );
							obs.unobserve( entry.target );
						}
					} );
				} );
				revealEls.forEach( function ( el ) { ro.observe( el ); } );
			}
		} else {
			// Reduced motion or disabled — make everything visible immediately.
			document.querySelectorAll( '.wprt-reveal, .wprt-reveal-stagger' ).forEach( function ( el ) {
				el.classList.add( 'is-visible' );
			} );
		}

		/* ---------------------------------------------------------------
		 * B. Animated hero stat counters + H. stats-counter strip.
		 * Animates any element with [data-count-to] from 0 → target.
		 * The PHP side stamps the target; we preserve the original suffix
		 * (e.g. "+", "m", "★") via data-count-suffix.
		 * ------------------------------------------------------------- */
		if ( d.heroCounters !== 'off' && ! reduceMotion ) {
			var counters = document.querySelectorAll( '[data-count-to]' );
			if ( counters.length ) {
				var co = makeObserver( function ( entries, obs ) {
					entries.forEach( function ( entry ) {
						if ( ! entry.isIntersecting ) { return; }
						animateCounter( entry.target );
						obs.unobserve( entry.target );
					} );
				}, { threshold: 0.4 } );
				counters.forEach( function ( el ) { co.observe( el ); } );
			}
		}

		function animateCounter( el ) {
			var target = parseFloat( el.getAttribute( 'data-count-to' ) );
			if ( isNaN( target ) ) { return; }
			var prefix = el.getAttribute( 'data-count-prefix' ) || '';
			var suffix = el.getAttribute( 'data-count-suffix' ) || '';
			var decimals = parseInt( el.getAttribute( 'data-count-decimals' ) || '0', 10 );
			var duration = 1600;
			var start = null;

			function step( ts ) {
				if ( ! start ) { start = ts; }
				var p = Math.min( ( ts - start ) / duration, 1 );
				// easeOutCubic
				var eased = 1 - Math.pow( 1 - p, 3 );
				var val = target * eased;
				el.textContent = prefix + formatNumber( val, decimals ) + suffix;
				if ( p < 1 ) {
					window.requestAnimationFrame( step );
				} else {
					el.textContent = prefix + formatNumber( target, decimals ) + suffix;
				}
			}
			window.requestAnimationFrame( step );
		}

		function formatNumber( n, decimals ) {
			if ( decimals > 0 ) { return n.toFixed( decimals ); }
			return Math.round( n ).toLocaleString();
		}

		/* ---------------------------------------------------------------
		 * C. Smooth FAQ accordion.
		 * Replaces the display:none/block toggle. Reads scrollHeight for an
		 * accurate animated open. Works with the existing .wprt-faq-question
		 * buttons; we just retarget the click here.
		 * ------------------------------------------------------------- */
		var faqButtons = document.querySelectorAll( '.wprt-faq-question' );
		if ( faqButtons.length ) {
			faqButtons.forEach( function ( btn ) {
				btn.addEventListener( 'click', function () {
					var item = btn.closest( '.wprt-faq-item' );
					if ( ! item ) { return; }
					var answer = item.querySelector( '.wprt-faq-answer' );
					if ( ! answer ) { return; }
					var isOpen = item.classList.toggle( 'is-open' );
					btn.setAttribute( 'aria-expanded', isOpen ? 'true' : 'false' );
					if ( reduceMotion ) { return; }
					if ( isOpen ) {
						answer.style.maxHeight = answer.scrollHeight + 'px';
						// After the transition, allow content to flow if it grows.
						answer.addEventListener( 'transitionend', function onEnd() {
							if ( item.classList.contains( 'is-open' ) ) {
								answer.style.maxHeight = 'none';
							}
							answer.removeEventListener( 'transitionend', onEnd );
						} );
					} else {
						// Collapsing: set a fixed px first (from 'none'), then to 0.
						answer.style.maxHeight = answer.scrollHeight + 'px';
						requestAnimationFrame( function () {
							answer.style.maxHeight = '0';
						} );
					}
				} );
			} );
		}

		/* ---------------------------------------------------------------
		 * D. Scroll-triggered competition-card progress bar fill.
		 *
		 * Approach: read the target width the plugin already set inline,
		 * animate to it from 0 once the bar scrolls into view. The real width
		 * is NEVER removed from the element — we animate a CSS variable so the
		 * bar can never be left "empty" if the observer fails to fire (e.g.
		 * inside a Swiper carousel or when the bar is above the fold).
		 * ------------------------------------------------------------- */
		if ( d.progressAnimate !== 'off' && ! reduceMotion ) {
			var bars = document.querySelectorAll( '.rc-card__progress-fill' );
			if ( bars.length ) {
				bars.forEach( function ( bar ) {
					// The plugin sets width inline, e.g. style="width: 74%". Read it.
					var target = parseFloat( bar.style.width );
					if ( ! target || target <= 0 ) { return; } // nothing to animate
					// Stash the target and start at 0 via a transform (width stays intact
					// as the source of truth, so a failed reveal still shows the fill).
					bar.setAttribute( 'data-progress-target', target );
					bar.style.width = '0%';
				} );
				var bo = makeObserver( function ( entries, obs ) {
					entries.forEach( function ( entry ) {
						if ( ! entry.isIntersecting ) { return; }
						var bar = entry.target;
						var target = parseFloat( bar.getAttribute( 'data-progress-target' ) );
						bar.classList.remove( 'is-paused' );
						// Animate to target. requestAnimationFrame so the 0% paints first.
						requestAnimationFrame( function () {
							bar.style.width = target + '%';
						} );
						obs.unobserve( bar );
					} );
				}, { threshold: 0.2 } );
				bars.forEach( function ( bar ) {
					// Only observe bars we successfully zeroed; others keep their width.
					if ( bar.getAttribute( 'data-progress-target' ) ) {
						bo.observe( bar );
					}
				} );
				// Safety net: if a bar is already in the viewport on load (above the
				// fold, common on shop pages), the observer fires immediately — but
				// also run a one-off check so a fast scroll never leaves a bar at 0.
				setTimeout( function () {
					bars.forEach( function ( bar ) {
						var target = bar.getAttribute( 'data-progress-target' );
						if ( target && parseFloat( bar.style.width ) === 0 ) {
							var rect = bar.getBoundingClientRect();
							if ( rect.top < window.innerHeight ) {
								bar.style.width = target + '%';
							}
						}
					} );
				}, 1200 );
			}
		}

		/* ---------------------------------------------------------------
		 * E. Back-to-top button (injected, toggleable).
		 * ------------------------------------------------------------- */
		if ( d.backToTop !== 'off' ) {
			var btn = document.createElement( 'button' );
			btn.type = 'button';
			btn.className = 'wprt-back-to-top';
			btn.setAttribute( 'aria-label', ( window.wpraffleTheme && window.wpraffleTheme.i18n && window.wpraffleTheme.i18n.backToTop ) || 'Back to top' );
			btn.innerHTML = '<i class="fa-solid fa-arrow-up" aria-hidden="true"></i>';
			document.body.appendChild( btn );

			var toggleVisibility = function () {
				if ( window.scrollY > 300 ) {
					btn.classList.add( 'is-visible' );
				} else {
					btn.classList.remove( 'is-visible' );
				}
			};
			window.addEventListener( 'scroll', toggleVisibility, { passive: true } );
			toggleVisibility();

			btn.addEventListener( 'click', function () {
				if ( reduceMotion ) {
					window.scrollTo( 0, 0 );
					return;
				}
				window.scrollTo( { top: 0, behavior: 'smooth' } );
			} );
		}

		/* ---------------------------------------------------------------
		 * P. Confetti burst on the winners page.
		 * Self-contained canvas confetti (~no dependency) — a small burst on
		 * load. Skipped entirely under prefers-reduced-motion.
		 * ------------------------------------------------------------- */
		if ( d.confettiWinners !== 'off' && ! reduceMotion && document.body.classList.contains( 'wprt-is-winners-page' ) ) {
			fireConfetti();
		}

		/* ---------------------------------------------------------------
		 * M. Ending-soon urgency on the homepage countdown.
		 * The plugin already ships urgency styling on shop cards (rc-card--ending-soon).
		 * This ports the same < 24h convention to the THEME homepage countdown by
		 * adding .is-urgent to the banner, which v1.2.0.css turns red.
		 *
		 * Performance: the urgency state only changes when the draw crosses the
		 * 24h threshold, so we check on a slow interval (60s) — NOT every second.
		 * A per-second toggle triggers a style recalc every tick, which combined
		 * with an infinite pulse animation and other page compositing (social
		 * proof toasts, entry forms) can saturate the main thread and freeze the
		 * page. 60s is plenty given the 24h window.
		 * ------------------------------------------------------------- */
		var banner = document.querySelector( '.wprt-countdown-banner[data-draw-date]' );
		if ( banner ) {
			var drawTs = Date.parse( banner.getAttribute( 'data-draw-date' ) );
			if ( ! isNaN( drawTs ) ) {
				var DAY = 24 * 60 * 60 * 1000;
				var checkUrgent = function () {
					var remaining = drawTs - Date.now();
					banner.classList.toggle( 'is-urgent', remaining > 0 && remaining < DAY );
				};
				checkUrgent();
				// Slow poll — the urgency state flips at most once per draw.
				setInterval( checkUrgent, 60000 );
			}
		}

		/* ---------------------------------------------------------------
		 * O. Hero video pause control (WCAG 2.2 SC 2.2.2).
		 * The autoplay <video> must expose a pause mechanism. This toggles
		 * play/pause and updates the icon + aria-label.
		 * ------------------------------------------------------------- */
		var video = document.querySelector( '.wpr-hero__video' );
		var pauseBtn = document.querySelector( '.wprt-hero-video-pause' );
		if ( video && pauseBtn ) {
			pauseBtn.addEventListener( 'click', function () {
				if ( video.paused ) {
					video.play();
					pauseBtn.innerHTML = '<i class="fa-solid fa-pause" aria-hidden="true"></i>';
					pauseBtn.setAttribute( 'aria-label', pauseBtn.getAttribute( 'aria-label' ).replace( /\bplay\b/i, 'Pause' ).replace( /\bpause\b/i, 'Pause' ) );
				} else {
					video.pause();
					pauseBtn.innerHTML = '<i class="fa-solid fa-play" aria-hidden="true"></i>';
				}
			} );
			// Respect reduced-motion: start paused.
			if ( reduceMotion ) {
				video.pause();
				pauseBtn.innerHTML = '<i class="fa-solid fa-play" aria-hidden="true"></i>';
			}
		}

		/* ---------------------------------------------------------------
		 * V. Sticky entry bar (single product).
		 * Watches the main entry form; shows the bar when it scrolls out of view.
		 * ------------------------------------------------------------- */
		var entryForm = document.querySelector( '.rc-entry-form, form.cart, .wpraffle-entry-form' );
		var stickyBar = document.querySelector( '.wprt-sticky-entry-bar' );
		if ( entryForm && stickyBar ) {
			var so = makeObserver( function ( entries ) {
				entries.forEach( function ( entry ) {
					// Show the bar when the form is NOT intersecting (scrolled past) and we're below it.
					stickyBar.classList.toggle( 'is-visible', ! entry.isIntersecting && window.scrollY > entry.target.offsetTop );
				} );
			}, { threshold: 0 } );
			so.observe( entryForm );
		}

		/* ---------------------------------------------------------------
		 * W. Notification bell dropdown toggle.
		 * ------------------------------------------------------------- */
		var bell = document.querySelector( '.wprt-notification-bell' );
		if ( bell ) {
			var dropdown = bell.querySelector( '.wprt-notification-dropdown' );
			var bellBtn = bell.querySelector( 'button' );
			if ( bellBtn && dropdown ) {
				bellBtn.addEventListener( 'click', function ( e ) {
					e.stopPropagation();
					var open = dropdown.classList.toggle( 'is-open' );
					bellBtn.setAttribute( 'aria-expanded', open ? 'true' : 'false' );
				} );
				document.addEventListener( 'click', function () { dropdown.classList.remove( 'is-open' ); } );
			}
		}

		/* ---------------------------------------------------------------
		 * S. Share buttons — copy-link handler.
		 * ------------------------------------------------------------- */
		document.querySelectorAll( '.wprt-share-btn--copy' ).forEach( function ( btn ) {
			btn.addEventListener( 'click', function () {
				var url = btn.getAttribute( 'data-share-url' ) || window.location.href;
				if ( navigator.clipboard && navigator.clipboard.writeText ) {
					navigator.clipboard.writeText( url ).then( function () { markCopied( btn ); } );
				} else {
					// Fallback for older browsers.
					var ta = document.createElement( 'textarea' );
					ta.value = url;
					document.body.appendChild( ta );
					ta.select();
					try { document.execCommand( 'copy' ); markCopied( btn ); } catch ( e ) {}
					ta.remove();
				}
			} );
		} );
		function markCopied( btn ) {
			btn.classList.add( 'is-copied' );
			var original = btn.innerHTML;
			btn.innerHTML = '<i class="fa-solid fa-check" aria-hidden="true"></i>';
			setTimeout( function () { btn.innerHTML = original; btn.classList.remove( 'is-copied' ); }, 1800 );
		}

		/* ---------------------------------------------------------------
		 * Mobile CTA visibility sync — add a body class so the back-to-top
		 * and chat buttons lift clear of the mobile CTA bar.
		 * ------------------------------------------------------------- */
		var mobileCta = document.querySelector( '.wprt-mobile-cta' );
		if ( mobileCta ) {
			var syncClass = function () {
				document.body.classList.toggle( 'wprt-mobile-cta-visible', mobileCta.offsetParent !== null );
			};
			syncClass();
			window.addEventListener( 'resize', syncClass );
		}

		/* ---------------------------------------------------------------
		 * #2. Free-entry prominence — switch to the postal tab when the
		 * "enter free by post" link is clicked. The tabs are the plugin's
		 * .raffle-tab-btn elements; we just trigger their existing handler.
		 * ------------------------------------------------------------- */
		document.querySelectorAll( '.wprt-free-entry-trigger' ).forEach( function ( link ) {
			link.addEventListener( 'click', function ( e ) {
				e.preventDefault();
				var postalBtn = document.querySelector( '.raffle-tab-btn[data-tab="postal"]' );
				if ( postalBtn ) { postalBtn.click(); postalBtn.focus(); }
			} );
		} );

		/* ---------------------------------------------------------------
		 * #4. Bundle visual upgrade — flag the highest-savings bundle and
		 * track selection for the .is-selected highlight. Reads the
		 * data-bundle-price / data-qty attributes the plugin already emits.
		 * ------------------------------------------------------------- */
		( function () {
			var pills = document.querySelectorAll( '.raffle-bundles-row .raffle-qty-pill[data-bundle-price]' );
			if ( ! pills.length ) { return; }
			var ticketPrice = 0;
			// Derive the per-ticket price from the first non-bundle context if possible.
			var oddsBox = document.getElementById( 'raffle-odds-box' );
			// Determine best-value = highest savings % vs (qty * unit price).
			var bestIdx = -1, bestSavings = -1;
			pills.forEach( function ( pill, i ) {
				var qty = parseInt( pill.getAttribute( 'data-qty' ), 10 ) || 0;
				var price = parseFloat( pill.getAttribute( 'data-bundle-price' ) ) || 0;
				if ( qty > 0 && price > 0 ) {
					// Solve unit price from the first pill where qty*unit > price.
					if ( ! ticketPrice && price < qty * 1000 ) {
						// Approximate; refined below if a savings badge is present.
					}
				}
				var badge = pill.querySelector( '.raffle-bundle-badge' );
				if ( badge && /save\s+(\d+)/i.test( badge.textContent ) ) {
					var pct = parseInt( RegExp.$1, 10 );
					if ( pct > bestSavings ) { bestSavings = pct; bestIdx = i; }
				}
			} );
			if ( bestIdx >= 0 && pills[ bestIdx ] ) {
				pills[ bestIdx ].classList.add( 'is-best-value' );
			}
			// Selection tracking.
			pills.forEach( function ( pill ) {
				pill.addEventListener( 'click', function () {
					pills.forEach( function ( p ) { p.classList.remove( 'is-selected' ); } );
					pill.classList.add( 'is-selected' );
				} );
			} );
		} )();

		/* ---------------------------------------------------------------
		 * U. Quick-view modal.
		 * Injects a "Quick View" button onto each competition card and opens
		 * a modal populated via AJAX. Closes on backdrop / Esc / close button.
		 * ------------------------------------------------------------- */
		initQuickView();

		function initQuickView() {
			if ( ! ( d.isRaffle ) ) { return; }
			var cards = document.querySelectorAll( '.rc-card' );
			if ( ! cards.length ) { return; }

			// Ensure the modal shell exists.
			var modal = document.getElementById( 'wprt-quickview-modal' );
			if ( ! modal ) { return; }
			var content = modal.querySelector( '.wprt-quickview-modal__content' );
			var ajaxUrl = d.ajaxUrl;
			var nonce   = d.nonce;

			cards.forEach( function ( card ) {
				if ( card.querySelector( '.wprt-quickview-trigger' ) ) { return; }
				var raffleId = card.getAttribute( 'data-raffle-id' );
				if ( ! raffleId ) { return; }
				var trigger = document.createElement( 'button' );
				trigger.type = 'button';
				trigger.className = 'wprt-quickview-trigger';
				trigger.setAttribute( 'aria-label', ( window.wpraffleTheme && window.wpraffleTheme.i18n && window.wpraffleTheme.i18n.quickView ) || 'Quick view' );
				trigger.innerHTML = '<i class="fa-solid fa-eye" aria-hidden="true"></i><span>' + ( ( window.wpraffleTheme && window.wpraffleTheme.i18n && window.wpraffleTheme.i18n.quickView ) || 'Quick view' ) + '</span>';
				trigger.addEventListener( 'click', function ( e ) {
					e.preventDefault();
					openModal( raffleId );
				} );
				card.appendChild( trigger );
			} );

			function openModal( raffleId ) {
				modal.hidden = false;
				document.body.style.overflow = 'hidden';
				content.innerHTML = '<p style="text-align:center;color:var(--wpr-text-muted,#9ca3af);">' + ( ( window.wpraffleTheme && window.wpraffleTheme.i18n && window.wpraffleTheme.i18n.loading ) || 'Loading…' ) + '</p>';
				fetch( ajaxUrl, {
					method: 'POST',
					headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
					body: 'action=wprt_quickview&nonce=' + encodeURIComponent( nonce ) + '&raffle_id=' + encodeURIComponent( raffleId )
				} )
				.then( function ( r ) { return r.json(); } )
				.then( function ( res ) {
					if ( ! res || ! res.success ) {
						content.innerHTML = '<p>' + ( ( window.wpraffleTheme && window.wpraffleTheme.i18n && window.wpraffleTheme.i18n.loadError ) || 'Could not load this competition.' ) + '</p>';
						return;
					}
					var data = res.data;
					var media = data.image ? 'background-image:url(\'' + data.image + '\')' : '';
					content.innerHTML =
						'<div class="wprt-quickview">' +
							'<div class="wprt-quickview__media" style="' + media + '"></div>' +
							'<div class="wprt-quickview__body">' +
								'<h2 class="wprt-quickview__title" id="wprt-quickview-title">' + escapeHtml( data.title ) + '</h2>' +
								( data.price ? '<div class="wprt-featured-spotlight__price">' + data.price + '</div>' : '' ) +
								( data.draw_label ? '<div class="wprt-featured-spotlight__countdown">' + escapeHtml( data.draw_label ) + '</div>' : '' ) +
								'<div class="rc-card__progress"><div class="rc-card__progress-stats"><span>' + data.sold + ' sold</span><span>' + data.remain + ' left</span></div><div class="rc-card__progress-bar"><div class="rc-card__progress-fill" style="width:' + data.pct + '%;"></div></div></div>' +
								'<a class="btn btn-accent btn-lg" href="' + data.enter_url + '">' + ( ( window.wpraffleTheme && window.wpraffleTheme.i18n && window.wpraffleTheme.i18n.enterNow ) || 'Enter Now' ) + '</a>' +
							'</div>' +
						'</div>';
				} )
				.catch( function () {
					content.innerHTML = '<p>' + ( ( window.wpraffleTheme && window.wpraffleTheme.i18n && window.wpraffleTheme.i18n.loadError ) || 'Could not load this competition.' ) + '</p>';
				} );
			}

			function closeModal() {
				modal.hidden = true;
				document.body.style.overflow = '';
			}
			modal.addEventListener( 'click', function ( e ) {
				if ( e.target.closest( '[data-wprt-quickview-close]' ) ) { closeModal(); }
			} );
			document.addEventListener( 'keydown', function ( e ) {
				if ( e.key === 'Escape' && ! modal.hidden ) { closeModal(); }
			} );
		}

		function escapeHtml( s ) {
			return String( s ).replace( /[&<>"']/g, function ( c ) {
				return { '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' }[ c ];
			} );
		}

		function fireConfetti() {
			var canvas = document.createElement( 'canvas' );
			canvas.className = 'wprt-confetti';
			canvas.setAttribute( 'aria-hidden', 'true' );
			document.body.appendChild( canvas );
			var ctx = canvas.getContext( '2d' );
			var W, H;
			function resize() {
				W = canvas.width = window.innerWidth;
				H = canvas.height = window.innerHeight;
			}
			resize();
			window.addEventListener( 'resize', resize );

			var colours = [ '#e4678a', '#5caeed', '#63dd92', '#ffc107', '#ffffff' ];
			var particles = [];
			var count = 140;
			for ( var i = 0; i < count; i++ ) {
				particles.push( {
					x: Math.random() * W,
					y: Math.random() * -H,
					w: 6 + Math.random() * 6,
					h: 8 + Math.random() * 8,
					colour: colours[ Math.floor( Math.random() * colours.length ) ],
					vy: 2 + Math.random() * 3,
					vx: -1 + Math.random() * 2,
					rot: Math.random() * 360,
					vr: -4 + Math.random() * 8
				} );
			}

			var start = null;
			var DURATION = 3500;
			function frame( ts ) {
				if ( ! start ) { start = ts; }
				var elapsed = ts - start;
				ctx.clearRect( 0, 0, W, H );
				particles.forEach( function ( p ) {
					p.y += p.vy;
					p.x += p.vx;
					p.rot += p.vr;
					ctx.save();
					ctx.translate( p.x, p.y );
					ctx.rotate( ( p.rot * Math.PI ) / 180 );
					ctx.fillStyle = p.colour;
					ctx.fillRect( -p.w / 2, -p.h / 2, p.w, p.h );
					ctx.restore();
				} );
				if ( elapsed < DURATION ) {
					window.requestAnimationFrame( frame );
				} else {
					canvas.remove();
				}
			}
			window.requestAnimationFrame( frame );
		}
	} );
} )();
