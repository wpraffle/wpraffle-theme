<?php
/**
 * Template Name: WPRaffle — Contact
 * Template Post Type: page
 * @package WPRaffle_Theme
 */
get_header();
?>
<main id="primary" class="site-main wprt-page-template wprt-page-contact">
	<?php while ( have_posts() ) : the_post(); ?>
	<section class="wprt-page-hero"><div class="container"><span class="wprt-page-kicker"><?php esc_html_e( 'We are here to help', 'wpraffle-theme' ); ?></span><h1><?php the_title(); ?></h1><?php if ( has_excerpt() ) : ?><p><?php echo esc_html( get_the_excerpt() ); ?></p><?php endif; ?></div></section>
	<section class="wprt-page-content"><div class="container wprt-reading-width"><?php if ( trim( get_the_content() ) ) { the_content(); } else { ?><div class="wprt-empty-state"><span class="dashicons dashicons-email-alt"></span><h2><?php esc_html_e( 'Add your contact form or support details.', 'wpraffle-theme' ); ?></h2><p><?php esc_html_e( 'Edit this page and insert a form block, shortcode or your preferred contact details.', 'wpraffle-theme' ); ?></p></div><?php } ?></div></section>
	<?php endwhile; ?>
</main>
<?php get_footer(); ?>
