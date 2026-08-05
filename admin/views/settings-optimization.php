<?php
/**
 * Settings page — Optimization tab.
 *
 * @package WPRaffle_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$s = WPRaffle_Theme_Settings::instance()->get_settings();
?>
<div class="wpr-panel">
	<p class="wpr-panel-intro"><?php esc_html_e( 'Reduce page weight and improve Core Web Vitals by disabling assets you don\'t need.', 'wpraffle-theme' ); ?></p>

	<h3><?php esc_html_e( 'Asset Loading', 'wpraffle-theme' ); ?></h3>
	<table class="form-table" role="presentation"><tbody>
		<tr><th scope="row"><?php esc_html_e( 'Google Fonts', 'wpraffle-theme' ); ?></th>
			<td><label><input type="checkbox" name="wpr_settings[load_google_fonts]" value="on" <?php checked( $s['load_google_fonts'], 'on' ); ?>> <?php esc_html_e( 'Load the selected Google Fonts (disable to use system fonts only).', 'wpraffle-theme' ); ?></label></td></tr>
		<tr><th scope="row"><?php esc_html_e( 'Font Awesome', 'wpraffle-theme' ); ?></th>
			<td><label><input type="checkbox" name="wpr_settings[load_font_awesome]" value="on" <?php checked( $s['load_font_awesome'], 'on' ); ?>> <?php esc_html_e( 'Load Font Awesome icons (~70KB).', 'wpraffle-theme' ); ?></label></td></tr>
		<tr><th scope="row"><?php esc_html_e( 'Fancybox (lightbox)', 'wpraffle-theme' ); ?></th>
			<td><label><input type="checkbox" name="wpr_settings[load_fancybox]" value="on" <?php checked( $s['load_fancybox'], 'on' ); ?>> <?php esc_html_e( 'Load Fancybox lightbox library.', 'wpraffle-theme' ); ?></label></td></tr>
		<tr><th scope="row"><?php esc_html_e( 'Swiper (carousels)', 'wpraffle-theme' ); ?></th>
			<td><label><input type="checkbox" name="wpr_settings[load_swiper]" value="on" <?php checked( $s['load_swiper'], 'on' ); ?>> <?php esc_html_e( 'Load Swiper carousel library.', 'wpraffle-theme' ); ?></label></td></tr>
	</tbody></table>

	<h3><?php esc_html_e( 'Performance', 'wpraffle-theme' ); ?></h3>
	<table class="form-table" role="presentation"><tbody>
		<tr><th scope="row"><?php esc_html_e( 'Disable emoji script', 'wpraffle-theme' ); ?></th>
			<td><label><input type="checkbox" name="wpr_settings[disable_emoji]" value="on" <?php checked( $s['disable_emoji'], 'on' ); ?>> <?php esc_html_e( 'Removes the WordPress emoji script (saves a request).', 'wpraffle-theme' ); ?></label></td></tr>
		<tr><th scope="row"><?php esc_html_e( 'Remove version query strings', 'wpraffle-theme' ); ?></th>
			<td><label><input type="checkbox" name="wpr_settings[disable_version_qs]" value="on" <?php checked( $s['disable_version_qs'], 'on' ); ?>> <?php esc_html_e( 'Strips ?ver= from CSS/JS URLs (improves caching).', 'wpraffle-theme' ); ?></label></td></tr>
		<tr><th scope="row"><?php esc_html_e( 'Preload hero image', 'wpraffle-theme' ); ?></th>
			<td><label><input type="checkbox" name="wpr_settings[preload_hero]" value="on" <?php checked( $s['preload_hero'], 'on' ); ?>> <?php esc_html_e( 'Add a preload link for the homepage hero background image.', 'wpraffle-theme' ); ?></label></td></tr>
	</tbody></table>
</div>
