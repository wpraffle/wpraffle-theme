<?php
/**
 * Settings page — Typography tab.
 *
 * @package WPRaffle_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$s = WPRaffle_Theme_Settings::instance()->get_settings();

// Curated Google Fonts list (popular subset — keeps the dropdown fast).
$google_fonts = array(
	'Montserrat' => 'Montserrat', 'Inter' => 'Inter', 'Poppins' => 'Poppins',
	'Roboto' => 'Roboto', 'Open Sans' => 'Open Sans', 'Lato' => 'Lato',
	'Raleway' => 'Raleway', 'Oswald' => 'Oswald', 'Nunito' => 'Nunito',
	'Mukta' => 'Mukta', 'Work Sans' => 'Work Sans', 'DM Sans' => 'DM Sans',
	'Barlow' => 'Barlow', 'Karla' => 'Karla', 'Rubik' => 'Rubik',
	'Archivo' => 'Archivo', 'Manrope' => 'Manrope', 'Sora' => 'Sora',
	'Space Grotesk' => 'Space Grotesk', 'Outfit' => 'Outfit',
	'system-ui' => 'System UI (no load)', 'Georgia' => 'Georgia (no load)', 'Arial' => 'Arial (no load)',
);
?>
<div class="wpr-panel">
	<p class="wpr-panel-intro"><?php esc_html_e( 'Choose the fonts and type scale for the whole site. Google Fonts are loaded dynamically — picks marked "(no load)" use system fonts.', 'wpraffle-theme' ); ?></p>

	<h3><?php esc_html_e( 'Font Families', 'wpraffle-theme' ); ?></h3>
	<table class="form-table" role="presentation"><tbody>
		<tr><th scope="row"><label for="wprt_heading_font"><?php esc_html_e( 'Headings', 'wpraffle-theme' ); ?></label></th>
			<td><select id="wprt_heading_font" name="wpr_settings[heading_font]">
				<?php foreach ( $google_fonts as $val => $label ) : ?>
					<option value="<?php echo esc_attr( $val ); ?>" <?php selected( $s['heading_font'], $val ); ?>><?php echo esc_html( $label ); ?></option>
				<?php endforeach; ?>
			</select></td></tr>
		<tr><th scope="row"><label for="wprt_body_font"><?php esc_html_e( 'Body', 'wpraffle-theme' ); ?></label></th>
			<td><select id="wprt_body_font" name="wpr_settings[body_font]">
				<?php foreach ( $google_fonts as $val => $label ) : ?>
					<option value="<?php echo esc_attr( $val ); ?>" <?php selected( $s['body_font'], $val ); ?>><?php echo esc_html( $label ); ?></option>
				<?php endforeach; ?>
			</select></td></tr>
		<tr><th scope="row"><label for="wprt_heading_weight"><?php esc_html_e( 'Heading weight', 'wpraffle-theme' ); ?></label></th>
			<td><select id="wprt_heading_weight" name="wpr_settings[heading_weight]">
				<?php foreach ( array( '300' => 'Light (300)', '400' => 'Regular (400)', '500' => 'Medium (500)', '600' => 'Semibold (600)', '700' => 'Bold (700)', '800' => 'Extra Bold (800)' ) as $val => $label ) : ?>
					<option value="<?php echo esc_attr( $val ); ?>" <?php selected( $s['heading_weight'], $val ); ?>><?php echo esc_html( $label ); ?></option>
				<?php endforeach; ?>
			</select></td></tr>
		<tr><th scope="row"><label for="wprt_body_weight"><?php esc_html_e( 'Body weight', 'wpraffle-theme' ); ?></label></th>
			<td><select id="wprt_body_weight" name="wpr_settings[body_weight]">
				<?php foreach ( array( '300' => 'Light (300)', '400' => 'Regular (400)', '500' => 'Medium (500)', '600' => 'Semibold (600)' ) as $val => $label ) : ?>
					<option value="<?php echo esc_attr( $val ); ?>" <?php selected( $s['body_weight'], $val ); ?>><?php echo esc_html( $label ); ?></option>
				<?php endforeach; ?>
			</select></td></tr>
	</tbody></table>

	<h3><?php esc_html_e( 'Type Scale (px)', 'wpraffle-theme' ); ?></h3>
	<table class="form-table" role="presentation"><tbody>
		<?php
		$sizes = array(
			'body_size' => __( 'Body', 'wpraffle-theme' ),
			'h6_size'   => __( 'Heading 6', 'wpraffle-theme' ),
			'h5_size'   => __( 'Heading 5', 'wpraffle-theme' ),
			'h4_size'   => __( 'Heading 4', 'wpraffle-theme' ),
			'h3_size'   => __( 'Heading 3', 'wpraffle-theme' ),
			'h2_size'   => __( 'Heading 2', 'wpraffle-theme' ),
			'h1_size'   => __( 'Heading 1', 'wpraffle-theme' ),
		);
		foreach ( $sizes as $key => $label ) :
			?>
			<tr><th scope="row"><label for="wprt_<?php echo esc_attr( $key ); ?>"><?php echo esc_html( $label ); ?></label></th>
				<td><input type="number" min="10" max="120" step="1" id="wprt_<?php echo esc_attr( $key ); ?>" name="wpr_settings[<?php echo esc_attr( $key ); ?>]" value="<?php echo esc_attr( $s[ $key ] ); ?>"> px</td></tr>
		<?php endforeach; ?>
		<tr><th scope="row"><label for="wprt_letter_spacing"><?php esc_html_e( 'Letter spacing', 'wpraffle-theme' ); ?></label></th>
			<td><input type="number" min="-5" max="20" step="0.1" id="wprt_letter_spacing" name="wpr_settings[letter_spacing]" value="<?php echo esc_attr( $s['letter_spacing'] ); ?>"> px (applies to headings)</td></tr>
		<tr><th scope="row"><label for="wprt_line_height"><?php esc_html_e( 'Body line height', 'wpraffle-theme' ); ?></label></th>
			<td><input type="number" min="1" max="3" step="0.1" id="wprt_line_height" name="wpr_settings[line_height]" value="<?php echo esc_attr( $s['line_height'] ); ?>"></td></tr>
	</tbody></table>
</div>
