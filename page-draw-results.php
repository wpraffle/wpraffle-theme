<?php
/**
 * Template Name: WPRaffle — Draw Results
 * Template Post Type: page
 * @package WPRaffle_Theme
 */
get_header();
?>
<main id="primary" class="site-main wprt-page-template wprt-page-draw-results wprt-page-results">
	<?php while ( have_posts() ) : the_post(); ?>
	<section class="wprt-page-hero"><div class="container"><span class="wprt-page-kicker"><?php esc_html_e( 'Verified winners and completed competitions', 'wpraffle-theme' ); ?></span><h1><?php the_title(); ?></h1><?php if ( has_excerpt() ) : ?><p><?php echo esc_html( get_the_excerpt() ); ?></p><?php endif; ?></div></section>
	<section class="wprt-page-content"><div class="container">
		<?php the_content(); ?>
		<?php if ( wpraffle_theme_has_plugin() && shortcode_exists( 'raffle_ended_list' ) ) : ?>
			<div class="wprt-results-grid"><?php echo do_shortcode( '[raffle_ended_list]' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></div>
		<?php elseif ( ! trim( get_the_content() ) ) : ?>
			<div class="wprt-empty-state"><span class="dashicons dashicons-awards"></span><h2><?php esc_html_e( 'Draw results will appear here.', 'wpraffle-theme' ); ?></h2><p><?php esc_html_e( 'Publish completed competitions or add your preferred results content to this page.', 'wpraffle-theme' ); ?></p></div>
		<?php endif; ?>
	</div></section>
	<?php endwhile; ?>
</main>
<?php get_footer(); ?>
