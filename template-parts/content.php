<?php
/**
 * Default content card for posts.
 *
 * @package WPRaffle_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<article id="post-<?php the_ID(); ?>" <?php post_class( 'wpr-post' ); ?>>
	<div class="wpr-card mb-4">
		<?php if ( has_post_thumbnail() ) : ?>
			<a href="<?php the_permalink(); ?>" class="wpr-card__media">
				<?php the_post_thumbnail( 'wpr-card-wide' ); ?>
			</a>
		<?php endif; ?>
		<div class="wpr-card__body">
			<div class="wpr-card__meta mb-2">
				<?php echo esc_html( get_the_date() ); ?> &middot; <?php the_category( ', ' ); ?>
			</div>
			<h2 class="wpr-card__title">
				<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
			</h2>
			<div class="wpr-card__excerpt">
				<?php the_excerpt(); ?>
			</div>
			<div class="wpr-card__footer">
				<a class="btn btn-outline-accent btn-sm" href="<?php the_permalink(); ?>"><?php esc_html_e( 'Read more', 'wpraffle-theme' ); ?></a>
			</div>
		</div>
	</div>
</article>
