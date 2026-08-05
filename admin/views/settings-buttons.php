<?php
/**
 * Settings page — Buttons tab.
 *
 * @package WPRaffle_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$s = WPRaffle_Theme_Settings::instance()->get_settings();
?>
<div class="wpr-panel">
	<p class="wpr-panel-intro"><?php esc_html_e( 'Style the primary button. These values drive both the theme buttons and the WPRaffles plugin CTA ("Enter Raffle", "View Competition").', 'wpraffle-theme' ); ?></p>

	<table class="form-table" role="presentation"><tbody>
		<tr><th scope="row"><label for="wprt_btn_radius"><?php esc_html_e( 'Corner radius', 'wpraffle-theme' ); ?></label></th>
			<td><input type="number" min="0" max="50" step="1" id="wprt_btn_radius" name="wpr_settings[btn_radius]" value="<?php echo esc_attr( $s['btn_radius'] ); ?>"> px
				<p class="description"><?php esc_html_e( '0 = square, 50 = pill.', 'wpraffle-theme' ); ?></p></td></tr>
		<tr><th scope="row"><label for="wprt_btn_padding_x"><?php esc_html_e( 'Horizontal padding', 'wpraffle-theme' ); ?></label></th>
			<td><input type="number" min="4" max="60" step="1" id="wprt_btn_padding_x" name="wpr_settings[btn_padding_x]" value="<?php echo esc_attr( $s['btn_padding_x'] ); ?>"> px</td></tr>
		<tr><th scope="row"><label for="wprt_btn_padding_y"><?php esc_html_e( 'Vertical padding', 'wpraffle-theme' ); ?></label></th>
			<td><input type="number" min="2" max="30" step="1" id="wprt_btn_padding_y" name="wpr_settings[btn_padding_y]" value="<?php echo esc_attr( $s['btn_padding_y'] ); ?>"> px</td></tr>
		<tr><th scope="row"><label for="wprt_btn_weight"><?php esc_html_e( 'Font weight', 'wpraffle-theme' ); ?></label></th>
			<td><select id="wprt_btn_weight" name="wpr_settings[btn_weight]">
				<?php foreach ( array( '400' => 'Regular', '500' => 'Medium', '600' => 'Semibold', '700' => 'Bold', '800' => 'Extra Bold' ) as $val => $label ) : ?>
					<option value="<?php echo esc_attr( $val ); ?>" <?php selected( $s['btn_weight'], $val ); ?>><?php echo esc_html( $label ); ?></option>
				<?php endforeach; ?>
			</select></td></tr>
		<tr><th scope="row"><label for="wprt_btn_transform"><?php esc_html_e( 'Text transform', 'wpraffle-theme' ); ?></label></th>
			<td><select id="wprt_btn_transform" name="wpr_settings[btn_transform]">
				<?php foreach ( array( 'none' => 'None', 'uppercase' => 'UPPERCASE', 'capitalize' => 'Capitalize' ) as $val => $label ) : ?>
					<option value="<?php echo esc_attr( $val ); ?>" <?php selected( $s['btn_transform'], $val ); ?>><?php echo esc_html( $label ); ?></option>
				<?php endforeach; ?>
			</select></td></tr>
		<tr><th scope="row"><?php esc_html_e( 'Hover lift', 'wpraffle-theme' ); ?></th>
			<td><label><input type="checkbox" name="wpr_settings[btn_hover_lift]" value="on" <?php checked( $s['btn_hover_lift'], 'on' ); ?>> <?php esc_html_e( 'Buttons lift slightly on hover', 'wpraffle-theme' ); ?></label></td></tr>
	</tbody></table>

	<div class="wprt-button-preview" style="margin-top:1.5rem;padding:1.5rem;background:var(--wpr-light,#f6f6f6);border-radius:8px;text-align:center;">
		<span class="btn btn-accent" style="display:inline-block;background:var(--wpr-accent,#e4678a);color:#fff;border-radius:<?php echo esc_attr( $s['btn_radius'] ); ?>px;padding:<?php echo esc_attr( $s['btn_padding_y'] ); ?>px <?php echo esc_attr( $s['btn_padding_x'] ); ?>px;font-weight:<?php echo esc_attr( $s['btn_weight'] ); ?>;text-transform:<?php echo esc_attr( $s['btn_transform'] ); ?>;">Preview Button</span>
	</div>
</div>
