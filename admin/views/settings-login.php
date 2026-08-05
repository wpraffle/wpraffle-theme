<?php
/**
 * Settings page — Login Page tab.
 *
 * @package WPRaffle_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$s = WPRaffle_Theme_Settings::instance()->get_settings();
?>
<div class="wpr-panel">
	<p class="wpr-panel-intro"><?php esc_html_e( 'Style the WordPress login page (wp-login.php) to match your theme.', 'wpraffle-theme' ); ?></p>

	<table class="form-table" role="presentation"><tbody>
		<tr><th scope="row"><label for="wprt_login_bg"><?php esc_html_e( 'Background image URL', 'wpraffle-theme' ); ?></label></th>
			<td><input type="url" class="regular-text" id="wprt_login_bg" name="wpr_settings[login_bg]" value="<?php echo esc_attr( $s['login_bg'] ); ?>" placeholder="https://...">
				<button type="button" class="button wpr-media-button" data-target="wprt_login_bg"><?php esc_html_e( 'Choose', 'wpraffle-theme' ); ?></button></td></tr>
		<tr><th scope="row"><label for="wprt_login_logo"><?php esc_html_e( 'Logo image URL', 'wpraffle-theme' ); ?></label></th>
			<td><input type="url" class="regular-text" id="wprt_login_logo" name="wpr_settings[login_logo]" value="<?php echo esc_attr( $s['login_logo'] ); ?>" placeholder="https://...">
				<button type="button" class="button wpr-media-button" data-target="wprt_login_logo"><?php esc_html_e( 'Choose', 'wpraffle-theme' ); ?></button>
				<p class="description"><?php esc_html_e( 'Replaces the default WordPress logo on the login page.', 'wpraffle-theme' ); ?></p></td></tr>
		<tr><th scope="row"><label for="wprt_login_custom_css"><?php esc_html_e( 'Custom CSS', 'wpraffle-theme' ); ?></label></th>
			<td><textarea id="wprt_login_custom_css" name="wpr_settings[login_custom_css]" rows="8" class="large-text code" style="font-family:monospace;" placeholder="/* e.g. #login h1 a { width: 300px; } */"><?php echo esc_textarea( $s['login_custom_css'] ); ?></textarea></td></tr>
	</tbody></table>
</div>
