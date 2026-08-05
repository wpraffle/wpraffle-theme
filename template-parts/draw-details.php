<?php
/**
 * Draw Details / odds disclosure block (DCMS Voluntary Code compliance).
 *
 * The Code requires operators to show, before entry: the likelihood of
 * winning, the draw mechanism, max entries per person, and access to full
 * T&Cs. The plugin already renders a live "odds" box inline; this block is a
 * complementary disclosure panel rendered after the entry form summarising
 * the draw's transparency information.
 *
 * Rendered via the woocommerce_after_single_product hook (see
 * class-wpraffle-theme-woocommerce.php or features), so it appears on the
 * single-raffle page below the entry form.
 *
 * @package WPRaffle_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! wpraffle_theme_has_plugin() ) {
	return;
}

global $wpdb, $product;

// Resolve the raffle row from the current product (or the global $raffle if set).
$raffle = isset( $GLOBALS['raffle'] ) ? $GLOBALS['raffle'] : null;
if ( ! $raffle && $product ) {
	$raffle_id = (int) get_post_meta( $product->get_id(), '_raffle_id', true );
	if ( $raffle_id ) {
		$raffle = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM {$wpdb->prefix}raffles WHERE id = %d", $raffle_id ) );
	}
}
if ( ! $raffle ) {
	return;
}

$s = WPRaffle_Theme_Settings::instance()->get_settings();

$total    = (int) ( $raffle->total_tickets ?: 0 );
$sold     = (int) ( $raffle->sold_tickets ?: 0 );
$remain   = max( 0, $total - $sold );
$max_pp   = (int) ( $raffle->max_tickets_per_user ?: 0 );

// Draw type label — friendly text for the stored draw_type value.
$draw_type_raw = isset( $raffle->draw_type ) ? $raffle->draw_type : '';
$draw_type_map = array(
	'auto' => __( 'Computer-randomised draw (auto)', 'wpraffle-theme' ),
	'live' => __( 'Live video draw', 'wpraffle-theme' ),
	'manual' => __( 'Manual operator draw', 'wpraffle-theme' ),
);
$draw_type_label = $draw_type_raw && isset( $draw_type_map[ $draw_type_raw ] ) ? $draw_type_map[ $draw_type_raw ] : __( 'Computer-randomised draw', 'wpraffle-theme' );
if ( ! empty( $s['draw_mechanism'] ) ) {
	$draw_type_label = $s['draw_mechanism'];
}

$terms_url = ! empty( $s['terms_url'] ) ? $s['terms_url'] : get_privacy_policy_url();

// Odds: 1 in (remaining / your tickets). With 1 ticket, 1 in remaining (or total if remaining unknown).
$odds_denom = $remain > 0 ? $remain : max( 1, $total );
?>
<details class="wprt-draw-details" <?php echo ( 'on' === $s['draw_details'] ) ? 'open' : ''; ?>>
	<summary class="wprt-draw-details__heading">
		<i class="fa-solid fa-circle-info" aria-hidden="true"></i>
		<?php esc_html_e( 'Draw details & odds of winning', 'wpraffle-theme' ); ?>
	</summary>
	<div class="wprt-draw-details__grid">
		<div class="wprt-draw-details__item">
			<span class="wprt-draw-details__label"><?php esc_html_e( 'Odds (1 ticket)', 'wpraffle-theme' ); ?></span>
			<span class="wprt-draw-details__value wprt-draw-details__value--accent">1 in <?php echo esc_html( number_format_i18n( $odds_denom ) ); ?></span>
		</div>
		<?php if ( $total > 0 ) : ?>
			<div class="wprt-draw-details__item">
				<span class="wprt-draw-details__label"><?php esc_html_e( 'Tickets', 'wpraffle-theme' ); ?></span>
				<span class="wprt-draw-details__value"><?php echo esc_html( number_format_i18n( $sold ) . ' / ' . number_format_i18n( $total ) ); ?></span>
			</div>
		<?php endif; ?>
		<?php if ( $max_pp > 0 ) : ?>
			<div class="wprt-draw-details__item">
				<span class="wprt-draw-details__label"><?php esc_html_e( 'Max entries / person', 'wpraffle-theme' ); ?></span>
				<span class="wprt-draw-details__value"><?php echo esc_html( number_format_i18n( $max_pp ) ); ?></span>
			</div>
		<?php endif; ?>
		<div class="wprt-draw-details__item">
			<span class="wprt-draw-details__label"><?php esc_html_e( 'Draw method', 'wpraffle-theme' ); ?></span>
			<span class="wprt-draw-details__value"><?php echo esc_html( $draw_type_label ); ?></span>
		</div>
	</div>
	<p class="wprt-draw-details__footer">
		<?php esc_html_e( 'Prizes are awarded in accordance with the laws of chance. No purchase necessary — a free postal entry route is available with equal winning odds.', 'wpraffle-theme' ); ?>
		<?php if ( $terms_url ) : ?>
			<a href="<?php echo esc_url( $terms_url ); ?>"><?php esc_html_e( 'Read the full terms & conditions →', 'wpraffle-theme' ); ?></a>
		<?php endif; ?>
	</p>
</details>
