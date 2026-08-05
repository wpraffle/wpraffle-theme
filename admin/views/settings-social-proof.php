<?php
/**
 * Settings page — Social Proof tab.
 *
 * @package WPRaffle_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$s = WPRaffle_Theme_Settings::instance()->get_settings();
?>
<div class="wpr-panel">
	<p class="wpr-panel-intro"><?php esc_html_e( 'Show small toast notifications when someone enters a competition. Requires the WPRaffles plugin.', 'wpraffle-theme' ); ?></p>

	<table class="form-table" role="presentation"><tbody>
		<tr><th scope="row"><?php esc_html_e( 'Enable social proof', 'wpraffle-theme' ); ?></th>
			<td><label><input type="checkbox" name="wpr_settings[social_proof]" value="on" <?php checked( $s['social_proof'], 'on' ); ?>> <?php esc_html_e( 'Show toast notifications', 'wpraffle-theme' ); ?></label></td></tr>
		<tr><th scope="row"><label for="wprt_social_proof_freq"><?php esc_html_e( 'Frequency (seconds)', 'wpraffle-theme' ); ?></label></th>
			<td><input type="number" min="10" max="300" step="5" id="wprt_social_proof_freq" name="wpr_settings[social_proof_freq]" value="<?php echo esc_attr( $s['social_proof_freq'] ); ?>">
				<p class="description"><?php esc_html_e( 'How often to show a new toast (10–300 seconds).', 'wpraffle-theme' ); ?></p></td></tr>
		<tr><th scope="row"><label for="wprt_social_proof_pos"><?php esc_html_e( 'Position', 'wpraffle-theme' ); ?></label></th>
			<td><select id="wprt_social_proof_pos" name="wpr_settings[social_proof_pos]">
				<?php foreach ( array( 'bottom-left' => 'Bottom Left', 'bottom-right' => 'Bottom Right' ) as $val => $label ) : ?>
					<option value="<?php echo esc_attr( $val ); ?>" <?php selected( $s['social_proof_pos'], $val ); ?>><?php echo esc_html( $label ); ?></option>
				<?php endforeach; ?>
			</select></td></tr>
	</tbody></table>
</div>
