<?php
/**
 * Settings page — Content tab.
 *
 * @package WPRaffle_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$f = function( $key, $default = '' ) {
	return get_theme_mod( $key, $default );
};

// Build a dropdown of published pages for the page-assignment pickers.
$all_pages = get_pages( array( 'post_status' => 'publish', 'number' => 0 ) );
$page_dropdown = function( $key ) use ( $all_pages ) {
	$current = (int) get_theme_mod( $key, 0 );
	$out  = '<select name="wpr_settings[' . esc_attr( $key ) . ']" class="regular-text">';
	$out .= '<option value="0">' . esc_html__( '— Auto-detect (default) —', 'wpraffle-theme' ) . '</option>';
	foreach ( $all_pages as $p ) {
		$out .= sprintf(
			'<option value="%d"%s>%s</option>',
			esc_attr( $p->ID ),
			selected( $current, $p->ID, false ),
			esc_html( $p->post_title )
		);
	}
	$out .= '</select>';
	return $out;
};
?>
<div class="wpr-panel">
	<p class="wpr-panel-intro"><?php esc_html_e( 'Choose which pages the homepage "View all" buttons link to. Leave on "Auto-detect" to fall back to the plugin\'s page settings or a shortcode search.', 'wpraffle-theme' ); ?></p>

	<h3><?php esc_html_e( 'Page Assignments', 'wpraffle-theme' ); ?></h3>
	<table class="form-table" role="presentation"><tbody>
		<tr><th scope="row"><label for="wpr_page_competitions"><?php esc_html_e( 'Competitions page', 'wpraffle-theme' ); ?></label></th>
			<td><?php echo $page_dropdown( 'wpr_page_competitions' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				<p class="description"><?php esc_html_e( 'The "View all competitions" button links here. Defaults to the WooCommerce shop page.', 'wpraffle-theme' ); ?></p></td></tr>
		<tr><th scope="row"><label for="wpr_page_winners"><?php esc_html_e( 'Winners page', 'wpraffle-theme' ); ?></label></th>
			<td><?php echo $page_dropdown( 'wpr_page_winners' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				<p class="description"><?php esc_html_e( 'The "View all winners" button links here.', 'wpraffle-theme' ); ?></p></td></tr>
		<tr><th scope="row"><label for="wpr_page_charities"><?php esc_html_e( 'Charities page', 'wpraffle-theme' ); ?></label></th>
			<td><?php echo $page_dropdown( 'wpr_page_charities' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				<p class="description"><?php esc_html_e( 'The "View all charities" button links here.', 'wpraffle-theme' ); ?></p></td></tr>
	</tbody></table>
</div>

<?php

$textarea = array(); // reserved for future rich fields.
?>
<div class="wpr-panel">
	<p class="wpr-panel-intro"><?php esc_html_e( 'Edit the homepage hero, section headings, top bar and footer content. These mirror the Customizer fields so both stay in sync.', 'wpraffle-theme' ); ?></p>

	<h3><?php esc_html_e( 'Hero', 'wpraffle-theme' ); ?></h3>
	<table class="form-table" role="presentation"><tbody>
		<tr><th scope="row"><label for="wpr_hero_eyebrow"><?php esc_html_e( 'Eyebrow', 'wpraffle-theme' ); ?></label></th>
			<td><input type="text" class="regular-text" name="wpr_settings[wpr_hero_eyebrow]" id="wpr_hero_eyebrow" value="<?php echo esc_attr( $f( 'wpr_hero_eyebrow', 'Luxury Prize Competitions' ) ); ?>"></td></tr>
		<tr><th scope="row"><label for="wpr_hero_title"><?php esc_html_e( 'Title', 'wpraffle-theme' ); ?></label></th>
			<td><input type="text" class="regular-text" name="wpr_settings[wpr_hero_title]" id="wpr_hero_title" value="<?php echo esc_attr( $f( 'wpr_hero_title' ) ); ?>">
				<p class="description"><?php esc_html_e( 'HTML allowed. Use <span class="accent">text</span> for the accent colour highlight.', 'wpraffle-theme' ); ?></p></td></tr>
		<tr><th scope="row"><label for="wpr_hero_lead"><?php esc_html_e( 'Subtitle', 'wpraffle-theme' ); ?></label></th>
			<td><input type="text" class="regular-text" name="wpr_settings[wpr_hero_lead]" id="wpr_hero_lead" value="<?php echo esc_attr( $f( 'wpr_hero_lead' ) ); ?>"></td></tr>
		<tr><th scope="row"><label for="wpr_hero_bg"><?php esc_html_e( 'Background image URL', 'wpraffle-theme' ); ?></label></th>
			<td><input type="text" class="regular-text" name="wpr_settings[wpr_hero_bg]" id="wpr_hero_bg" value="<?php echo esc_attr( $f( 'wpr_hero_bg' ) ); ?>" placeholder="https://...">
				<button type="button" class="button wpr-media-button" data-target="wpr_hero_bg"><?php esc_html_e( 'Choose image', 'wpraffle-theme' ); ?></button></td></tr>
	</tbody></table>

	<h3><?php esc_html_e( 'Hero Stats', 'wpraffle-theme' ); ?></h3>
	<table class="form-table" role="presentation"><tbody>
		<tr><th scope="row"><label for="wpr_stat_winners"><?php esc_html_e( 'Winners count', 'wpraffle-theme' ); ?></label></th>
			<td><input type="text" class="regular-text" name="wpr_settings[wpr_stat_winners]" id="wpr_stat_winners" value="<?php echo esc_attr( $f( 'wpr_stat_winners', '12,400+' ) ); ?>"></td></tr>
		<tr><th scope="row"><label for="wpr_stat_raised"><?php esc_html_e( 'Raised for charity', 'wpraffle-theme' ); ?></label></th>
			<td><input type="text" class="regular-text" name="wpr_settings[wpr_stat_raised]" id="wpr_stat_raised" value="<?php echo esc_attr( $f( 'wpr_stat_raised', '£2.8m' ) ); ?>"></td></tr>
		<tr><th scope="row"><label for="wpr_stat_rating"><?php esc_html_e( 'Rating', 'wpraffle-theme' ); ?></label></th>
			<td><input type="text" class="regular-text" name="wpr_settings[wpr_stat_rating]" id="wpr_stat_rating" value="<?php echo esc_attr( $f( 'wpr_stat_rating', '4.9★' ) ); ?>"></td></tr>
	</tbody></table>

	<h3><?php esc_html_e( 'Top Bar', 'wpraffle-theme' ); ?></h3>
	<table class="form-table" role="presentation"><tbody>
		<tr><th scope="row"><label for="wpr_topbar_text"><?php esc_html_e( 'Message', 'wpraffle-theme' ); ?></label></th>
			<td><input type="text" class="regular-text" name="wpr_settings[wpr_topbar_text]" id="wpr_topbar_text" value="<?php echo esc_attr( $f( 'wpr_topbar_text' ) ); ?>"></td></tr>
	</tbody></table>

	<h3><?php esc_html_e( 'Section Headings', 'wpraffle-theme' ); ?></h3>
	<table class="form-table" role="presentation"><tbody>
		<tr><th scope="row"><label for="wpr_active_title"><?php esc_html_e( 'Active Competitions title', 'wpraffle-theme' ); ?></label></th>
			<td><input type="text" class="regular-text" name="wpr_settings[wpr_active_title]" id="wpr_active_title" value="<?php echo esc_attr( $f( 'wpr_active_title' ) ); ?>"></td></tr>
		<tr><th scope="row"><label for="wpr_active_subtitle"><?php esc_html_e( 'Active Competitions subtitle', 'wpraffle-theme' ); ?></label></th>
			<td><input type="text" class="regular-text" name="wpr_settings[wpr_active_subtitle]" id="wpr_active_subtitle" value="<?php echo esc_attr( $f( 'wpr_active_subtitle' ) ); ?>"></td></tr>
		<tr><th scope="row"><label for="wpr_winners_title"><?php esc_html_e( 'Winners title', 'wpraffle-theme' ); ?></label></th>
			<td><input type="text" class="regular-text" name="wpr_settings[wpr_winners_title]" id="wpr_winners_title" value="<?php echo esc_attr( $f( 'wpr_winners_title' ) ); ?>"></td></tr>
		<tr><th scope="row"><label for="wpr_winners_subtitle"><?php esc_html_e( 'Winners subtitle', 'wpraffle-theme' ); ?></label></th>
			<td><input type="text" class="regular-text" name="wpr_settings[wpr_winners_subtitle]" id="wpr_winners_subtitle" value="<?php echo esc_attr( $f( 'wpr_winners_subtitle' ) ); ?>"></td></tr>
		<tr><th scope="row"><label for="wpr_charity_title"><?php esc_html_e( 'Charity title', 'wpraffle-theme' ); ?></label></th>
			<td><input type="text" class="regular-text" name="wpr_settings[wpr_charity_title]" id="wpr_charity_title" value="<?php echo esc_attr( $f( 'wpr_charity_title' ) ); ?>"></td></tr>
		<tr><th scope="row"><label for="wpr_charity_subtitle"><?php esc_html_e( 'Charity subtitle', 'wpraffle-theme' ); ?></label></th>
			<td><input type="text" class="regular-text" name="wpr_settings[wpr_charity_subtitle]" id="wpr_charity_subtitle" value="<?php echo esc_attr( $f( 'wpr_charity_subtitle' ) ); ?>"></td></tr>
		<tr><th scope="row"><label for="wpr_trust_title"><?php esc_html_e( 'Trust block title', 'wpraffle-theme' ); ?></label></th>
			<td><input type="text" class="regular-text" name="wpr_settings[wpr_trust_title]" id="wpr_trust_title" value="<?php echo esc_attr( $f( 'wpr_trust_title' ) ); ?>"></td></tr>
		<tr><th scope="row"><label for="wpr_trust_subtitle"><?php esc_html_e( 'Trust block subtitle', 'wpraffle-theme' ); ?></label></th>
			<td><input type="text" class="regular-text" name="wpr_settings[wpr_trust_subtitle]" id="wpr_trust_subtitle" value="<?php echo esc_attr( $f( 'wpr_trust_subtitle' ) ); ?>"></td></tr>
	</tbody></table>

	<h3><?php esc_html_e( 'Footer', 'wpraffle-theme' ); ?></h3>
	<table class="form-table" role="presentation"><tbody>
		<tr><th scope="row"><label for="wpraffle_theme_footer_about"><?php esc_html_e( 'About text', 'wpraffle-theme' ); ?></label></th>
			<td><input type="text" class="regular-text" name="wpr_settings[wpraffle_theme_footer_about]" id="wpraffle_theme_footer_about" value="<?php echo esc_attr( $f( 'wpraffle_theme_footer_about' ) ); ?>"></td></tr>
	</tbody></table>

	<h3><?php esc_html_e( 'Social Links', 'wpraffle-theme' ); ?></h3>
	<table class="form-table" role="presentation"><tbody>
		<?php
		$socials = array(
			'facebook'  => 'Facebook',
			'instagram' => 'Instagram',
			'x'         => 'X (Twitter)',
			'tiktok'    => 'TikTok',
			'youtube'   => 'YouTube',
		);
		foreach ( $socials as $k => $label ) :
			$key = 'wpr_social_' . $k;
			?>
			<tr><th scope="row"><label for="<?php echo esc_attr( $key ); ?>"><?php echo esc_html( $label ); ?></label></th>
				<td><input type="url" class="regular-text" name="wpr_settings[<?php echo esc_attr( $key ); ?>]" id="<?php echo esc_attr( $key ); ?>" value="<?php echo esc_attr( $f( $key ) ); ?>" placeholder="https://"></td></tr>
		<?php endforeach; ?>
	</tbody></table>
</div>
