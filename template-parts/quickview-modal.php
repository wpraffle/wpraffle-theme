<?php
/**
 * Quick-view modal (v1.2.0 Enhancement U).
 *
 * Renders the modal shell + opens it via AJAX when a "Quick View" trigger is
 * clicked on a competition card. The trigger button is injected onto cards by
 * v1.2.0.js (the plugin owns the card markup, so we add the overlay in JS).
 * Uses Fancybox when available, otherwise a native dialog.
 *
 * The modal deliberately shows summary info + a CTA to the full entry page
 * rather than duplicating the entry form (the form needs the single-raffle
 * page's scripts/nonces).
 *
 * Included from the footer on pages that show competition grids.
 *
 * @package WPRaffle_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! wpraffle_theme_has_plugin() ) {
	return;
}
?>
<div class="wprt-quickview-modal" id="wprt-quickview-modal" role="dialog" aria-modal="true" aria-labelledby="wprt-quickview-title" hidden>
	<div class="wprt-quickview-modal__backdrop" data-wprt-quickview-close></div>
	<div class="wprt-quickview-modal__panel">
		<button type="button" class="wprt-quickview-modal__close" data-wprt-quickview-close aria-label="<?php esc_attr_e( 'Close quick view', 'wpraffle-theme' ); ?>">
			<i class="fa-solid fa-xmark" aria-hidden="true"></i>
		</button>
		<div class="wprt-quickview-modal__content">
			<p style="text-align:center;color:var(--wpr-text-muted,#9ca3af);"><?php esc_html_e( 'Loading…', 'wpraffle-theme' ); ?></p>
		</div>
	</div>
</div>
