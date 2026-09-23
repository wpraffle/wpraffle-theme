<?php
/**
 * Live Draw section — embeds the plugin's [raffle_live_draw] shortcode.
 *
 * @package WPRaffle_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<section id="live-draw" class="section section--dark wprt-dark-section--standard">
	<div class="container text-center">
		<span class="eyebrow wprt-dark-eyebrow"><?php esc_html_e( 'Watch Live', 'wpraffle-theme' ); ?></span>
		<h2 class="wprt-dark-title wprt-dark-title--large-gap"><?php esc_html_e( 'Live Draw', 'wpraffle-theme' ); ?></h2>
		<?php echo do_shortcode( '[raffle_live_draw]' ); ?>
	</div>
</section>
