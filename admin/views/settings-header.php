<?php
/**
 * Settings page — Header tab.
 *
 * @package WPRaffle_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$s = WPRaffle_Theme_Settings::instance()->get_settings();
?>
<div class="wpr-panel">
	<p class="wpr-panel-intro"><?php esc_html_e( 'Choose the header layout, scroll behaviour, and extra header features.', 'wpraffle-theme' ); ?></p>

	<h3><?php esc_html_e( 'Header Layout', 'wpraffle-theme' ); ?></h3>
	<table class="form-table" role="presentation"><tbody>
		<tr><th scope="row"><label for="wprt_header_layout"><?php esc_html_e( 'Layout', 'wpraffle-theme' ); ?></label></th>
			<td><select id="wprt_header_layout" name="wpr_settings[header_layout]">
				<?php foreach ( array(
					'default'  => __( 'Default (logo left, nav centre, actions right)', 'wpraffle-theme' ),
					'centered' => __( 'Centered logo (nav split)', 'wpraffle-theme' ),
					'minimal'  => __( 'Minimal (logo left, icon menu right)', 'wpraffle-theme' ),
					'split'    => __( 'Split (logo centre, nav both sides)', 'wpraffle-theme' ),
				) as $val => $label ) : ?>
					<option value="<?php echo esc_attr( $val ); ?>" <?php selected( $s['header_layout'], $val ); ?>><?php echo esc_html( $label ); ?></option>
				<?php endforeach; ?>
			</select></td></tr>
		<tr><th scope="row"><label for="wprt_header_scroll"><?php esc_html_e( 'Scroll behaviour', 'wpraffle-theme' ); ?></label></th>
			<td><select id="wprt_header_scroll" name="wpr_settings[header_scroll]">
				<?php foreach ( array(
					'none'    => __( 'Static (no effect)', 'wpraffle-theme' ),
					'shrink'  => __( 'Shrink on scroll', 'wpraffle-theme' ),
					'hide'    => __( 'Hide on scroll-down, show on scroll-up', 'wpraffle-theme' ),
					'shadow'  => __( 'Add shadow when scrolled', 'wpraffle-theme' ),
				) as $val => $label ) : ?>
					<option value="<?php echo esc_attr( $val ); ?>" <?php selected( $s['header_scroll'], $val ); ?>><?php echo esc_html( $label ); ?></option>
				<?php endforeach; ?>
			</select></td></tr>
		<tr><th scope="row"><?php esc_html_e( 'Transparent over hero', 'wpraffle-theme' ); ?></th>
			<td><label><input type="checkbox" name="wpr_settings[header_overlay]" value="on" <?php checked( $s['header_overlay'], 'on' ); ?>> <?php esc_html_e( 'Make the header transparent until scrolled (homepage hero overlay)', 'wpraffle-theme' ); ?></label></td></tr>
	</tbody></table>

	<h3><?php esc_html_e( 'Header Features', 'wpraffle-theme' ); ?></h3>
	<table class="form-table" role="presentation"><tbody>
		<tr><th scope="row"><label for="wprt_dark_mode"><?php esc_html_e( 'Dark mode', 'wpraffle-theme' ); ?></label></th>
			<td><select id="wprt_dark_mode" name="wpr_settings[dark_mode]">
				<?php foreach ( array(
					'off'    => __( 'Disabled', 'wpraffle-theme' ),
					'auto'   => __( 'Auto (follow OS preference)', 'wpraffle-theme' ),
					'manual' => __( 'Manual (toggle in header)', 'wpraffle-theme' ),
				) as $val => $label ) : ?>
					<option value="<?php echo esc_attr( $val ); ?>" <?php selected( $s['dark_mode'], $val ); ?>><?php echo esc_html( $label ); ?></option>
				<?php endforeach; ?>
			</select>
				<p class="description"><?php esc_html_e( 'Adds a dark/light toggle to the header.', 'wpraffle-theme' ); ?></p></td></tr>
		<tr><th scope="row"><?php esc_html_e( 'Mega menu', 'wpraffle-theme' ); ?></th>
			<td><label><input type="checkbox" name="wpr_settings[mega_menu]" value="on" <?php checked( $s['mega_menu'], 'on' ); ?>> <?php esc_html_e( 'Enable multi-column dropdown menus for the primary navigation', 'wpraffle-theme' ); ?></label></td></tr>
		<tr><th scope="row"><?php esc_html_e( 'Sticky mobile CTA bar', 'wpraffle-theme' ); ?></th>
			<td><label><input type="checkbox" name="wpr_settings[mobile_cta]" value="on" <?php checked( $s['mobile_cta'], 'on' ); ?>> <?php esc_html_e( 'Show a fixed Enter button on mobile', 'wpraffle-theme' ); ?></label>
				<p class="description"><?php esc_html_e( 'Appears as a bottom bar on phones/tablets only.', 'wpraffle-theme' ); ?></p></td></tr>
		<tr id="wprt-mobile-cta-fields"><th scope="row"><label for="wprt_mobile_cta_text"><?php esc_html_e( 'Mobile CTA text', 'wpraffle-theme' ); ?></label></th>
			<td><input type="text" class="regular-text" id="wprt_mobile_cta_text" name="wpr_settings[mobile_cta_text]" value="<?php echo esc_attr( $s['mobile_cta_text'] ); ?>"></td></tr>
		<tr><th scope="row"><label for="wprt_mobile_cta_url"><?php esc_html_e( 'Mobile CTA URL', 'wpraffle-theme' ); ?></label></th>
			<td><input type="url" class="regular-text" id="wprt_mobile_cta_url" name="wpr_settings[mobile_cta_url]" value="<?php echo esc_attr( $s['mobile_cta_url'] ); ?>" placeholder="<?php esc_attr_e( 'Defaults to competitions page', 'wpraffle-theme' ); ?>"></td></tr>
	</tbody></table>
</div>
