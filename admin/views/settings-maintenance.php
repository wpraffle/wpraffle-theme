<?php
/**
 * Settings page — Maintenance tab.
 *
 * @package WPRaffle_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$s = WPRaffle_Theme_Settings::instance()->get_settings();
?>
<div class="wpr-panel">
	<p class="wpr-panel-intro"><?php esc_html_e( 'Put the site behind a branded Coming Soon / Maintenance page. Logged-in admins see the real site.', 'wpraffle-theme' ); ?></p>

	<table class="form-table" role="presentation"><tbody>
		<tr><th scope="row"><?php esc_html_e( 'Enable maintenance mode', 'wpraffle-theme' ); ?></th>
			<td><label><input type="checkbox" name="wpr_settings[maintenance]" value="on" <?php checked( $s['maintenance'], 'on' ); ?>> <?php esc_html_e( 'Show the Coming Soon page to visitors', 'wpraffle-theme' ); ?></label></td></tr>
		<tr><th scope="row"><label for="wprt_maintenance_title"><?php esc_html_e( 'Heading', 'wpraffle-theme' ); ?></label></th>
			<td><input type="text" class="regular-text" id="wprt_maintenance_title" name="wpr_settings[maintenance_title]" value="<?php echo esc_attr( $s['maintenance_title'] ); ?>"></td></tr>
		<tr><th scope="row"><label for="wprt_maintenance_text"><?php esc_html_e( 'Body text', 'wpraffle-theme' ); ?></label></th>
			<td><input type="text" class="large-text" id="wprt_maintenance_text" name="wpr_settings[maintenance_text]" value="<?php echo esc_attr( $s['maintenance_text'] ); ?>"></td></tr>
		<tr><th scope="row"><label for="wprt_maintenance_bg"><?php esc_html_e( 'Background image URL', 'wpraffle-theme' ); ?></label></th>
			<td><input type="url" class="regular-text" id="wprt_maintenance_bg" name="wpr_settings[maintenance_bg]" value="<?php echo esc_attr( $s['maintenance_bg'] ); ?>" placeholder="https://...">
				<button type="button" class="button wpr-media-button" data-target="wprt_maintenance_bg"><?php esc_html_e( 'Choose', 'wpraffle-theme' ); ?></button></td></tr>
		<tr><th scope="row"><?php esc_html_e( 'Email capture', 'wpraffle-theme' ); ?></th>
			<td><label><input type="checkbox" name="wpr_settings[maintenance_email]" value="on" <?php checked( $s['maintenance_email'], 'on' ); ?>> <?php esc_html_e( 'Show an email signup field', 'wpraffle-theme' ); ?></label></td></tr>
		<tr><th scope="row"><label for="wprt_maintenance_countdown"><?php esc_html_e( 'Countdown date (optional)', 'wpraffle-theme' ); ?></label></th>
			<td><input type="datetime-local" id="wprt_maintenance_countdown" name="wpr_settings[maintenance_countdown]" value="<?php echo esc_attr( $s['maintenance_countdown'] ); ?>"></td></tr>
	</tbody></table>
</div>
