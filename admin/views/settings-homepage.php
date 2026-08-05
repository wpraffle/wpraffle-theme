<?php
/**
 * Settings page — Homepage builder tab.
 *
 * A drag-and-drop section manager (v1.2.0). Each section is a card with a
 * drag handle, icon, title, description, and an enable toggle. Reordering the
 * rows updates hidden "order" inputs, so the existing save_homepage_tab()
 * handler (which reads sections[key][order] + sections[key][enabled]) works
 * unchanged — the drag UI is a progressive enhancement over the old numeric
 * inputs.
 *
 * @package WPRaffle_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$s = WPRaffle_Theme_Settings::instance()->get_settings();

// Human labels + descriptions + icons for each section. The raw key (e.g.
// how_it_works) is shown only as a subtle hint, never as the primary label.
$section_meta = array(
	'hero'          => array(
		'label'       => __( 'Hero Banner', 'wpraffle-theme' ),
		'description' => __( 'Large headline banner with background image, stats and dual CTA.', 'wpraffle-theme' ),
		'icon'        => 'dashicons-format-image',
	),
	'winners'       => array(
		'label'       => __( 'Featured Winners', 'wpraffle-theme' ),
		'description' => __( 'Carousel of recent winners with photos and draw videos.', 'wpraffle-theme' ),
		'icon'        => 'dashicons-awards',
	),
	'active'        => array(
		'label'       => __( 'Active Competitions', 'wpraffle-theme' ),
		'description' => __( 'Live raffle grid with category filter tabs.', 'wpraffle-theme' ),
		'icon'        => 'dashicons-grid-view',
	),
	'countdown'     => array(
		'label'       => __( 'Next Draw Countdown', 'wpraffle-theme' ),
		'description' => __( 'Countdown timer to the soonest-drawing competition.', 'wpraffle-theme' ),
		'icon'        => 'dashicons-clock',
	),
	'live_draw'     => array(
		'label'       => __( 'Live Draw', 'wpraffle-theme' ),
		'description' => __( 'Embedded live-draw video for the next competition.', 'wpraffle-theme' ),
		'icon'        => 'dashicons-video-alt3',
	),
	'charity'       => array(
		'label'       => __( 'Charity Donations', 'wpraffle-theme' ),
		'description' => __( 'Total raised + charities grid.', 'wpraffle-theme' ),
		'icon'        => 'dashicons-heart',
	),
	'testimonials'  => array(
		'label'       => __( 'Testimonials', 'wpraffle-theme' ),
		'description' => __( 'Customer review carousel (real Trustpilot reviews when connected).', 'wpraffle-theme' ),
		'icon'        => 'dashicons-format-chat',
	),
	'faq'           => array(
		'label'       => __( 'FAQ Accordion', 'wpraffle-theme' ),
		'description' => __( 'Frequently asked questions, editable on the FAQs tab.', 'wpraffle-theme' ),
		'icon'        => 'dashicons-editor-help',
	),
	'trust'         => array(
		'label'       => __( 'Trust Block', 'wpraffle-theme' ),
		'description' => __( 'Secure payments / free entry / verified draws pillars.', 'wpraffle-theme' ),
		'icon'        => 'dashicons-shield-alt',
	),
	'how_it_works'  => array(
		'label'       => __( 'How It Works', 'wpraffle-theme' ),
		'description' => __( '4-step illustrated walkthrough (configure content on the Enhancements tab).', 'wpraffle-theme' ),
		'icon'        => 'dashicons-lightbulb',
	),
	'stats_counter' => array(
		'label'       => __( 'Stats Counter Strip', 'wpraffle-theme' ),
		'description' => __( 'Animated counter strip — total raised, winners, average rating.', 'wpraffle-theme' ),
		'icon'        => 'dashicons-chart-bar',
	),
	'featured'      => array(
		'label'       => __( 'Featured Spotlight', 'wpraffle-theme' ),
		'description' => __( 'A single oversized highlighted competition card.', 'wpraffle-theme' ),
		'icon'        => 'dashicons-star-filled',
	),
);

$sections = isset( $s['sections'] ) ? $s['sections'] : array();

// Sort the meta by the saved order so the builder opens showing the live order.
$ordered_keys = array();
foreach ( $section_meta as $key => $meta ) {
	$order = isset( $sections[ $key ]['order'] ) ? (int) $sections[ $key ]['order'] : 99;
	$ordered_keys[ $key ] = $order;
}
asort( $ordered_keys );
?>
<div class="wpr-panel">
	<p class="wpr-panel-intro"><?php esc_html_e( 'Drag the sections to reorder your homepage. Toggle the switch to show or hide each section. Changes apply on save.', 'wpraffle-theme' ); ?></p>

	<div class="wprt-homepage-builder" id="wprt-homepage-builder">
		<?php $position = 1; foreach ( $ordered_keys as $key => $order ) :
			$meta  = $section_meta[ $key ];
			$cfg   = isset( $sections[ $key ] ) ? $sections[ $key ] : array();
			$on    = ! empty( $cfg['enabled'] ) || ( is_array( $cfg ) && isset( $cfg['enabled'] ) && $cfg['enabled'] );
			?>
			<div class="wprt-builder-row<?php echo $on ? ' is-enabled' : ''; ?>" data-section="<?php echo esc_attr( $key ); ?>">
				<input type="hidden" name="wpr_settings[sections][<?php echo esc_attr( $key ); ?>][order]" value="<?php echo esc_attr( $order ); ?>" class="wprt-builder-order-input">

				<div class="wprt-builder-row__handle" title="<?php esc_attr_e( 'Drag to reorder', 'wpraffle-theme' ); ?>">
					<span class="dashicons dashicons-menu"></span>
					<span class="wprt-builder-row__pos"><?php echo (int) $position; ?></span>
				</div>

				<span class="wprt-builder-row__icon dashicons <?php echo esc_attr( $meta['icon'] ); ?>"></span>

				<div class="wprt-builder-row__content">
					<strong class="wprt-builder-row__title"><?php echo esc_html( $meta['label'] ); ?></strong>
					<span class="wprt-builder-row__desc"><?php echo esc_html( $meta['description'] ); ?></span>
				</div>

				<div class="wprt-builder-row__actions">
					<label class="wprt-builder-toggle">
						<input type="checkbox" name="wpr_settings[sections][<?php echo esc_attr( $key ); ?>][enabled]" value="1" <?php checked( $on ); ?>>
						<span class="wprt-builder-toggle__track" aria-hidden="true"><span class="wprt-builder-toggle__thumb"></span></span>
						<span class="screen-reader-text"><?php echo esc_html( sprintf( /* translators: section name */ __( 'Enable %s', 'wpraffle-theme' ), $meta['label'] ) ); ?></span>
					</label>
				</div>
			</div>
		<?php $position++; endforeach; ?>
	</div>

	<p class="description" style="margin-top:1rem;">
		<span class="dashicons dashicons-info-outline" style="vertical-align:-3px;"></span>
		<?php esc_html_e( 'Some sections only appear when the WPRaffles plugin is active and there is content to show (e.g. winners, countdown, live draw). Disabled or empty sections are hidden automatically.', 'wpraffle-theme' ); ?>
	</p>
</div>
