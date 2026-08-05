<?php
/**
 * Template Name: Charities
 *
 * Displays the charities the site supports, with the total raised banner and
 * the plugin's [raffle_charities] shortcode grid.
 *
 * Assign this template to a page under Page → Attributes → Template, or rely
 * on the auto-created "Charities" page (theme activation creates it).
 *
 * @package WPRaffle_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();
?>
<main id="main" class="site-main">

	<header class="wpr-shop-hero section--dark" style="padding:3.5rem 0;">
		<div class="container">
			<span class="eyebrow" style="color:#fff;opacity:.85;"><?php esc_html_e( 'Giving Back', 'wpraffle-theme' ); ?></span>
			<h1 class="mb-2" style="color:#fff;font-size:clamp(1.8rem,4vw,2.75rem);margin:0;">
				<?php the_title(); ?>
			</h1>
			<p class="mb-0" style="color:rgba(255,255,255,.85);">
				<?php esc_html_e( 'Every ticket purchased helps support great causes.', 'wpraffle-theme' ); ?>
			</p>
		</div>
	</header>

	<div class="section">
		<div class="container">

			<?php if ( wpraffle_theme_has_plugin() ) : ?>
				<div class="wpr-charity mb-5">
					<span class="eyebrow" style="color:#fff;opacity:.85;"><?php esc_html_e( 'Total Raised', 'wpraffle-theme' ); ?></span>
					<div class="wpr-charity__total"><?php echo esc_html( WPRaffle_Theme_Integration::get_total_raised() ); ?></div>
					<div class="wpr-charity__label"><?php esc_html_e( 'Raised for Charity', 'wpraffle-theme' ); ?></div>
				</div>

				<?php echo do_shortcode( '[raffle_charities columns="3"]' ); ?>
			<?php else : ?>
				<p class="text-center"><?php esc_html_e( 'Activate the WPRaffles plugin to display your charities.', 'wpraffle-theme' ); ?></p>
			<?php endif; ?>

		</div>
	</div>

</main>
<?php
get_footer();
