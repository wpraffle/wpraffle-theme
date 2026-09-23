<?php
/**
 * Template Name: WPRaffle — Full Width
 * Template Post Type: page
 * @package WPRaffle_Theme
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }
get_header();
?>
<main id="primary" class="site-main wprt-page-template wprt-page-full-width">
	<?php while ( have_posts() ) : the_post(); ?>
	<section class="wprt-page-hero"><div class="container"><span class="wprt-page-kicker"><?php esc_html_e( 'Discover more', 'wpraffle-theme' ); ?></span><h1><?php the_title(); ?></h1><?php if ( has_excerpt() ) : ?><p><?php echo esc_html( get_the_excerpt() ); ?></p><?php endif; ?></div></section>
	<?php if ( has_post_thumbnail() ) : ?><div class="wpr-page-featured"><?php the_post_thumbnail( 'wpr-hero' ); ?></div><?php endif; ?>
	<section class="wprt-page-content"><div class="container-fluid px-4"><?php the_content(); wp_link_pages(); ?></div></section>
	<?php endwhile; ?>
</main>
<?php get_footer(); ?>
