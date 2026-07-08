<?php
/**
 * Template Name: Full Width
 * A full-width page template (no sidebar, edge-to-edge content).
 *
 * @package Diamond
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
		<article id="post-<?php the_ID(); ?>" <?php post_class( 'diamond-page diamond-page--full' ); ?>>
			<?php if ( has_post_thumbnail() ) : ?>
				<div class="diamond-page-featured"><?php the_post_thumbnail( 'diamond-hero' ); ?></div>
			<?php endif; ?>
			<div class="section">
				<div class="container-fluid px-4">
					<header class="diamond-page-header mb-4">
						<h1 class="diamond-page-title"><?php the_title(); ?></h1>
						<div class="diamond-rule"></div>
					</header>
					<div class="diamond-page-content">
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
