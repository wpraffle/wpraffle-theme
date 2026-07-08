<?php
/**
 * Single featured winner card. Receives a featured-winner row via $args['winner'].
 *
 * The row comes from Raffle_Featured_Winners::get_featured() which JOINs the
 * raffles table, so it carries: winner_name, winner_photo_id, testimonial,
 * raffle_title, prize_image, wc_product_id.
 *
 * @package Diamond
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$fw = isset( $args['winner'] ) ? $args['winner'] : null;
if ( ! $fw ) {
	return;
}

// Winner photo: prefer the dedicated winner photo, fall back to the prize image.
$photo = WPRaffle_Theme_Integration::get_winner_photo_url( $fw, 'diamond-winner' );
if ( ! $photo && ! empty( $fw->prize_image ) ) {
	$photo = $fw->prize_image;
}
if ( ! $photo ) {
	$photo = WPRAFFLE_THEME_URI . '/assets/images/placeholder-winner.svg';
}

// Privacy-aware winner name.
$name = '';
if ( ! empty( $fw->winner_name ) && method_exists( 'Raffle_Public', 'winner_display_name' ) ) {
	$name = Raffle_Public::winner_display_name( $fw->winner_name );
} elseif ( ! empty( $fw->winner_name ) ) {
	$name = $fw->winner_name;
}

$prize = ! empty( $fw->raffle_title ) ? $fw->raffle_title : '';
$quote = ! empty( $fw->testimonial ) ? $fw->testimonial : '';
$date  = ! empty( $fw->updated_at ) ? wp_date( get_option( 'date_format' ), strtotime( $fw->updated_at ) ) : '';
?>
<div class="swiper-slide">
	<div class="diamond-winner-card">
		<div class="diamond-winner-card__media">
			<img src="<?php echo esc_url( $photo ); ?>" alt="<?php echo esc_attr( $name ?: $prize ); ?>" loading="lazy">
			<?php wpraffle_theme_winner_badge( 'main' ); ?>
		</div>
		<div class="diamond-winner-card__body">
			<?php if ( $name ) : ?>
				<h3 class="diamond-winner-card__name"><?php echo esc_html( $name ); ?></h3>
			<?php endif; ?>
			<?php if ( $prize ) : ?>
				<p class="diamond-winner-card__prize"><?php echo esc_html( $prize ); ?></p>
			<?php endif; ?>
			<?php if ( $quote ) : ?>
				<p class="diamond-winner-card__quote">&ldquo;<?php echo esc_html( $quote ); ?>&rdquo;</p>
			<?php endif; ?>
			<?php if ( $date ) : ?>
				<div class="diamond-winner-card__date"><?php echo esc_html( $date ); ?></div>
			<?php endif; ?>
		</div>
	</div>
</div>
