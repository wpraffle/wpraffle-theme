<?php
/**
 * Template Name: WPRaffle — Charities
 * Template Post Type: page
 * @package WPRaffle_Theme
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }
get_header();
?>
<main id="primary" class="site-main wprt-page-template wprt-page-charities">
	<?php while ( have_posts() ) : the_post(); ?>
	<section class="wprt-page-hero"><div class="container"><span class="wprt-page-kicker"><?php esc_html_e( 'Giving back', 'wpraffle-theme' ); ?></span><h1><?php the_title(); ?></h1><p><?php echo esc_html( has_excerpt() ? get_the_excerpt() : __( 'Every ticket purchased helps support great causes.', 'wpraffle-theme' ) ); ?></p></div></section>
	<section class="wprt-page-content"><div class="container">
		<?php if ( trim( get_the_content() ) ) : ?><div class="wprt-reading-width wprt-page-intro"><?php the_content(); ?></div><?php endif; ?>
		<?php if ( wpraffle_theme_has_plugin() ) : ?>
			<div class="wpr-charity mb-5"><span class="wprt-page-kicker"><?php esc_html_e( 'Total raised', 'wpraffle-theme' ); ?></span><div class="wpr-charity__total"><?php echo esc_html( WPRaffle_Theme_Integration::get_total_raised() ); ?></div><div class="wpr-charity__label"><?php esc_html_e( 'Raised for charity', 'wpraffle-theme' ); ?></div></div>
			<?php echo do_shortcode( '[raffle_charities columns="3"]' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
		<?php else : ?>
			<div class="wprt-empty-state"><span class="dashicons dashicons-heart"></span><h2><?php esc_html_e( 'Charities will appear here.', 'wpraffle-theme' ); ?></h2><p><?php esc_html_e( 'Activate WPRaffle to display supported charities.', 'wpraffle-theme' ); ?></p></div>
		<?php endif; ?>
	</div></section>
	<?php endwhile; ?>
</main>
<?php get_footer(); ?>
