<?php
/**
 * Promo / announcement bar — dismissible, schedulable.
 *
 * @package WPRaffle_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$s = WPRaffle_Theme_Settings::instance()->get_settings();

// Schedule check.
$now   = time();
$start = $s['promo_start'] ? strtotime( $s['promo_start'] ) : 0;
$end   = $s['promo_end'] ? strtotime( $s['promo_end'] ) : PHP_INT_MAX;
if ( $now < $start || $now > $end ) {
	return;
}

// Dismissal cookie.
if ( isset( $_COOKIE['wprt_promo_dismissed'] ) && '1' === $_COOKIE['wprt_promo_dismissed'] ) {
	return;
}
?>
<div class="wprt-promo-bar" data-bg="<?php echo esc_attr( $s['promo_bg'] ); ?>" style="background:<?php echo esc_attr( $s['promo_bg'] ); ?>;">
	<div class="wprt-promo-bar__inner">
		<?php if ( $s['promo_url'] ) : ?>
			<a href="<?php echo esc_url( $s['promo_url'] ); ?>" class="wprt-promo-bar__link"><?php echo wp_kses_post( $s['promo_text'] ); ?></a>
		<?php else : ?>
			<span class="wprt-promo-bar__text"><?php echo wp_kses_post( $s['promo_text'] ); ?></span>
		<?php endif; ?>
		<button type="button" class="wprt-promo-bar__close" aria-label="<?php esc_attr_e( 'Dismiss', 'wpraffle-theme' ); ?>"><i class="fa-solid fa-xmark"></i></button>
	</div>
</div>
