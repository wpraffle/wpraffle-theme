<?php
/**
 * The archive template (categories, tags, custom taxonomies, author, date).
 * Note: WooCommerce product archives are handled by woocommerce/archive-product.php.
 *
 * @package WPRaffle_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();
?>
<main id="main" class="site-main section">
	<div class="container">
		<header class="wpr-archive-header mb-5">
			<?php
			the_archive_title( '<h1 class="wpr-archive-title">', '</h1>' );
			the_archive_description( '<div class="wpr-archive-description">', '</div>' );
			?>
			<div class="wpr-rule"></div>
		</header>

		<div class="row">
			<div class="col-lg-8">
				<?php if ( have_posts() ) : ?>
					<div class="wpr-posts">
						<?php while ( have_posts() ) : ?>
							<?php the_post(); ?>
							<?php get_template_part( 'template-parts/content', get_post_type() ); ?>
						<?php endwhile; ?>
					</div>
					<?php
					the_posts_pagination( array(
						'mid_size'  => 1,
						'prev_text' => __( 'Previous', 'wpraffle-theme' ),
						'next_text' => __( 'Next', 'wpraffle-theme' ),
					) );
					?>
				<?php else : ?>
					<?php get_template_part( 'template-parts/content', 'none' ); ?>
				<?php endif; ?>
			</div>
			<aside class="col-lg-4">
				<?php get_sidebar(); ?>
			</aside>
		</div>
	</div>
</main>
<?php
get_footer();
