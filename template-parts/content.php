<?php
/**
 * Default content card for posts.
 *
 * @package Diamond
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<article id="post-<?php the_ID(); ?>" <?php post_class( 'diamond-post' ); ?>>
	<div class="diamond-card mb-4">
		<?php if ( has_post_thumbnail() ) : ?>
			<a href="<?php the_permalink(); ?>" class="diamond-card__media">
				<?php the_post_thumbnail( 'diamond-card-wide' ); ?>
			</a>
		<?php endif; ?>
		<div class="diamond-card__body">
			<div class="diamond-card__meta mb-2">
				<?php echo esc_html( get_the_date() ); ?> &middot; <?php the_category( ', ' ); ?>
			</div>
			<h2 class="diamond-card__title">
				<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
			</h2>
			<div class="diamond-card__excerpt">
				<?php the_excerpt(); ?>
			</div>
			<div class="diamond-card__footer">
				<a class="btn btn-outline-accent btn-sm" href="<?php the_permalink(); ?>"><?php esc_html_e( 'Read more', 'wpraffle-theme' ); ?></a>
			</div>
		</div>
	</div>
</article>
