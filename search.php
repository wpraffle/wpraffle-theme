<?php
/**
 * Search results template.
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
		<header class="diamond-archive-header mb-5">
			<h1 class="diamond-archive-title">
				<?php
				/* translators: %s: search query. */
				printf( esc_html__( 'Search results for: %s', 'wpraffle-theme' ), '<span>' . esc_html( get_search_query() ) . '</span>' );
				?>
			</h1>
			<div class="diamond-rule"></div>
		</header>

		<?php if ( have_posts() ) : ?>
			<div class="diamond-posts">
				<?php while ( have_posts() ) : ?>
					<?php the_post(); ?>
					<?php get_template_part( 'template-parts/content', 'search' ); ?>
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
</main>
<?php
get_footer();
