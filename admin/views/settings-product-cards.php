<?php
/**
 * Settings page — Product Cards tab.
 *
 * @package WPRaffle_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$s = WPRaffle_Theme_Settings::instance()->get_settings();
?>
<div class="wpr-panel">
	<p class="wpr-panel-intro"><?php esc_html_e( 'Control how raffle competition cards look across the site (homepage, shop, raffles page).', 'wpraffle-theme' ); ?></p>

	<table class="form-table" role="presentation"><tbody>
		<tr><th scope="row"><label for="wprt_card_ratio"><?php esc_html_e( 'Image aspect ratio', 'wpraffle-theme' ); ?></label></th>
			<td><select id="wprt_card_ratio" name="wpr_settings[card_ratio]">
				<?php foreach ( array( '4-3' => '4:3 (Default)', '16-9' => '16:9 (Widescreen)', 'square' => 'Square (1:1)' ) as $val => $label ) : ?>
					<option value="<?php echo esc_attr( $val ); ?>" <?php selected( $s['card_ratio'], $val ); ?>><?php echo esc_html( $label ); ?></option>
				<?php endforeach; ?>
			</select></td></tr>
		<tr><th scope="row"><label for="wprt_card_title_pos"><?php esc_html_e( 'Title position', 'wpraffle-theme' ); ?></label></th>
			<td><select id="wprt_card_title_pos" name="wpr_settings[card_title_pos]">
				<?php foreach ( array( 'below' => 'Below image (Default)', 'overlay' => 'Overlaid on image' ) as $val => $label ) : ?>
					<option value="<?php echo esc_attr( $val ); ?>" <?php selected( $s['card_title_pos'], $val ); ?>><?php echo esc_html( $label ); ?></option>
				<?php endforeach; ?>
			</select></td></tr>
		<tr><th scope="row"><label for="wprt_card_progress"><?php esc_html_e( 'Progress bar style', 'wpraffle-theme' ); ?></label></th>
			<td><select id="wprt_card_progress" name="wpr_settings[card_progress]">
				<?php foreach ( array( 'thick' => 'Thick (8px)', 'thin' => 'Thin (4px)', 'hidden' => 'Hidden' ) as $val => $label ) : ?>
					<option value="<?php echo esc_attr( $val ); ?>" <?php selected( $s['card_progress'], $val ); ?>><?php echo esc_html( $label ); ?></option>
				<?php endforeach; ?>
			</select></td></tr>
		<tr><th scope="row"><label for="wprt_card_hover"><?php esc_html_e( 'Hover effect', 'wpraffle-theme' ); ?></label></th>
			<td><select id="wprt_card_hover" name="wpr_settings[card_hover]">
				<?php foreach ( array( 'lift' => 'Lift up (Default)', 'zoom' => 'Zoom image', 'border' => 'Border highlight' ) as $val => $label ) : ?>
					<option value="<?php echo esc_attr( $val ); ?>" <?php selected( $s['card_hover'], $val ); ?>><?php echo esc_html( $label ); ?></option>
				<?php endforeach; ?>
			</select></td></tr>
	</tbody></table>
</div>
