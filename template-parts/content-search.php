<?php
/**
 * Search result content card.
 *
 * @package WPRaffle_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<article id="post-<?php the_ID(); ?>" <?php post_class( 'wpr-post' ); ?>>
	<div class="wpr-card mb-4">
		<div class="wpr-card__body">
			<div class="wpr-card__meta mb-2"><?php echo esc_html( get_post_type_object( get_post_type() )->labels->singular_name ); ?> &middot; <?php echo esc_html( get_the_date() ); ?></div>
			<h2 class="wpr-card__title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
			<div class="wpr-card__excerpt"><?php the_excerpt(); ?></div>
		</div>
	</div>
</article>
