<?php
/**
 * Placeholder winner card (shown when the plugin is absent or has no winners).
 *
 * @package WPRaffle_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<div class="swiper-slide">
	<div class="wpr-winner-card">
		<div class="wpr-winner-card__media">
			<img src="<?php echo esc_url( WPRAFFLE_THEME_URI . '/assets/images/placeholder-winner.svg' ); ?>" alt="" loading="lazy">
			<?php wpraffle_theme_winner_badge( 'main' ); ?>
		</div>
		<div class="wpr-winner-card__body">
			<h3 class="wpr-winner-card__name"><?php esc_html_e( 'Your Winner Here', 'wpraffle-theme' ); ?></h3>
			<p class="wpr-winner-card__prize"><?php esc_html_e( 'Activate WPRaffles to showcase recent winners.', 'wpraffle-theme' ); ?></p>
		</div>
	</div>
</div>
