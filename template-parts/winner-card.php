<?php
/**
 * Single featured winner card. Receives a featured-winner row via $args['winner'].
 *
 * The row comes from Raffle_Featured_Winners::get_featured() which JOINs the
 * raffles table, so it carries: winner_name, winner_photo_id, testimonial,
 * raffle_title, prize_image, wc_product_id.
 *
 * v1.2.0: surfaces a "Watch the draw" overlay when the source raffle has a
 * draw_video_url, and emphasises the prize + draw date as proof of life.
 *
 * @package WPRaffle_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$fw = isset( $args['winner'] ) ? $args['winner'] : null;
if ( ! $fw ) {
	return;
}

// Winner photo: prefer the dedicated winner photo, fall back to the prize image.
$photo = WPRaffle_Theme_Integration::get_winner_photo_url( $fw, 'wpr-winner' );
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

// Draw video URL — pulled from the source raffle when available.
$video_url = '';
if ( ! empty( $fw->raffle_id ) ) {
	global $wpdb;
	$video_url = $wpdb->get_var( $wpdb->prepare(
		"SELECT draw_video_url FROM {$wpdb->prefix}raffles WHERE id = %d",
		(int) $fw->raffle_id
	) );
}
?>
<div class="swiper-slide">
	<div class="wpr-winner-card">
		<div class="wpr-winner-card__media">
			<img src="<?php echo esc_url( $photo ); ?>" alt="<?php echo esc_attr( $name ?: $prize ); ?>" loading="lazy">
			<?php wpraffle_theme_winner_badge( 'main' ); ?>
			<?php if ( $video_url ) : ?>
				<a class="wpr-winner-card__video-btn" href="<?php echo esc_url( $video_url ); ?>" data-fancybox aria-label="<?php esc_attr_e( 'Watch the draw', 'wpraffle-theme' ); ?>">
					<span class="wpr-winner-card__video-btn-inner"><i class="fa-solid fa-play" aria-hidden="true"></i></span>
				</a>
			<?php endif; ?>
		</div>
		<div class="wpr-winner-card__body">
			<?php if ( $prize ) : ?>
				<p class="wpr-winner-card__prize"><?php echo esc_html( sprintf( __( 'Won: %s', 'wpraffle-theme' ), $prize ) ); ?></p>
			<?php endif; ?>
			<?php if ( $name ) : ?>
				<h3 class="wpr-winner-card__name"><?php echo esc_html( $name ); ?></h3>
			<?php endif; ?>
			<?php if ( $quote ) : ?>
				<p class="wpr-winner-card__quote">&ldquo;<?php echo esc_html( $quote ); ?>&rdquo;</p>
			<?php endif; ?>
			<?php if ( $date ) : ?>
				<div class="wpr-winner-card__date"><?php echo esc_html( sprintf( __( 'Drawn %s', 'wpraffle-theme' ), $date ) ); ?></div>
			<?php endif; ?>
			<?php if ( $video_url ) : ?>
				<a class="wpr-winner-card__watch-link" href="<?php echo esc_url( $video_url ); ?>" data-fancybox>
					<i class="fa-solid fa-circle-play" aria-hidden="true"></i> <?php esc_html_e( 'Watch the draw', 'wpraffle-theme' ); ?>
				</a>
			<?php endif; ?>
		</div>
	</div>
</div>
