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

<?php WPRaffle_Theme_Preview::panel(); ?>

<section class="wpr-panel"><h3><?php esc_html_e( 'Card field order', 'wpraffle-theme' ); ?></h3><p><?php esc_html_e( 'Choose optional fields and arrange them with the up/down buttons. Titles, images, prices and the entry link remain available. Entry links stay last. This builder applies to WPRaffle competition cards; WooCommerce fallback cards retain their existing layout.', 'wpraffle-theme' ); ?></p><ol class="wprt-card-builder">
<?php $enabled_fields = isset( $s['card_fields'] ) ? WPRaffle_Theme_Preview::card_fields( $s['card_fields'] ) : WPRaffle_Theme_Preview::card_fields( null ); $fields = array_merge( array_diff( $enabled_fields, array( 'cta' ) ), array_diff( WPRaffle_Theme_Preview::card_fields( null ), $enabled_fields ), array( 'cta' ) ); foreach ( $fields as $field ) : ?>
<li><?php $required = in_array( $field, array( 'image','title','price','cta' ), true ); if ( $required ) : ?><input type="hidden" name="wpr_settings[card_fields][]" value="<?php echo esc_attr( $field ); ?>"><?php endif; ?><label><input type="checkbox" <?php if ( ! $required ) : ?>name="wpr_settings[card_fields][]"<?php endif; ?> value="<?php echo esc_attr( $field ); ?>" <?php checked( in_array( $field, $enabled_fields, true ) ); disabled( $required ); ?>> <?php echo esc_html( ucfirst( $field ) ); ?></label> <button type="button" class="button" data-wprt-move="up" aria-label="<?php echo esc_attr( sprintf( __( 'Move %s up', 'wpraffle-theme' ), $field ) ); ?>">↑</button> <button type="button" class="button" data-wprt-move="down" aria-label="<?php echo esc_attr( sprintf( __( 'Move %s down', 'wpraffle-theme' ), $field ) ); ?>">↓</button></li>
<?php endforeach; ?></ol></section>
