<?php
/**
 * Settings page — Enhancements (v1.2.0) tab.
 *
 * All the fact-checked competitive enhancements grouped in one place.
 * Each toggle has a matching default + save handler in
 * WPRaffle_Theme_Settings::save_enhancements_tab().
 *
 * @package WPRaffle_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$s = WPRaffle_Theme_Settings::instance()->get_settings();

/** Render a checkbox row bound to a settings key. */
$checkbox = function ( $key, $label ) use ( $s ) {
	printf(
		'<tr><th scope="row">%s</th><td><label><input type="checkbox" name="wpr_settings[%1$s]" value="on" %2$s> %3$s</label></td></tr>',
		esc_attr( $key ),
		checked( $s[ $key ], 'on', false ),
		esc_html( $label )
	);
};
?>
<div class="wpr-panel">
	<p class="wpr-panel-intro"><?php esc_html_e( 'v1.2.0 competitive enhancements. All animations respect the visitor’s reduced-motion preference.', 'wpraffle-theme' ); ?></p>

	<h3><?php esc_html_e( 'Animations & UX', 'wpraffle-theme' ); ?></h3>
	<table class="form-table" role="presentation"><tbody>
		<?php
		$checkbox( 'scroll_reveal', __( 'Scroll-triggered fade-in on homepage sections.', 'wpraffle-theme' ) );
		$checkbox( 'hero_counters', __( 'Animate hero stat numbers from 0 on scroll.', 'wpraffle-theme' ) );
		$checkbox( 'progress_animate', __( 'Animate competition-card progress bars on scroll-in.', 'wpraffle-theme' ) );
		$checkbox( 'back_to_top', __( 'Show a floating “back to top” button.', 'wpraffle-theme' ) );
		$checkbox( 'confetti_winners', __( 'Confetti burst on the winners page.', 'wpraffle-theme' ) );
		?>
	</tbody></table>

	<h3><?php esc_html_e( 'Trustpilot Integration', 'wpraffle-theme' ); ?></h3>
	<p class="description"><?php esc_html_e( 'Genuine integration only — the theme never fakes Trustpilot styling on manual testimonials.', 'wpraffle-theme' ); ?></p>
	<table class="form-table" role="presentation"><tbody>
		<tr><th scope="row"><label for="wprt_tp_business_id"><?php esc_html_e( 'Trustpilot Business Unit ID', 'wpraffle-theme' ); ?></label></th>
			<td><input type="text" class="regular-text" id="wprt_tp_business_id" name="wpr_settings[trustpilot_business_id]" value="<?php echo esc_attr( $s['trustpilot_business_id'] ); ?>" placeholder="e.g. 5f69c4d6d6f31e0001f7e3b1">
			<p class="description"><?php esc_html_e( 'Found in your Trustpilot business account underWidgets → Get TrustBox. Leave blank to disable.', 'wpraffle-theme' ); ?></p></td></tr>
		<tr><th scope="row"><label for="wprt_tp_position"><?php esc_html_e( 'TrustBox widget position', 'wpraffle-theme' ); ?></label></th>
			<td><select id="wprt_tp_position" name="wpr_settings[trustpilot_position]">
				<?php foreach ( array( 'off' => 'Off', 'hero' => 'In hero', 'footer' => 'In footer', 'both' => 'Hero + footer' ) as $val => $label ) : ?>
					<option value="<?php echo esc_attr( $val ); ?>" <?php selected( $s['trustpilot_position'], $val ); ?>><?php echo esc_html( $label ); ?></option>
				<?php endforeach; ?>
			</select></td></tr>
		<?php $checkbox( 'testimonials_trustpilot', __( 'Pull real Trustpilot reviews into the testimonials carousel (requires Business Unit ID above).', 'wpraffle-theme' ) ); ?>
	</tbody></table>

	<h3><?php esc_html_e( 'Cookie Consent / GDPR', 'wpraffle-theme' ); ?></h3>
	<p class="description"><?php esc_html_e( 'A simple theme-level banner. If you already run a consent plugin, leave this off to avoid double banners.', 'wpraffle-theme' ); ?></p>
	<table class="form-table" role="presentation"><tbody>
		<?php $checkbox( 'cookie_consent', __( 'Show a cookie consent banner.', 'wpraffle-theme' ) ); ?>
		<tr><th scope="row"><label for="wprt_cc_text"><?php esc_html_e( 'Banner text', 'wpraffle-theme' ); ?></label></th>
			<td><textarea id="wprt_cc_text" name="wpr_settings[cookie_consent_text]" rows="2" class="large-text"><?php echo esc_textarea( $s['cookie_consent_text'] ? $s['cookie_consent_text'] : __( 'We use cookies to improve your experience and analyse traffic. By clicking “Accept” you consent to our use of cookies.', 'wpraffle-theme' ) ); ?></textarea></td></tr>
		<tr><th scope="row"><label for="wprt_cc_link"><?php esc_html_e( 'Privacy policy link', 'wpraffle-theme' ); ?></label></th>
			<td><input type="url" class="regular-text" id="wprt_cc_link" name="wpr_settings[cookie_consent_link]" value="<?php echo esc_attr( $s['cookie_consent_link'] ); ?>"></td></tr>
		<tr><th scope="row"><label for="wprt_cc_duration"><?php esc_html_e( 'Remember for (days)', 'wpraffle-theme' ); ?></label></th>
			<td><input type="number" min="1" max="365" id="wprt_cc_duration" name="wpr_settings[cookie_consent_duration]" value="<?php echo esc_attr( $s['cookie_consent_duration'] ); ?>" class="small-text"></td></tr>
	</tbody></table>

	<h3><?php esc_html_e( 'Hero', 'wpraffle-theme' ); ?></h3>
	<table class="form-table" role="presentation"><tbody>
		<tr><th scope="row"><label for="wprt_hero_video"><?php esc_html_e( 'Hero background video URL', 'wpraffle-theme' ); ?></label></th>
			<td><input type="url" class="regular-text" id="wprt_hero_video" name="wpr_settings[hero_video]" value="<?php echo esc_attr( $s['hero_video'] ); ?>" placeholder="https://example.com/hero.mp4">
			<p class="description"><?php esc_html_e( 'Optional. Falls back to the hero image on mobile for performance. A pause control is shown automatically.', 'wpraffle-theme' ); ?></p></td></tr>
	</tbody></table>

	<h3><?php esc_html_e( 'How It Works section', 'wpraffle-theme' ); ?></h3>
	<p class="description"><?php esc_html_e( 'Enable the section under the Homepage tab. Leave fields blank to use sensible defaults.', 'wpraffle-theme' ); ?></p>
	<table class="form-table" role="presentation"><tbody>
		<tr><th scope="row"><label for="wprt_hiw_title"><?php esc_html_e( 'Section title', 'wpraffle-theme' ); ?></label></th>
			<td><input type="text" class="regular-text" id="wprt_hiw_title" name="wpr_settings[hiw_title]" value="<?php echo esc_attr( $s['hiw_title'] ); ?>"></td></tr>
		<tr><th scope="row"><label for="wprt_hiw_subtitle"><?php esc_html_e( 'Section subtitle', 'wpraffle-theme' ); ?></label></th>
			<td><input type="text" class="regular-text" id="wprt_hiw_subtitle" name="wpr_settings[hiw_subtitle]" value="<?php echo esc_attr( $s['hiw_subtitle'] ); ?>"></td></tr>
		<?php for ( $i = 1; $i <= 4; $i++ ) : ?>
			<tr><th scope="row"><?php echo esc_html( sprintf( __( 'Step %d', 'wpraffle-theme' ), $i ) ); ?></th>
				<td style="display:flex;flex-direction:column;gap:6px;">
					<input type="text" class="regular-text" name="wpr_settings[hiw_step<?php echo esc_attr( $i ); ?>_icon]" value="<?php echo esc_attr( $s[ 'hiw_step' . $i . '_icon' ] ); ?>" placeholder="<?php esc_attr_e( 'Font Awesome icon (e.g. fa-trophy)', 'wpraffle-theme' ); ?>">
					<input type="text" class="regular-text" name="wpr_settings[hiw_step<?php echo esc_attr( $i ); ?>_title]" value="<?php echo esc_attr( $s[ 'hiw_step' . $i . '_title' ] ); ?>" placeholder="<?php esc_attr_e( 'Title', 'wpraffle-theme' ); ?>">
					<input type="text" class="regular-text" name="wpr_settings[hiw_step<?php echo esc_attr( $i ); ?>_text]" value="<?php echo esc_attr( $s[ 'hiw_step' . $i . '_text' ] ); ?>" placeholder="<?php esc_attr_e( 'Short description', 'wpraffle-theme' ); ?>">
				</td></tr>
		<?php endfor; ?>
	</tbody></table>

	<h3><?php esc_html_e( 'Featured Spotlight', 'wpraffle-theme' ); ?></h3>
	<p class="description"><?php esc_html_e( 'Promote a single competition. Picks by ID (approach 1) — or, on WPRaffle plugin v1.3.1+, flag the competition as featured in the plugin and it sorts to the top.', 'wpraffle-theme' ); ?></p>
	<table class="form-table" role="presentation"><tbody>
		<tr><th scope="row"><label for="wprt_featured_id"><?php esc_html_e( 'Featured raffle ID', 'wpraffle-theme' ); ?></label></th>
			<td><input type="number" min="0" id="wprt_featured_id" name="wpr_settings[featured_raffle_id]" value="<?php echo esc_attr( $s['featured_raffle_id'] ); ?>" class="small-text">
			<p class="description"><?php esc_html_e( 'The raffle ID shown in the spotlight. 0 = auto-pick the first featured-flagged raffle (plugin 1.3.1+).', 'wpraffle-theme' ); ?></p></td></tr>
		<tr><th scope="row"><label for="wprt_featured_title"><?php esc_html_e( 'Custom heading', 'wpraffle-theme' ); ?></label></th>
			<td><input type="text" class="regular-text" id="wprt_featured_title" name="wpr_settings[featured_title]" value="<?php echo esc_attr( $s['featured_title'] ); ?>" placeholder="<?php esc_attr_e( 'Featured Competition', 'wpraffle-theme' ); ?>"></td></tr>
		<tr><th scope="row"><label for="wprt_featured_badge"><?php esc_html_e( 'Badge text', 'wpraffle-theme' ); ?></label></th>
			<td><input type="text" class="regular-text" id="wprt_featured_badge" name="wpr_settings[featured_badge]" value="<?php echo esc_attr( $s['featured_badge'] ); ?>" placeholder="<?php esc_attr_e( 'Featured', 'wpraffle-theme' ); ?>"></td></tr>
	</tbody></table>

	<h3><?php esc_html_e( 'Social & Chat', 'wpraffle-theme' ); ?></h3>
	<table class="form-table" role="presentation"><tbody>
		<tr><th scope="row"><label for="wprt_ig_url"><?php esc_html_e( 'Instagram feed URL', 'wpraffle-theme' ); ?></label></th>
			<td><input type="url" class="regular-text" id="wprt_ig_url" name="wpr_settings[instagram_feed_url]" value="<?php echo esc_attr( $s['instagram_feed_url'] ); ?>" placeholder="https://www.instagram.com/yourhandle/">
			<p class="description"><?php esc_html_e( 'Completes the existing footer Instagram setting. Enable the footer toggle on the Footer tab too.', 'wpraffle-theme' ); ?></p></td></tr>
		<tr><th scope="row"><label for="wprt_chat_provider"><?php esc_html_e( 'Floating chat provider', 'wpraffle-theme' ); ?></label></th>
			<td><select id="wprt_chat_provider" name="wpr_settings[chat_provider]">
				<?php foreach ( array( 'off' => 'Off', 'whatsapp' => 'WhatsApp', 'tawk' => 'Tawk.to', 'crisp' => 'Crisp' ) as $val => $label ) : ?>
					<option value="<?php echo esc_attr( $val ); ?>" <?php selected( $s['chat_provider'], $val ); ?>><?php echo esc_html( $label ); ?></option>
				<?php endforeach; ?>
			</select></td></tr>
		<tr><th scope="row"><label for="wprt_chat_number"><?php esc_html_e( 'WhatsApp number', 'wpraffle-theme' ); ?></label></th>
			<td><input type="text" class="regular-text" id="wprt_chat_number" name="wpr_settings[chat_number]" value="<?php echo esc_attr( $s['chat_number'] ); ?>" placeholder="447700900123 (full international, no +)"></td></tr>
		<tr><th scope="row"><label for="wprt_chat_id"><?php esc_html_e( 'Tawk / Crisp ID', 'wpraffle-theme' ); ?></label></th>
			<td><input type="text" class="regular-text" id="wprt_chat_id" name="wpr_settings[chat_id]" value="<?php echo esc_attr( $s['chat_id'] ); ?>" placeholder="<?php esc_attr_e( 'Provider widget ID', 'wpraffle-theme' ); ?>"></td></tr>
	</tbody></table>

	<h3><?php esc_html_e( 'Compliance (DCMS Voluntary Code)', 'wpraffle-theme' ); ?></h3>
	<p class="description"><?php esc_html_e( 'Settings aligned to the UK Voluntary Code of Good Practice for Prize Draw Operators (in force since 20 May 2026).', 'wpraffle-theme' ); ?></p>
	<table class="form-table" role="presentation"><tbody>
		<?php $checkbox( 'responsible_play', __( 'Show the 18+ responsible-play bar in the footer (GamCare / BeGambleAware + spend-limits link).', 'wpraffle-theme' ) ); ?>
		<tr><th scope="row"><label for="wprt_rg_gamcare"><?php esc_html_e( 'GamCare URL', 'wpraffle-theme' ); ?></label></th>
			<td><input type="url" class="regular-text" id="wprt_rg_gamcare" name="wpr_settings[rg_gamcare_url]" value="<?php echo esc_attr( $s['rg_gamcare_url'] ); ?>" placeholder="https://www.gamcare.org.uk/"></td></tr>
		<tr><th scope="row"><label for="wprt_rg_bega"><?php esc_html_e( 'BeGambleAware URL', 'wpraffle-theme' ); ?></label></th>
			<td><input type="url" class="regular-text" id="wprt_rg_bega" name="wpr_settings[rg_begambleaware_url]" value="<?php echo esc_attr( $s['rg_begambleaware_url'] ); ?>" placeholder="https://www.begambleaware.org/"></td></tr>
		<?php $checkbox( 'draw_details', __( 'Show the draw-details / odds-of-winning disclosure on each competition page.', 'wpraffle-theme' ) ); ?>
		<tr><th scope="row"><label for="wprt_draw_mech"><?php esc_html_e( 'Draw mechanism text', 'wpraffle-theme' ); ?></label></th>
			<td><input type="text" class="regular-text" id="wprt_draw_mech" name="wpr_settings[draw_mechanism]" value="<?php echo esc_attr( $s['draw_mechanism'] ); ?>" placeholder="<?php esc_attr_e( 'e.g. Computer-randomised draw, independently verified', 'wpraffle-theme' ); ?>">
			<p class="description"><?php esc_html_e( 'Overrides the default draw-method label. Leave blank to auto-detect from the raffle settings.', 'wpraffle-theme' ); ?></p></td></tr>
		<tr><th scope="row"><label for="wprt_terms"><?php esc_html_e( 'Terms & conditions URL', 'wpraffle-theme' ); ?></label></th>
			<td><input type="url" class="regular-text" id="wprt_terms" name="wpr_settings[terms_url]" value="<?php echo esc_attr( $s['terms_url'] ); ?>" placeholder="<?php esc_attr_e( 'https://yoursite.com/terms/', 'wpraffle-theme' ); ?>"></td></tr>
	</tbody></table>
</div>
