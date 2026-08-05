<?php
/**
 * Settings page — FAQs tab (repeatable question/answer fields).
 *
 * @package WPRaffle_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$s    = WPRaffle_Theme_Settings::instance()->get_settings();
$faqs = isset( $s['faqs'] ) && is_array( $s['faqs'] ) ? $s['faqs'] : array();
?>
<div class="wpr-panel">
	<p class="wpr-panel-intro"><?php esc_html_e( 'Add frequently asked questions. They appear on the homepage FAQ accordion section (if enabled on the Homepage tab).', 'wpraffle-theme' ); ?></p>

	<div class="wprt-repeatable" data-target="faqs">
		<div class="wprt-repeatable-rows">
			<?php
			if ( empty( $faqs ) ) {
				$faqs = array( array( 'question' => '', 'answer' => '' ) );
			}
			foreach ( $faqs as $i => $faq ) :
				?>
				<div class="wprt-repeatable-row">
					<p>
						<input type="text" name="wpr_settings[faqs][<?php echo esc_attr( $i ); ?>][question]" value="<?php echo esc_attr( isset( $faq['question'] ) ? $faq['question'] : '' ); ?>" class="widefat" placeholder="<?php esc_attr_e( 'Question…', 'wpraffle-theme' ); ?>">
					</p>
					<p>
						<textarea name="wpr_settings[faqs][<?php echo esc_attr( $i ); ?>][answer]" rows="3" class="large-text" placeholder="<?php esc_attr_e( 'Answer…', 'wpraffle-theme' ); ?>"><?php echo esc_textarea( isset( $faq['answer'] ) ? $faq['answer'] : '' ); ?></textarea>
					</p>
					<p>
						<button type="button" class="button wprt-remove-row"><?php esc_html_e( 'Remove', 'wpraffle-theme' ); ?></button>
					</p>
					<hr>
				</div>
			<?php endforeach; ?>
		</div>
		<p>
			<button type="button" class="button button-secondary wprt-add-row">
				<span class="dashicons dashicons-plus-alt2" style="vertical-align:text-top;"></span>
				<?php esc_html_e( 'Add FAQ', 'wpraffle-theme' ); ?>
			</button>
		</p>
	</div>
</div>

<script type="text/template" class="wprt-template-faqs">
	<div class="wprt-repeatable-row">
		<p><input type="text" name="" value="" class="widefat" placeholder="<?php esc_attr_e( 'Question…', 'wpraffle-theme' ); ?>"></p>
		<p><textarea name="" rows="3" class="large-text" placeholder="<?php esc_attr_e( 'Answer…', 'wpraffle-theme' ); ?>"></textarea></p>
		<p><button type="button" class="button wprt-remove-row"><?php esc_html_e( 'Remove', 'wpraffle-theme' ); ?></button></p>
		<hr>
	</div>
</script>
