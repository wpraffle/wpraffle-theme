<?php
/**
 * Settings page — Custom Code tab.
 *
 * @package WPRaffle_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$s = WPRaffle_Theme_Settings::instance()->get_settings();
?>
<div class="wpr-panel">
	<p class="wpr-panel-intro"><?php esc_html_e( 'Add custom CSS and JS without editing theme files. These persist across theme updates.', 'wpraffle-theme' ); ?></p>

	<h3><?php esc_html_e( 'Custom CSS (front end)', 'wpraffle-theme' ); ?></h3>
	<table class="form-table" role="presentation"><tbody>
		<tr><th scope="row"><label for="wprt_custom_css"><?php esc_html_e( 'CSS', 'wpraffle-theme' ); ?></label></th>
			<td><textarea id="wprt_custom_css" name="wpr_settings[custom_css]" rows="12" class="large-text code" style="font-family:monospace;" placeholder="/* e.g. .wpr-hero { padding-top: 6rem; } */"><?php echo esc_textarea( $s['custom_css'] ); ?></textarea></td></tr>
	</tbody></table>

	<h3><?php esc_html_e( 'Custom JS (footer)', 'wpraffle-theme' ); ?></h3>
	<table class="form-table" role="presentation"><tbody>
		<tr><th scope="row"><label for="wprt_custom_js"><?php esc_html_e( 'JavaScript', 'wpraffle-theme' ); ?></label></th>
			<td><textarea id="wprt_custom_js" name="wpr_settings[custom_js]" rows="10" class="large-text code" style="font-family:monospace;" placeholder="/* e.g. console.log('hello'); */"><?php echo esc_textarea( $s['custom_js'] ); ?></textarea>
				<p class="description"><?php esc_html_e( 'Output inside <script> tags in the footer.', 'wpraffle-theme' ); ?></p></td></tr>
	</tbody></table>

	<h3><?php esc_html_e( 'Custom Admin CSS', 'wpraffle-theme' ); ?></h3>
	<table class="form-table" role="presentation"><tbody>
		<tr><th scope="row"><label for="wprt_admin_custom_css"><?php esc_html_e( 'Admin CSS', 'wpraffle-theme' ); ?></label></th>
			<td><textarea id="wprt_admin_custom_css" name="wpr_settings[admin_custom_css]" rows="6" class="large-text code" style="font-family:monospace;" placeholder="/* e.g. #adminmenu { background: #000; } */"><?php echo esc_textarea( $s['admin_custom_css'] ); ?></textarea></td></tr>
	</tbody></table>
</div>
