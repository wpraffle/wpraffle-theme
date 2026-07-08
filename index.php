<?php
/**
 * The main template file — blog/posts fallback.
 *
 * @package Diamond
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();
?>
<main id="main" class="site-main section">
	<div class="container">
		<div class="row">

			<div class="col-lg-8">
				<?php if ( have_posts() ) : ?>
					<div class="diamond-posts">
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
