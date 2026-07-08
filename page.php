<?php
/**
 * Default page template.
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
		<?php
		while ( have_posts() ) :
			the_post();
			?>
			<article id="post-<?php the_ID(); ?>" <?php post_class( 'diamond-page' ); ?>>
				<header class="diamond-page-header mb-4">
					<h1 class="diamond-page-title"><?php the_title(); ?></h1>
					<div class="diamond-rule"></div>
				</header>
				<?php if ( has_post_thumbnail() ) : ?>
					<div class="diamond-page-featured mb-4"><?php the_post_thumbnail( 'diamond-card-wide' ); ?></div>
				<?php endif; ?>
				<div class="diamond-page-content">
					<?php
					the_content();
					wp_link_pages();
					?>
				</div>
			</article>
			<?php
			if ( comments_open() || get_comments_number() ) {
				comments_template();
			}
		endwhile;
		?>
	</div>
</main>
<?php
get_footer();
