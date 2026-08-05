<?php
/**
 * Settings page — Footer tab.
 *
 * @package WPRaffle_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$s = WPRaffle_Theme_Settings::instance()->get_settings();
?>
<div class="wpr-panel">
	<p class="wpr-panel-intro"><?php esc_html_e( 'Configure the footer layout, CTA strip, and optional newsletter signup.', 'wpraffle-theme' ); ?></p>

	<h3><?php esc_html_e( 'Layout', 'wpraffle-theme' ); ?></h3>
	<table class="form-table" role="presentation"><tbody>
		<tr><th scope="row"><label for="wprt_footer_columns"><?php esc_html_e( 'Columns', 'wpraffle-theme' ); ?></label></th>
			<td><select id="wprt_footer_columns" name="wpr_settings[footer_columns]">
				<?php foreach ( array( '2' => '2 Columns', '3' => '3 Columns', '4' => '4 Columns' ) as $val => $label ) : ?>
					<option value="<?php echo esc_attr( $val ); ?>" <?php selected( $s['footer_columns'], $val ); ?>><?php echo esc_html( $label ); ?></option>
				<?php endforeach; ?>
			</select></td></tr>
		<tr><th scope="row"><?php esc_html_e( 'Newsletter signup', 'wpraffle-theme' ); ?></th>
			<td><label><input type="checkbox" name="wpr_settings[footer_newsletter]" value="on" <?php checked( $s['footer_newsletter'], 'on' ); ?>> <?php esc_html_e( 'Show email signup form in footer', 'wpraffle-theme' ); ?></label></td></tr>
		<tr><th scope="row"><?php esc_html_e( 'Instagram feed', 'wpraffle-theme' ); ?></th>
			<td><label><input type="checkbox" name="wpr_settings[footer_instagram]" value="on" <?php checked( $s['footer_instagram'], 'on' ); ?>> <?php esc_html_e( 'Show an Instagram feed grid in the footer', 'wpraffle-theme' ); ?></label>
			<p class="description"><?php esc_html_e( 'Set the feed URL under Enhancements. Renders placeholder tiles until an Instagram API/widget is connected.', 'wpraffle-theme' ); ?></p></td></tr>
	</tbody></table>

	<h3><?php esc_html_e( 'CTA Strip', 'wpraffle-theme' ); ?></h3>
	<table class="form-table" role="presentation"><tbody>
		<tr><th scope="row"><?php esc_html_e( 'Enable CTA strip', 'wpraffle-theme' ); ?></th>
			<td><label><input type="checkbox" name="wpr_settings[footer_cta]" value="on" <?php checked( $s['footer_cta'], 'on' ); ?>> <?php esc_html_e( 'Show a call-to-action strip above the footer columns', 'wpraffle-theme' ); ?></label></td></tr>
		<tr><th scope="row"><label for="wprt_footer_cta_title"><?php esc_html_e( 'CTA heading', 'wpraffle-theme' ); ?></label></th>
			<td><input type="text" class="regular-text" id="wprt_footer_cta_title" name="wpr_settings[footer_cta_title]" value="<?php echo esc_attr( $s['footer_cta_title'] ); ?>"></td></tr>
		<tr><th scope="row"><label for="wprt_footer_cta_text"><?php esc_html_e( 'CTA text', 'wpraffle-theme' ); ?></label></th>
			<td><input type="text" class="regular-text" id="wprt_footer_cta_text" name="wpr_settings[footer_cta_text]" value="<?php echo esc_attr( $s['footer_cta_text'] ); ?>"></td></tr>
		<tr><th scope="row"><label for="wprt_footer_cta_btn"><?php esc_html_e( 'CTA button text', 'wpraffle-theme' ); ?></label></th>
			<td><input type="text" class="regular-text" id="wprt_footer_cta_btn" name="wpr_settings[footer_cta_btn]" value="<?php echo esc_attr( $s['footer_cta_btn'] ); ?>"></td></tr>
		<tr><th scope="row"><label for="wprt_footer_cta_url"><?php esc_html_e( 'CTA button URL', 'wpraffle-theme' ); ?></label></th>
			<td><input type="url" class="regular-text" id="wprt_footer_cta_url" name="wpr_settings[footer_cta_url]" value="<?php echo esc_attr( $s['footer_cta_url'] ); ?>"></td></tr>
	</tbody></table>
</div>
