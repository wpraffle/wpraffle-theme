<?php
/**
 * Competition card for the WC shop fallback grid (used when the WPRaffles
 * plugin is inactive). When the plugin IS active, the [raffle_list] shortcode
 * renders its own .raffle-product-card markup which wpraffle.css styles.
 *
 * @package WPRaffle_Theme
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
	<div class="wpr-card">
		<a href="<?php the_permalink(); ?>" class="wpr-card__media">
			<?php
			if ( has_post_thumbnail() ) {
				the_post_thumbnail( 'wpr-card' );
			} else {
				echo '<img src="' . esc_url( WPRAFFLE_THEME_URI . '/assets/images/placeholder-prize.svg' ) . '" alt="">';
			}
			?>
		</a>
		<div class="wpr-card__body">
			<h3 class="wpr-card__title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
			<?php if ( $product->get_price_html() ) : ?>
				<div class="wpr-card__price"><?php echo wp_kses_post( $product->get_price_html() ); ?></div>
			<?php endif; ?>
			<div class="wpr-card__footer">
				<a class="btn btn-accent btn-sm" href="<?php the_permalink(); ?>"><?php esc_html_e( 'Enter', 'wpraffle-theme' ); ?></a>
			</div>
		</div>
	</div>
</div>
