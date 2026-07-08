<?php
/**
 * Settings page — Style tab.
 *
 * @package Diamond
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$settings = WPRaffle_Theme_Settings::instance()->get_settings();
$presets  = WPRaffle_Theme_Settings::get_presets();
$current_preset = isset( $settings['preset'] ) ? $settings['preset'] : 'diamond';

$fields = array(
	'accent'   => __( 'Accent (Primary)', 'wpraffle-theme' ),
	'accent_2' => __( 'Accent 2 (Secondary)', 'wpraffle-theme' ),
	'dark'     => __( 'Dark', 'wpraffle-theme' ),
	'light'    => __( 'Light', 'wpraffle-theme' ),
	'success'  => __( 'Success', 'wpraffle-theme' ),
	'danger'   => __( 'Danger', 'wpraffle-theme' ),
	'warning'  => __( 'Warning', 'wpraffle-theme' ),
	'body'     => __( 'Body Text', 'wpraffle-theme' ),
);
?>
<div class="diamond-panel">
	<p class="diamond-panel-intro"><?php esc_html_e( 'Pick colours to re-theme the whole site. These drive both the theme and the WPRaffles plugin components. Derived shades (darker, lighter, borders) are generated automatically.', 'wpraffle-theme' ); ?></p>

	<h3><?php esc_html_e( 'Presets', 'wpraffle-theme' ); ?></h3>
	<div class="diamond-presets">
		<?php foreach ( $presets as $slug => $preset ) : ?>
			<a href="<?php echo esc_url( wp_nonce_url( admin_url( 'themes.php?page=diamond-settings&tab=style&preset=' . $slug ), 'diamond_preset', 'diamond_preset_nonce' ) ); ?>"
				class="diamond-preset <?php echo $current_preset === $slug ? 'is-active' : ''; ?>"
				data-preset="<?php echo esc_attr( $slug ); ?>">
				<span class="diamond-preset-swatches">
					<?php foreach ( array( 'accent', 'accent_2', 'dark', 'light' ) as $ck ) : ?>
						<span style="background:<?php echo esc_attr( $preset['colours'][ $ck ] ); ?>;"></span>
					<?php endforeach; ?>
				</span>
				<span class="diamond-preset-name"><?php echo esc_html( $preset['name'] ); ?></span>
			</a>
		<?php endforeach; ?>
	</div>

	<h3><?php esc_html_e( 'Custom Colours', 'wpraffle-theme' ); ?></h3>
	<table class="form-table" role="presentation">
		<tbody>
			<?php foreach ( $fields as $key => $label ) : ?>
				<tr>
					<th scope="row"><label for="diamond-colour-<?php echo esc_attr( $key ); ?>"><?php echo esc_html( $label ); ?></label></th>
					<td>
						<input type="text"
							id="diamond-colour-<?php echo esc_attr( $key ); ?>"
							name="diamond[<?php echo esc_attr( $key ); ?>]"
							value="<?php echo esc_attr( $settings[ $key ] ); ?>"
							class="diamond-color-picker"
							data-default-color="<?php echo esc_attr( WPRaffle_Theme_Settings::get_defaults()[ $key ] ); ?>" />
					</td>
				</tr>
			<?php endforeach; ?>
		</tbody>
	</table>
	<input type="hidden" name="diamond[preset]" value="custom">
</div>
