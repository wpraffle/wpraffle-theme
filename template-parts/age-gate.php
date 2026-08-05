<?php
/**
 * Age verification gate modal.
 *
 * @package WPRaffle_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$s = WPRaffle_Theme_Settings::instance()->get_settings();

// Don't show if already confirmed (cookie check).
if ( isset( $_COOKIE['wprt_age_verified'] ) && '1' === $_COOKIE['wprt_age_verified'] ) {
	return;
}
?>
<div class="wprt-age-gate" id="wprt-age-gate">
	<div class="wprt-age-gate__overlay"></div>
	<div class="wprt-age-gate__modal">
		<div class="wprt-age-gate__icon"><i class="fa-solid fa-shield-halved"></i></div>
		<h2 class="wprt-age-gate__title"><?php echo esc_html( $s['age_title'] ); ?></h2>
		<p class="wprt-age-gate__text"><?php echo esc_html( $s['age_text'] ); ?></p>
		<div class="wprt-age-gate__actions">
			<button type="button" class="btn btn-accent wprt-age-gate__yes" data-duration="<?php echo esc_attr( $s['age_duration'] ); ?>"><?php echo esc_html( $s['age_btn_yes'] ); ?></button>
			<a href="<?php echo esc_url( $s['age_no_url'] ?: 'https://www.google.com' ); ?>" class="btn btn-outline-secondary wprt-age-gate__no"><?php echo esc_html( $s['age_btn_no'] ); ?></a>
		</div>
	</div>
</div>
