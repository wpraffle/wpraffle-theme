<?php
/**
 * Sticky "Add to basket" bar (v1.2.0 Enhancement V).
 *
 * Appears at the bottom of single-competition pages when the main entry form
 * scrolls out of view. Rendered into the footer by
 * WPRaffle_Theme_Features::render_footer_extras() on `is_singular( 'product' )`.
 * Visibility toggling is handled by v1.2.0.js (IntersectionObserver on the
 * entry form).
 *
 * @package WPRaffle_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'WooCommerce' ) ) {
	return;
}

global $product;
if ( ! $product ) {
	$product = wc_get_product( get_the_ID() );
}
if ( ! $product ) {
	return;
}

$title = get_the_title();
$price = $product->get_price_html();
$url   = $product->get_permalink();
?>
<div class="wprt-sticky-entry-bar" role="region" aria-label="<?php esc_attr_e( 'Quick entry', 'wpraffle-theme' ); ?>" hidden>
	<div class="wprt-sticky-entry-bar__inner">
		<span class="wprt-sticky-entry-bar__title"><?php echo esc_html( $title ); ?></span>
		<span class="wprt-sticky-entry-bar__price"><?php echo wp_kses_post( $price ); ?></span>
		<a class="btn btn-accent" href="<?php echo esc_url( $url ); ?>#main"><?php esc_html_e( 'Enter Now', 'wpraffle-theme' ); ?></a>
	</div>
</div>
<script>
	// Unhide once the DOM is ready (keeps it out of the initial paint to avoid layout shift).
	document.addEventListener( 'DOMContentLoaded', function () {
		var bar = document.querySelector( '.wprt-sticky-entry-bar' );
		if ( bar ) { bar.hidden = false; }
	} );
</script>
