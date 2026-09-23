<?php
/**
 * Template Name: WPRaffle — Competitions
 * Template Post Type: page
 * @package WPRaffle_Theme
 */
get_header();
?>
<main id="primary" class="site-main wprt-page-template wprt-page-competitions">
	<?php while ( have_posts() ) : the_post(); ?>
	<section class="wprt-page-hero wprt-page-hero--compact"><div class="container"><span class="wprt-page-kicker"><?php esc_html_e( 'Choose your next competition', 'wpraffle-theme' ); ?></span><h1><?php the_title(); ?></h1><?php if ( has_excerpt() ) : ?><p><?php echo esc_html( get_the_excerpt() ); ?></p><?php endif; ?></div></section>
	<?php if ( trim( get_the_content() ) ) : ?><section class="wprt-page-content"><div class="container wprt-reading-width"><?php the_content(); ?></div></section><?php endif; ?>
	<?php get_template_part( 'template-parts/active-competitions' ); ?>
	<?php endwhile; ?>
</main>
<?php get_footer(); ?>
