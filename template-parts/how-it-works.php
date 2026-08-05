<?php
/**
 * How It Works section (v1.2.0 Enhancement F).
 *
 * A 3–4 step illustrated walkthrough. Fully editable from Theme Options →
 * Enhancements. Falls back to sensible defaults so the section always renders
 * something useful even before the operator configures it.
 *
 * @package WPRaffle_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$s = WPRaffle_Theme_Settings::instance()->get_settings();

$title    = $s['hiw_title']    ? $s['hiw_title']    : __( 'How It Works', 'wpraffle-theme' );
$subtitle = $s['hiw_subtitle'] ? $s['hiw_subtitle'] : __( 'Four simple steps to your next win.', 'wpraffle-theme' );

// Each step: icon / title / text, with defaults.
$steps = array(
	array(
		'icon'  => $s['hiw_step1_icon']  ?: 'fa-mouse-pointer',
		'title' => $s['hiw_step1_title'] ?: __( 'Pick a Competition', 'wpraffle-theme' ),
		'text'  => $s['hiw_step1_text']  ?: __( 'Browse our live draws and choose the prize you love.', 'wpraffle-theme' ),
	),
	array(
		'icon'  => $s['hiw_step2_icon']  ?: 'fa-question',
		'title' => $s['hiw_step2_title'] ?: __( 'Answer the Question', 'wpraffle-theme' ),
		'text'  => $s['hiw_step2_text']  ?: __( 'Answer a simple skill question and choose your tickets.', 'wpraffle-theme' ),
	),
	array(
		'icon'  => $s['hiw_step3_icon']  ?: 'fa-hourglass-half',
		'title' => $s['hiw_step3_title'] ?: __( 'Wait for the Draw', 'wpraffle-theme' ),
		'text'  => $s['hiw_step3_text']  ?: __( 'Watch the live draw or get notified the moment it closes.', 'wpraffle-theme' ),
	),
	array(
		'icon'  => $s['hiw_step4_icon']  ?: 'fa-trophy',
		'title' => $s['hiw_step4_title'] ?: __( 'Win!', 'wpraffle-theme' ),
		'text'  => $s['hiw_step4_text']  ?: __( 'Winners are paid out instantly — no waiting around.', 'wpraffle-theme' ),
	),
);

// Drop steps with no title AND no text (lets operators hide a step by blanking both).
$steps = array_filter( $steps, function ( $step ) {
	return ! empty( $step['title'] ) || ! empty( $step['text'] );
} );
?>
<section id="how-it-works" class="section section--tint">
	<div class="container">
		<?php wpraffle_theme_section_heading( $title, $subtitle ); ?>
		<div class="wprt-how-it-works wprt-reveal-stagger">
			<?php foreach ( $steps as $step ) : ?>
				<div class="wprt-how-it-works__step">
					<div class="wprt-how-it-works__icon"><i class="fa-solid <?php echo esc_attr( $step['icon'] ); ?>" aria-hidden="true"></i></div>
					<div class="wprt-how-it-works__num" aria-hidden="true"></div>
					<?php if ( ! empty( $step['title'] ) ) : ?>
						<h3 class="wprt-how-it-works__title"><?php echo esc_html( $step['title'] ); ?></h3>
					<?php endif; ?>
					<?php if ( ! empty( $step['text'] ) ) : ?>
						<p class="wprt-how-it-works__text"><?php echo esc_html( $step['text'] ); ?></p>
					<?php endif; ?>
				</div>
			<?php endforeach; ?>
		</div>
	</div>
</section>
