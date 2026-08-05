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
<section id="live-draw" class="section section--dark" style="padding:3rem 0;">
	<div class="container text-center">
		<span class="eyebrow" style="color:#fff;opacity:.85;"><?php esc_html_e( 'Watch Live', 'wpraffle-theme' ); ?></span>
		<h2 style="color:#fff;margin-bottom:1.5rem;"><?php esc_html_e( 'Live Draw', 'wpraffle-theme' ); ?></h2>
		<?php echo do_shortcode( '[raffle_live_draw]' ); ?>
	</div>
</section>
