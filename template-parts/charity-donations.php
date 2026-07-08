<?php
/**
 * Charity Donations section — shows the total raised (via Raffle_Charity)
 * plus active charities grid via the [raffle_charities] shortcode.
 *
 * @package Diamond
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<section id="charity" class="section section--tint">
	<div class="container">
		<?php
		wpraffle_theme_section_heading(
			get_theme_mod( 'diamond_charity_title', __( 'Supporting Great Causes', 'wpraffle-theme' ) ),
			get_theme_mod( 'diamond_charity_subtitle', __( '100% of ticket sales donated to charity.', 'wpraffle-theme' ) ),
			wpraffle_theme_charities_url(),
			__( 'View all charities', 'wpraffle-theme' )
		);
		?>
		<div class="diamond-charity">
			<span class="eyebrow" style="color:#fff;opacity:.85;"><?php esc_html_e( 'Giving Back', 'wpraffle-theme' ); ?></span>
			<div class="diamond-charity__total"><?php echo esc_html( wpraffle_theme_has_plugin() ? WPRaffle_Theme_Integration::get_total_raised() : '£0' ); ?></div>
			<div class="diamond-charity__label"><?php esc_html_e( 'Raised for Charity', 'wpraffle-theme' ); ?></div>
		</div>

		<?php if ( wpraffle_theme_has_plugin() ) : ?>
			<div class="mt-5">
				<?php echo do_shortcode( '[raffle_charities columns="3"]' ); ?>
			</div>
		<?php else : ?>
			<p class="text-center mt-4"><?php esc_html_e( 'Activate the WPRaffles plugin to show the charities you support.', 'wpraffle-theme' ); ?></p>
		<?php endif; ?>
	</div>
</section>
