<?php
/**
 * Featured Competition spotlight (v1.2.0 Enhancement G — reworked).
 *
 * The original plan assumed a plugin "featured flag" that did not exist.
 * Two supported paths:
 *   1. Admin-picked raffle ID (theme setting `featured_raffle_id`) — works on
 *      any plugin version. This is the recommended, theme-only approach.
 *   2. With WPRaffle plugin v1.3.1+, fall back to the first raffle flagged
 *      is_featured=1 in the DB (the v16 migration column).
 *
 * @package WPRaffle_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! wpraffle_theme_has_plugin() ) {
	return;
}

global $wpdb;
$s = WPRaffle_Theme_Settings::instance()->get_settings();
$table = $wpdb->prefix . 'raffles';

$raffle = null;

// Path 1: explicit ID.
if ( ! empty( $s['featured_raffle_id'] ) ) {
	$raffle = $wpdb->get_row( $wpdb->prepare(
		"SELECT * FROM {$table} WHERE id = %d AND status = 'active' LIMIT 1",
		(int) $s['featured_raffle_id']
	) );
}

// Path 2: fall back to first featured-flagged raffle (plugin v1.3.1+).
if ( ! $raffle ) {
	$has_col = $wpdb->get_var( "SHOW COLUMNS FROM {$table} LIKE 'is_featured'" );
	if ( $has_col ) {
		$now = current_time( 'mysql' );
		$raffle = $wpdb->get_row( $wpdb->prepare(
			"SELECT * FROM {$table}
			 WHERE is_featured = 1 AND status = 'active'
			   AND ( draw_date IS NULL OR draw_date > %s )
			 ORDER BY draw_date ASC LIMIT 1",
			$now
		) );
	}
}

if ( ! $raffle ) {
	return;
}

// Resolve the raffle to a URL + image.
$product = $raffle->wc_product_id ? wc_get_product( $raffle->wc_product_id ) : null;
$enter_url = $product ? $product->get_permalink() : wpraffle_theme_competitions_url();
$image_id  = $product ? $product->get_image_id() : 0;
$image_url = $image_id ? wp_get_attachment_image_url( $image_id, 'wpr-card-wide' ) : '';

$price_html = $product ? wp_kses_post( $product->get_price_html() ) : ( $raffle->ticket_price ? esc_html( wc_price( $raffle->ticket_price ) ) : '' );

$heading = ! empty( $s['featured_title'] ) ? $s['featured_title'] : __( 'Featured Competition', 'wpraffle-theme' );
$badge   = ! empty( $s['featured_badge'] ) ? $s['featured_badge'] : __( 'Featured', 'wpraffle-theme' );

// Progress + remaining.
$total    = (int) ( $raffle->total_tickets ?: 0 );
$sold     = (int) ( $raffle->sold_tickets ?: 0 );
$remain   = max( 0, $total - $sold );
$pct      = $total > 0 ? min( 100, round( ( $sold / $total ) * 100 ) ) : 0;

// Countdown target (ISO for JS if the theme countdown script is reused).
$draw_iso = $raffle->draw_date ? gmdate( 'Y-m-d\TH:i:s\Z', strtotime( $raffle->draw_date ) ) : '';
?>

<section id="featured" class="section section--tint">
	<div class="container">
		<?php wpraffle_theme_section_heading( $heading, '' ); ?>

		<div class="wprt-featured-spotlight wprt-reveal">
			<div class="wprt-featured-spotlight__media" <?php echo $image_url ? 'style="background-image:url(\'' . esc_url( $image_url ) . '\')"' : ''; ?>>
				<span class="wprt-featured-spotlight__badge"><?php echo esc_html( $badge ); ?></span>
			</div>
			<div class="wprt-featured-spotlight__body">
				<h2 class="wprt-featured-spotlight__title"><?php echo esc_html( $raffle->title ); ?></h2>

				<div class="wprt-featured-spotlight__meta">
					<?php if ( $price_html ) : ?>
						<span class="wprt-featured-spotlight__price"><?php echo $price_html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped — wc_price / price_html is safe. ?></span>
					<?php endif; ?>
					<?php if ( $draw_iso ) : ?>
						<span class="wprt-featured-spotlight__countdown" data-draw-date="<?php echo esc_attr( $draw_iso ); ?>">
							<?php echo esc_html( __( 'Draws:', 'wpraffle-theme' ) . ' ' . date_i18n( get_option( 'date_format' ), strtotime( $raffle->draw_date ) ) ); ?>
						</span>
					<?php endif; ?>
				</div>

				<?php if ( $total > 0 ) : ?>
					<div class="rc-card__progress">
						<div class="rc-card__progress-stats">
							<span><?php echo esc_html( sprintf( __( '%d sold', 'wpraffle-theme' ), $sold ) ); ?></span>
							<span><?php echo esc_html( sprintf( _n( '%d ticket left', '%d tickets left', $remain, 'wpraffle-theme' ), $remain ) ); ?></span>
						</div>
						<div class="rc-card__progress-bar">
							<div class="rc-card__progress-fill" style="width:<?php echo (int) $pct; ?>%;"></div>
						</div>
					</div>
				<?php endif; ?>

				<div>
					<a class="btn btn-accent btn-lg" href="<?php echo esc_url( $enter_url ); ?>">
						<?php esc_html_e( 'Enter Now', 'wpraffle-theme' ); ?> <i class="fa-solid fa-arrow-right ms-1"></i>
					</a>
				</div>
			</div>
		</div>
	</div>
</section>
