<?php
/**
 * FAQ accordion section — pulls from the Theme Options FAQs tab.
 *
 * @package WPRaffle_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$s    = WPRaffle_Theme_Settings::instance()->get_settings();
$faqs = isset( $s['faqs'] ) && is_array( $s['faqs'] ) ? $s['faqs'] : array();

if ( empty( $faqs ) ) {
	return;
}
?>
<section id="faq" class="section">
	<div class="container" style="max-width:800px;">
		<?php
		wpraffle_theme_section_heading(
			get_theme_mod( 'wpr_faq_title', __( 'Frequently Asked Questions', 'wpraffle-theme' ) ),
			get_theme_mod( 'wpr_faq_subtitle', __( 'Everything you need to know about how we run our competitions.', 'wpraffle-theme' ) )
		);
		?>
		<div class="wprt-faq-accordion">
			<?php foreach ( $faqs as $i => $faq ) : ?>
				<?php
				$question = isset( $faq['question'] ) ? $faq['question'] : '';
				$answer   = isset( $faq['answer'] ) ? $faq['answer'] : '';
				if ( ! $question && ! $answer ) {
					continue;
				}
				?>
				<div class="wprt-faq-item">
					<button class="wprt-faq-question" type="button" aria-expanded="<?php echo 0 === $i ? 'true' : 'false'; ?>">
						<?php echo esc_html( $question ); ?>
						<i class="fa-solid fa-chevron-down wprt-faq-icon"></i>
					</button>
					<div class="wprt-faq-answer"<?php echo 0 === $i ? ' style="display:block;"' : ''; ?>>
						<?php echo wp_kses_post( wpautop( $answer ) ); ?>
					</div>
				</div>
			<?php endforeach; ?>
		</div>
	</div>
</section>
