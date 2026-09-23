<?php
/**
 * Template Name: WPRaffle — Winners
 * Template Post Type: page
 * @package WPRaffle_Theme
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }
get_header();
?>
<main id="primary" class="site-main wprt-page-template wprt-page-winners">
	<?php while ( have_posts() ) : the_post(); ?>
	<section class="wprt-page-hero"><div class="container"><span class="wprt-page-kicker"><?php esc_html_e( 'Real winners', 'wpraffle-theme' ); ?></span><h1><?php the_title(); ?></h1><p><?php echo esc_html( has_excerpt() ? get_the_excerpt() : __( 'Real prizes, real people, paid out instantly.', 'wpraffle-theme' ) ); ?></p></div></section>
	<section class="wprt-page-content"><div class="container">
		<?php if ( trim( get_the_content() ) ) : ?><div class="wprt-reading-width wprt-page-intro"><?php the_content(); ?></div><?php endif; ?>
		<?php if ( wpraffle_theme_has_plugin() && shortcode_exists( 'raffle_ended_list' ) ) : ?>
			<div class="wprt-results-grid"><?php echo do_shortcode( '[raffle_ended_list]' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></div>
		<?php else : ?>
			<div class="wprt-empty-state"><span class="dashicons dashicons-awards"></span><h2><?php esc_html_e( 'Winners will appear here.', 'wpraffle-theme' ); ?></h2><p><?php esc_html_e( 'Activate WPRaffle and complete a competition to populate the winners wall.', 'wpraffle-theme' ); ?></p></div>
		<?php endif; ?>
	</div></section>
	<?php endwhile; ?>
</main>
<?php get_footer(); ?>
