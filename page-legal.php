<?php
/**
 * Template Name: WPRaffle — Legal & Policy
 * Template Post Type: page
 * @package WPRaffle_Theme
 */
get_header();
?>
<main id="primary" class="site-main wprt-page-template wprt-page-legal">
	<?php while ( have_posts() ) : the_post(); ?>
	<section class="wprt-page-hero wprt-page-hero--compact"><div class="container"><span class="wprt-page-kicker"><?php esc_html_e( 'Policy', 'wpraffle-theme' ); ?></span><h1><?php the_title(); ?></h1><p><?php echo esc_html( get_the_modified_date() ? sprintf( __( 'Last updated %s', 'wpraffle-theme' ), get_the_modified_date() ) : '' ); ?></p></div></section>
	<section class="wprt-page-content"><div class="container wprt-reading-width wprt-legal-content"><?php the_content(); ?></div></section>
	<?php endwhile; ?>
</main>
<?php get_footer(); ?>
