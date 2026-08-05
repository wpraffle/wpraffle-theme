<?php
/**
 * Settings page — Testimonials tab (repeatable fields).
 *
 * @package WPRaffle_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$s             = WPRaffle_Theme_Settings::instance()->get_settings();
$testimonials  = isset( $s['testimonial_items'] ) && is_array( $s['testimonial_items'] ) ? $s['testimonial_items'] : array();
?>
<div class="wpr-panel">
	<p class="wpr-panel-intro"><?php esc_html_e( 'Add player testimonials / reviews. They appear on the homepage testimonials carousel (if enabled on the Homepage tab).', 'wpraffle-theme' ); ?></p>

	<div class="wprt-repeatable" data-target="testimonial_items">
		<div class="wprt-repeatable-rows">
			<?php
			if ( empty( $testimonials ) ) {
				$testimonials = array( array( 'name' => '', 'content' => '', 'photo' => '' ) );
			}
			foreach ( $testimonials as $i => $t ) :
				?>
				<div class="wprt-repeatable-row">
					<p>
						<input type="text" name="wpr_settings[testimonial_items][<?php echo esc_attr( $i ); ?>][name]" value="<?php echo esc_attr( isset( $t['name'] ) ? $t['name'] : '' ); ?>" class="regular-text" placeholder="<?php esc_attr_e( 'Name (e.g. John S.)', 'wpraffle-theme' ); ?>">
					</p>
					<p>
						<textarea name="wpr_settings[testimonial_items][<?php echo esc_attr( $i ); ?>][content]" rows="3" class="large-text" placeholder="<?php esc_attr_e( 'Testimonial / review text…', 'wpraffle-theme' ); ?>"><?php echo esc_textarea( isset( $t['content'] ) ? $t['content'] : '' ); ?></textarea>
					</p>
					<p>
						<input type="url" name="wpr_settings[testimonial_items][<?php echo esc_attr( $i ); ?>][photo]" value="<?php echo esc_attr( isset( $t['photo'] ) ? $t['photo'] : '' ); ?>" class="regular-text" placeholder="<?php esc_attr_e( 'Photo URL (optional)', 'wpraffle-theme' ); ?>">
						<button type="button" class="button wprt-media-button" data-target-field="wpr_settings[testimonial_items][<?php echo esc_attr( $i ); ?>][photo]"><?php esc_html_e( 'Choose', 'wpraffle-theme' ); ?></button>
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
				<?php esc_html_e( 'Add Testimonial', 'wpraffle-theme' ); ?>
			</button>
		</p>
	</div>
</div>

<script type="text/template" class="wprt-template-testimonial_items">
	<div class="wprt-repeatable-row">
		<p><input type="text" name="" value="" class="regular-text" placeholder="<?php esc_attr_e( 'Name (e.g. John S.)', 'wpraffle-theme' ); ?>"></p>
		<p><textarea name="" rows="3" class="large-text" placeholder="<?php esc_attr_e( 'Testimonial / review text…', 'wpraffle-theme' ); ?>"></textarea></p>
		<p><input type="url" name="" value="" class="regular-text" placeholder="<?php esc_attr_e( 'Photo URL (optional)', 'wpraffle-theme' ); ?>"></p>
		<p><button type="button" class="button wprt-remove-row"><?php esc_html_e( 'Remove', 'wpraffle-theme' ); ?></button></p>
		<hr>
	</div>
</script>
