<?php
/**
 * Stats Counter strip (v1.2.0 Enhancement H).
 *
 * A narrow dark strip with animated counters. Reuses the same counter JS as
 * the hero stats (data-count-* attributes). Uses the charity total when the
 * plugin is available; otherwise shows the configured static values.
 *
 * @package WPRaffle_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$s = WPRaffle_Theme_Settings::instance()->get_settings();

// Total raised — prefer the live plugin figure when available.
$raised = '0';
if ( class_exists( 'WPRaffle_Theme_Integration' ) && method_exists( 'WPRaffle_Theme_Integration', 'get_total_raised' ) ) {
	$raised = WPRaffle_Theme_Integration::get_total_raised();
}

// Four counters. The .num gets data-count-* so v1.2.0.js animates it.
$stats = array(
	array(
		'prefix'   => '£',
		'target'   => (float) preg_replace( '/[^\d.]/', '', (string) $raised ) ?: 0,
		'decimals' => 0,
		'suffix'   => '',
		'label'    => __( 'Raised for Charity', 'wpraffle-theme' ),
	),
	array(
		'prefix'   => '',
		'target'   => (float) preg_replace( '/[^\d.]/', '', (string) get_theme_mod( 'wpr_stat_winners', '12400' ) ) ?: 0,
		'decimals' => 0,
		'suffix'   => '+',
		'label'    => __( 'Happy Winners', 'wpraffle-theme' ),
	),
	array(
		'prefix'   => '',
		'target'   => 100,
		'decimals' => 0,
		'suffix'   => '%',
		'label'    => __( 'Guaranteed Draws', 'wpraffle-theme' ),
	),
	array(
		'prefix'   => '',
		'target'   => 4.9,
		'decimals' => 1,
		'suffix'   => '★',
		'label'    => __( 'Average Rating', 'wpraffle-theme' ),
	),
);
?>
<section id="stats-counter" class="section section--dark" style="padding:3rem 0;">
	<div class="container">
		<div class="wprt-stats-counter wprt-reveal-stagger">
			<?php foreach ( $stats as $stat ) : ?>
				<div class="wprt-stats-counter__item">
					<span class="num"
						<?php if ( 'on' === $s['hero_counters'] ) : ?>
							data-count-to="<?php echo esc_attr( $stat['target'] ); ?>"
							data-count-prefix="<?php echo esc_attr( $stat['prefix'] ); ?>"
							data-count-suffix="<?php echo esc_attr( $stat['suffix'] ); ?>"
							data-count-decimals="<?php echo esc_attr( $stat['decimals'] ); ?>"
						<?php endif; ?>>
						<?php echo esc_html( $stat['prefix'] . ( $stat['decimals'] ? number_format( $stat['target'], $stat['decimals'] ) : number_format( $stat['target'] ) ) . $stat['suffix'] ); ?>
					</span>
					<span class="label"><?php echo esc_html( $stat['label'] ); ?></span>
				</div>
			<?php endforeach; ?>
		</div>
	</div>
</section>
