<?php
/**
 * Settings page — Promo Bar tab.
 *
 * @package WPRaffle_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$s = WPRaffle_Theme_Settings::instance()->get_settings();
?>
<div class="wpr-panel">
	<p class="wpr-panel-intro"><?php esc_html_e( 'A dismissible, schedulable announcement banner above the header. Great for promotions and time-limited offers.', 'wpraffle-theme' ); ?></p>

	<table class="form-table" role="presentation"><tbody>
		<tr><th scope="row"><?php esc_html_e( 'Enable promo bar', 'wpraffle-theme' ); ?></th>
			<td><label><input type="checkbox" name="wpr_settings[promo_bar]" value="on" <?php checked( $s['promo_bar'], 'on' ); ?>> <?php esc_html_e( 'Show the promo bar', 'wpraffle-theme' ); ?></label></td></tr>
		<tr><th scope="row"><label for="wprt_promo_text"><?php esc_html_e( 'Message', 'wpraffle-theme' ); ?></label></th>
			<td><input type="text" class="large-text" id="wprt_promo_text" name="wpr_settings[promo_text]" value="<?php echo esc_attr( $s['promo_text'] ); ?>" placeholder="<?php esc_attr_e( 'e.g. Black Friday: Double Tickets All Weekend!', 'wpraffle-theme' ); ?>"></td></tr>
		<tr><th scope="row"><label for="wprt_promo_url"><?php esc_html_e( 'Link URL (optional)', 'wpraffle-theme' ); ?></label></th>
			<td><input type="url" class="regular-text" id="wprt_promo_url" name="wpr_settings[promo_url]" value="<?php echo esc_attr( $s['promo_url'] ); ?>"></td></tr>
		<tr><th scope="row"><label for="wprt_promo_bg"><?php esc_html_e( 'Background colour', 'wpraffle-theme' ); ?></label></th>
			<td><input type="text" class="wpr-color-picker" id="wprt_promo_bg" name="wpr_settings[promo_bg]" value="<?php echo esc_attr( $s['promo_bg'] ); ?>" data-default-color="#e4678a"></td></tr>
		<tr><th scope="row"><label for="wprt_promo_start"><?php esc_html_e( 'Start date (optional)', 'wpraffle-theme' ); ?></label></th>
			<td><input type="date" id="wprt_promo_start" name="wpr_settings[promo_start]" value="<?php echo esc_attr( $s['promo_start'] ); ?>"></td></tr>
		<tr><th scope="row"><label for="wprt_promo_end"><?php esc_html_e( 'End date (optional)', 'wpraffle-theme' ); ?></label></th>
			<td><input type="date" id="wprt_promo_end" name="wpr_settings[promo_end]" value="<?php echo esc_attr( $s['promo_end'] ); ?>"></td></tr>
	</tbody></table>
</div>
