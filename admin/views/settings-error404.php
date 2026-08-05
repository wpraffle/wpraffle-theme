<?php
/**
 * Settings page — 404 Page tab.
 *
 * @package WPRaffle_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$s = WPRaffle_Theme_Settings::instance()->get_settings();
?>
<div class="wpr-panel">
	<p class="wpr-panel-intro"><?php esc_html_e( 'Customise the 404 (page not found) page.', 'wpraffle-theme' ); ?></p>

	<table class="form-table" role="presentation"><tbody>
		<tr><th scope="row"><label for="wprt_error_heading"><?php esc_html_e( 'Heading', 'wpraffle-theme' ); ?></label></th>
			<td><input type="text" class="large-text" id="wprt_error_heading" name="wpr_settings[error_heading]" value="<?php echo esc_attr( $s['error_heading'] ); ?>"></td></tr>
		<tr><th scope="row"><label for="wprt_error_text"><?php esc_html_e( 'Body text', 'wpraffle-theme' ); ?></label></th>
			<td><input type="text" class="large-text" id="wprt_error_text" name="wpr_settings[error_text]" value="<?php echo esc_attr( $s['error_text'] ); ?>"></td></tr>
		<tr><th scope="row"><?php esc_html_e( 'Show search form', 'wpraffle-theme' ); ?></th>
			<td><label><input type="checkbox" name="wpr_settings[error_show_search]" value="on" <?php checked( $s['error_show_search'], 'on' ); ?>></label></td></tr>
		<tr><th scope="row"><?php esc_html_e( 'Show "Browse competitions" button', 'wpraffle-theme' ); ?></th>
			<td><label><input type="checkbox" name="wpr_settings[error_show_comps]" value="on" <?php checked( $s['error_show_comps'], 'on' ); ?>></label></td></tr>
		<tr><th scope="row"><label for="wprt_error_bg"><?php esc_html_e( 'Background image URL (optional)', 'wpraffle-theme' ); ?></label></th>
			<td><input type="url" class="regular-text" id="wprt_error_bg" name="wpr_settings[error_bg]" value="<?php echo esc_attr( $s['error_bg'] ); ?>" placeholder="https://...">
				<button type="button" class="button wpr-media-button" data-target="wprt_error_bg"><?php esc_html_e( 'Choose', 'wpraffle-theme' ); ?></button></td></tr>
	</tbody></table>
</div>
