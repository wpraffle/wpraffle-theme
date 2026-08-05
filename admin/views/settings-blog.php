<?php
/**
 * Settings page — Blog tab.
 *
 * @package WPRaffle_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$s = WPRaffle_Theme_Settings::instance()->get_settings();
?>
<div class="wpr-panel">
	<p class="wpr-panel-intro"><?php esc_html_e( 'Control how blog posts and archives are displayed.', 'wpraffle-theme' ); ?></p>

	<table class="form-table" role="presentation"><tbody>
		<tr><th scope="row"><label for="wprt_blog_layout"><?php esc_html_e( 'Layout', 'wpraffle-theme' ); ?></label></th>
			<td><select id="wprt_blog_layout" name="wpr_settings[blog_layout]">
				<?php foreach ( array(
					'grid'     => __( 'Grid', 'wpraffle-theme' ),
					'list'     => __( 'List', 'wpraffle-theme' ),
					'masonry'  => __( 'Masonry', 'wpraffle-theme' ),
				) as $val => $label ) : ?>
					<option value="<?php echo esc_attr( $val ); ?>" <?php selected( $s['blog_layout'], $val ); ?>><?php echo esc_html( $label ); ?></option>
				<?php endforeach; ?>
			</select></td></tr>
		<tr><th scope="row"><label for="wprt_blog_columns"><?php esc_html_e( 'Columns (grid/masonry)', 'wpraffle-theme' ); ?></label></th>
			<td><input type="number" min="1" max="4" step="1" id="wprt_blog_columns" name="wpr_settings[blog_columns]" value="<?php echo esc_attr( $s['blog_columns'] ); ?>"></td></tr>
		<tr><th scope="row"><label for="wprt_excerpt_length"><?php esc_html_e( 'Excerpt length', 'wpraffle-theme' ); ?></label></th>
			<td><input type="number" min="5" max="100" step="1" id="wprt_excerpt_length" name="wpr_settings[excerpt_length]" value="<?php echo esc_attr( $s['excerpt_length'] ); ?>"> words</td></tr>
		<tr><th scope="row"><?php esc_html_e( 'Show author', 'wpraffle-theme' ); ?></th>
			<td><label><input type="checkbox" name="wpr_settings[show_author]" value="on" <?php checked( $s['show_author'], 'on' ); ?>></label></td></tr>
		<tr><th scope="row"><?php esc_html_e( 'Show date', 'wpraffle-theme' ); ?></th>
			<td><label><input type="checkbox" name="wpr_settings[show_date]" value="on" <?php checked( $s['show_date'], 'on' ); ?>></label></td></tr>
		<tr><th scope="row"><?php esc_html_e( 'Show category', 'wpraffle-theme' ); ?></th>
			<td><label><input type="checkbox" name="wpr_settings[show_category]" value="on" <?php checked( $s['show_category'], 'on' ); ?>></label></td></tr>
	</tbody></table>
</div>
