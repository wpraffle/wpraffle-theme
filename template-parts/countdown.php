<?php
/**
 * Next-draw countdown banner — counts down to the soonest-drawing live raffle.
 *
 * @package WPRaffle_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Find the next live raffle with the nearest draw date.
global $wpdb;
$next = $wpdb->get_var( $wpdb->prepare(
	"SELECT draw_date FROM {$wpdb->prefix}raffles WHERE status = 'active' AND draw_date IS NOT NULL AND draw_date > %s ORDER BY draw_date ASC LIMIT 1",
	gmdate( 'Y-m-d H:i:s' )
) );

if ( ! $next ) {
	return;
}

$iso = gmdate( 'Y-m-d\TH:i:s\Z', strtotime( $next ) );
?>
<section id="countdown" class="section section--dark wprt-dark-section--compact">
	<div class="container text-center">
		<span class="eyebrow wprt-dark-eyebrow"><?php esc_html_e( 'Next Draw', 'wpraffle-theme' ); ?></span>
		<h2 class="wprt-dark-title"><?php esc_html_e( 'Closing Soon', 'wpraffle-theme' ); ?></h2>
		<div class="wprt-countdown-banner" data-draw-date="<?php echo esc_attr( $iso ); ?>">
			<div class="wprt-cd-unit"><span class="wprt-cd-num wprt-cd-days">0</span><span class="wprt-cd-label"><?php esc_html_e( 'Days', 'wpraffle-theme' ); ?></span></div>
			<div class="wprt-cd-unit"><span class="wprt-cd-num wprt-cd-hours">0</span><span class="wprt-cd-label"><?php esc_html_e( 'Hrs', 'wpraffle-theme' ); ?></span></div>
			<div class="wprt-cd-unit"><span class="wprt-cd-num wprt-cd-mins">0</span><span class="wprt-cd-label"><?php esc_html_e( 'Mins', 'wpraffle-theme' ); ?></span></div>
			<div class="wprt-cd-unit"><span class="wprt-cd-num wprt-cd-secs">0</span><span class="wprt-cd-label"><?php esc_html_e( 'Secs', 'wpraffle-theme' ); ?></span></div>
		</div>
		<?php if ( class_exists( 'WooCommerce' ) ) : ?>
			<a class="btn btn-accent btn-lg mt-4" href="<?php echo esc_url( wpraffle_theme_competitions_url() ); ?>"><?php esc_html_e( 'Enter Now', 'wpraffle-theme' ); ?></a>
		<?php endif; ?>
	</div>
</section>
