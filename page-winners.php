<?php
/**
 * Template Name: Winners
 *
 * Displays the winners wall. Renders the plugin's [raffle_ended_list]
 * shortcode (which shows completed raffles + winners across Live Draw /
 * Auto-Draw / Instant Wins tabs) inside a Paragon-style page header.
 *
 * Assign this template to a page under Page → Attributes → Template, or rely
 * on the auto-created "Winners" page (theme activation creates it).
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
			<span class="eyebrow" style="color:#fff;opacity:.85;"><?php esc_html_e( 'Real Winners', 'wpraffle-theme' ); ?></span>
			<h1 class="mb-2" style="color:#fff;font-size:clamp(1.8rem,4vw,2.75rem);margin:0;">
				<?php the_title(); ?>
			</h1>
			<p class="mb-0" style="color:rgba(255,255,255,.85);">
				<?php esc_html_e( 'Real prizes, real people, paid out instantly.', 'wpraffle-theme' ); ?>
			</p>
		</div>
	</header>

	<div class="section">
		<div class="container">
			<?php if ( wpraffle_theme_has_plugin() ) : ?>
				<?php echo do_shortcode( '[raffle_ended_list]' ); ?>
			<?php else : ?>
				<p class="text-center"><?php esc_html_e( 'Activate the WPRaffles plugin to display the winners wall.', 'wpraffle-theme' ); ?></p>
			<?php endif; ?>
		</div>
	</div>

</main>
<?php
get_footer();
