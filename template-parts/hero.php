<?php
/**
 * Homepage hero banner.
 *
 * v1.2.0 additions:
 *  - Optional background video (Enhancement O) with a WCAG pause control.
 *  - Trustpilot TrustBox slot (Enhancement I).
 *  - Hero stat counters: numbers carry data-count-* attributes so v1.2.0.js
 *    animates them. Reduced-motion users see the final value (JS + CSS).
 *
 * @package WPRaffle_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$s = WPRaffle_Theme_Settings::instance()->get_settings();

$bg_id  = get_theme_mod( 'wpr_hero_bg' );
$bg_url = $bg_id ? wp_get_attachment_image_url( $bg_id, 'wpr-hero' ) : '';

$video_url = ! empty( $s['hero_video'] ) ? esc_url( $s['hero_video'] ) : '';
// Suppress the inline video on small screens (perf) — handled via media query + a body class.
$show_video = $video_url && ! wp_is_mobile();

/**
 * Turn a hero stat string like "12,400+", "£2.8m", "4.9★" into the data
 * attributes the counter JS needs (numeric target + prefix + suffix).
 * Falls back gracefully: if parsing fails, the raw value is printed untouched.
 */
$hero_stat_attrs = function ( $raw ) {
	$out     = array( 'prefix' => '', 'target' => '', 'suffix' => '' );
	$matched = array();
	// Capture a leading non-digit prefix (currency/symbol) + the numeric body + trailing suffix.
	if ( preg_match( '/^([^\d]*)([\d,]+(?:\.\d+)?)(.*)$/', (string) $raw, $matched ) ) {
		$out['prefix'] = $matched[1];
		$out['target'] = (float) str_replace( ',', '', $matched[2] );
		// Decimals preserved for things like "4.9".
		$out['decimals'] = ( false !== strpos( $matched[2], '.' ) ) ? strlen( substr( strstr( $matched[2], '.' ), 1 ) ) : 0;
		$out['suffix']   = $matched[3];
	}
	return $out;
};

$stat_winners = get_theme_mod( 'wpr_stat_winners', '12,400+' );
$stat_raised  = get_theme_mod( 'wpr_stat_raised', '£2.8m' );
$stat_rating  = get_theme_mod( 'wpr_stat_rating', '4.9★' );

$tp_id   = ! empty( $s['trustpilot_business_id'] ) ? $s['trustpilot_business_id'] : '';
$tp_show = in_array( $s['trustpilot_position'], array( 'hero', 'both' ), true ) && $tp_id;
?>
<section class="wpr-hero">
	<?php if ( $show_video ) : ?>
		<video class="wpr-hero__video" autoplay muted loop playsinline preload="metadata" aria-hidden="true">
			<source src="<?php echo esc_url( $video_url ); ?>">
		</video>
		<div class="wprt-hero-video-controls">
			<button type="button" class="wprt-hero-video-pause" aria-label="<?php esc_attr_e( 'Pause hero video', 'wpraffle-theme' ); ?>"><i class="fa-solid fa-pause" aria-hidden="true"></i></button>
		</div>
	<?php elseif ( $bg_url ) : ?>
		<div class="wpr-hero__bg" style="background-image:url('<?php echo esc_url( $bg_url ); ?>');"></div>
	<?php endif; ?>
	<div class="container">
		<div class="wpr-hero__inner">
			<span class="eyebrow" style="color:#fff;opacity:.85;"><?php echo esc_html( get_theme_mod( 'wpr_hero_eyebrow', __( 'Luxury Prize Competitions', 'wpraffle-theme' ) ) ); ?></span>
			<h1 class="wpr-hero__title">
				<?php
				echo wp_kses_post( get_theme_mod( 'wpr_hero_title', __( 'Win incredible prizes <span class="accent">every week</span>', 'wpraffle-theme' ) ) );
				?>
			</h1>
			<p class="wpr-hero__lead"><?php echo esc_html( get_theme_mod( 'wpr_hero_lead', __( 'Low fixed odds, instant payouts and 100% of ticket sales donated to charity. Enter your favourite competition today.', 'wpraffle-theme' ) ) ); ?></p>
			<div class="wpr-hero__actions">
				<?php if ( class_exists( 'WooCommerce' ) ) : ?>
					<a class="btn btn-accent btn-lg" href="<?php echo esc_url( get_permalink( wc_get_page_id( 'shop' ) ) ); ?>"><?php esc_html_e( 'Enter Competitions', 'wpraffle-theme' ); ?> <i class="fa-solid fa-arrow-right ms-1"></i></a>
				<?php endif; ?>
				<a class="btn btn-outline-light btn-lg" href="#winners"><?php esc_html_e( 'See Recent Winners', 'wpraffle-theme' ); ?></a>
			</div>

			<?php if ( $tp_show ) : ?>
				<div class="wprt-trustpilot-slot wprt-trustpilot-slot--hero">
					<!-- Trustpilot TrustBox widget. The official script + container. -->
					<div class="trustpilot-widget" data-locale="en-GB" data-template-id="5613c9cde69ddbf09bb0cadd" data-businessunit-id="<?php echo esc_attr( $tp_id ); ?>" data-style-height="52px" data-style-width="100%">
						<a href="https://uk.trustpilot.com/review/<?php echo esc_attr( wp_parse_url( home_url(), PHP_URL_HOST ) ); ?>" target="_blank" rel="noopener"><?php esc_html_e( 'Trustpilot', 'wpraffle-theme' ); ?></a>
					</div>
				</div>
			<?php endif; ?>

			<div class="wpr-hero__stats">
				<?php
				$stats = array(
					array( 'value' => $stat_winners, 'label' => __( 'Winners', 'wpraffle-theme' ) ),
					array( 'value' => $stat_raised,  'label' => __( 'Raised for Charity', 'wpraffle-theme' ) ),
					array( 'value' => $stat_rating,  'label' => __( 'Trustpilot', 'wpraffle-theme' ) ),
				);
				foreach ( $stats as $stat ) :
					$a = $hero_stat_attrs( $stat['value'] );
					$has_target = '' !== $a['target'];
					?>
					<div class="wpr-hero__stat">
						<div class="num"
							<?php if ( $has_target && 'on' === $s['hero_counters'] ) : ?>
								data-count-to="<?php echo esc_attr( $a['target'] ); ?>"
								data-count-prefix="<?php echo esc_attr( $a['prefix'] ); ?>"
								data-count-suffix="<?php echo esc_attr( $a['suffix'] ); ?>"
								data-count-decimals="<?php echo esc_attr( isset( $a['decimals'] ) ? $a['decimals'] : 0 ); ?>"
							<?php endif; ?>>
							<?php echo esc_html( $stat['value'] ); ?>
						</div>
						<div class="label"><?php echo esc_html( $stat['label'] ); ?></div>
					</div>
				<?php endforeach; ?>
			</div>
		</div>
	</div>
</section>
