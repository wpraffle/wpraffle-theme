<?php
/**
 * Competition card for the WC shop fallback grid (used when the WPRaffles
 * plugin is inactive). When the plugin IS active, the [raffle_list] shortcode
 * renders its own .raffle-product-card markup which wpraffle.css styles.
 *
 * @package Diamond
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

global $product;
if ( ! $product ) {
	return;
}
?>
<div class="col-md-6 col-lg-4">
	<div class="diamond-card">
		<a href="<?php the_permalink(); ?>" class="diamond-card__media">
			<?php
			if ( has_post_thumbnail() ) {
				the_post_thumbnail( 'diamond-card' );
			} else {
				echo '<img src="' . esc_url( WPRAFFLE_THEME_URI . '/assets/images/placeholder-prize.svg' ) . '" alt="">';
			}
			?>
		</a>
		<div class="diamond-card__body">
			<h3 class="diamond-card__title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
			<?php if ( $product->get_price_html() ) : ?>
				<div class="diamond-card__price"><?php echo wp_kses_post( $product->get_price_html() ); ?></div>
			<?php endif; ?>
			<div class="diamond-card__footer">
				<a class="btn btn-accent btn-sm" href="<?php the_permalink(); ?>"><?php esc_html_e( 'Enter', 'wpraffle-theme' ); ?></a>
			</div>
		</div>
	</div>
</div>
