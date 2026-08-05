<?php
/**
 * Settings page — Age Gate tab.
 *
 * @package WPRaffle_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$s = WPRaffle_Theme_Settings::instance()->get_settings();
?>
<div class="wpr-panel">
	<p class="wpr-panel-intro"><?php esc_html_e( 'Require age confirmation on first visit. Essential for UK competition compliance.', 'wpraffle-theme' ); ?></p>

	<table class="form-table" role="presentation"><tbody>
		<tr><th scope="row"><?php esc_html_e( 'Enable age gate', 'wpraffle-theme' ); ?></th>
			<td><label><input type="checkbox" name="wpr_settings[age_gate]" value="on" <?php checked( $s['age_gate'], 'on' ); ?>> <?php esc_html_e( 'Show the age verification modal', 'wpraffle-theme' ); ?></label></td></tr>
		<tr><th scope="row"><label for="wprt_age_title"><?php esc_html_e( 'Title', 'wpraffle-theme' ); ?></label></th>
			<td><input type="text" class="regular-text" id="wprt_age_title" name="wpr_settings[age_title]" value="<?php echo esc_attr( $s['age_title'] ); ?>"></td></tr>
		<tr><th scope="row"><label for="wprt_age_text"><?php esc_html_e( 'Body text', 'wpraffle-theme' ); ?></label></th>
			<td><input type="text" class="large-text" id="wprt_age_text" name="wpr_settings[age_text]" value="<?php echo esc_attr( $s['age_text'] ); ?>"></td></tr>
		<tr><th scope="row"><label for="wprt_age_btn_yes"><?php esc_html_e( 'Confirm button text', 'wpraffle-theme' ); ?></label></th>
			<td><input type="text" class="regular-text" id="wprt_age_btn_yes" name="wpr_settings[age_btn_yes]" value="<?php echo esc_attr( $s['age_btn_yes'] ); ?>"></td></tr>
		<tr><th scope="row"><label for="wprt_age_btn_no"><?php esc_html_e( 'Decline button text', 'wpraffle-theme' ); ?></label></th>
			<td><input type="text" class="regular-text" id="wprt_age_btn_no" name="wpr_settings[age_btn_no]" value="<?php echo esc_attr( $s['age_btn_no'] ); ?>"></td></tr>
		<tr><th scope="row"><label for="wprt_age_no_url"><?php esc_html_e( 'Decline redirect URL', 'wpraffle-theme' ); ?></label></th>
			<td><input type="url" class="regular-text" id="wprt_age_no_url" name="wpr_settings[age_no_url]" value="<?php echo esc_attr( $s['age_no_url'] ); ?>" placeholder="https://www.google.com"></td></tr>
		<tr><th scope="row"><label for="wprt_age_duration"><?php esc_html_e( 'Cookie duration (days)', 'wpraffle-theme' ); ?></label></th>
			<td><input type="number" min="1" max="365" step="1" id="wprt_age_duration" name="wpr_settings[age_duration]" value="<?php echo esc_attr( $s['age_duration'] ); ?>"></td></tr>
	</tbody></table>
</div>
