<?php
/**
 * Search result content card.
 *
 * @package Diamond
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<article id="post-<?php the_ID(); ?>" <?php post_class( 'diamond-post' ); ?>>
	<div class="diamond-card mb-4">
		<div class="diamond-card__body">
			<div class="diamond-card__meta mb-2"><?php echo esc_html( get_post_type_object( get_post_type() )->labels->singular_name ); ?> &middot; <?php echo esc_html( get_the_date() ); ?></div>
			<h2 class="diamond-card__title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
			<div class="diamond-card__excerpt"><?php the_excerpt(); ?></div>
		</div>
	</div>
</article>
