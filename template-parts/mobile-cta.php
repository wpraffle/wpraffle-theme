<?php
/**
 * Sticky mobile CTA bar — shows the next-closing competition + Enter button.
 *
 * @package WPRaffle_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$s = WPRaffle_Theme_Settings::instance()->get_settings();
?>
<div class="wprt-mobile-cta">
	<div class="wprt-mobile-cta__inner">
		<span class="wprt-mobile-cta__text"><?php echo esc_html( $s['mobile_cta_text'] ?: __( 'Enter Now', 'wpraffle-theme' ) ); ?></span>
		<a href="<?php echo esc_url( $s['mobile_cta_url'] ?: wpraffle_theme_competitions_url() ); ?>" class="wprt-mobile-cta__btn">
			<i class="fa-solid fa-ticket"></i>
			<?php esc_html_e( 'Enter', 'wpraffle-theme' ); ?>
		</a>
	</div>
</div>
