<?php
/**
 * Template Name: Full Width
 * A full-width page template (no sidebar, edge-to-edge content).
 *
 * @package WPRaffle_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();
?>
<main id="main" class="site-main">
	<?php
	while ( have_posts() ) :
		the_post();
		?>
		<article id="post-<?php the_ID(); ?>" <?php post_class( 'wpr-page wpr-page--full' ); ?>>
			<?php if ( has_post_thumbnail() ) : ?>
				<div class="wpr-page-featured"><?php the_post_thumbnail( 'wpr-hero' ); ?></div>
			<?php endif; ?>
			<div class="section">
				<div class="container-fluid px-4">
					<header class="wpr-page-header mb-4">
						<h1 class="wpr-page-title"><?php the_title(); ?></h1>
						<div class="wpr-rule"></div>
					</header>
					<div class="wpr-page-content">
						<?php
						the_content();
						wp_link_pages();
						?>
					</div>
				</div>
			</div>
		</article>
		<?php
	endwhile;
	?>
</main>
<?php
get_footer();
