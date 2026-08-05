<?php
/**
 * Default page template.
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
		<?php
		while ( have_posts() ) :
			the_post();
			?>
			<article id="post-<?php the_ID(); ?>" <?php post_class( 'wpr-page' ); ?>>
				<header class="wpr-page-header mb-4">
					<h1 class="wpr-page-title"><?php the_title(); ?></h1>
					<div class="wpr-rule"></div>
				</header>
				<?php if ( has_post_thumbnail() ) : ?>
					<div class="wpr-page-featured mb-4"><?php the_post_thumbnail( 'wpr-card-wide' ); ?></div>
				<?php endif; ?>
				<div class="wpr-page-content">
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
