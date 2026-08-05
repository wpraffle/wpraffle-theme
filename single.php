<?php
/**
 * Single post template.
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
		<div class="row">
			<div class="col-lg-8">
				<?php
				while ( have_posts() ) :
					the_post();
					get_template_part( 'template-parts/content', get_post_type() );

					if ( comments_open() || get_comments_number() ) {
						comments_template();
					}
				endwhile;
				?>
			</div>
			<aside class="col-lg-4">
				<?php get_sidebar(); ?>
			</aside>
		</div>
	</div>
</main>
<?php
get_footer();
