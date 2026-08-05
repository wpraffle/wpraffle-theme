<?php
/**
 * Cookie consent / GDPR bar (v1.2.0 Enhancement K).
 *
 * A lightweight theme-level banner. The recommendation in the plan is to use a
 * dedicated consent plugin for full GDPR compliance; this is intentionally
 * simple (Accept / Reject, persistent cookie). If a known consent plugin is
 * active, this banner is suppressed to avoid double banners.
 *
 * @package WPRaffle_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$s = WPRaffle_Theme_Settings::instance()->get_settings();

if ( 'on' !== $s['cookie_consent'] ) {
	return;
}

// Suppress if a known consent plugin is already handling this.
if ( defined( 'COOKIEBOT_API_KEY' ) || defined( 'COMPLIANZ' ) || class_exists( 'Cookie_Law_Info' ) || class_exists( 'GDPR' ) ) {
	return;
}

$text = $s['cookie_consent_text'] ? $s['cookie_consent_text'] : __( 'We use cookies to improve your experience and analyse traffic. By clicking “Accept” you consent to our use of cookies.', 'wpraffle-theme' );
$link = $s['cookie_consent_link'] ? $s['cookie_consent_link'] : get_privacy_policy_url();
$days = max( 1, (int) $s['cookie_consent_duration'] );
?>
<div class="wprt-cookie-consent" id="wprt-cookie-consent" role="region" aria-label="<?php esc_attr_e( 'Cookie consent', 'wpraffle-theme' ); ?>" hidden>
	<p class="wprt-cookie-consent__text">
		<?php
		if ( $link ) {
			/* translators: %s: privacy policy link. */
			echo wp_kses_post( nl2br( $text ) ) . ' <a href="' . esc_url( $link ) . '">' . esc_html__( 'Read more', 'wpraffle-theme' ) . '</a>';
		} else {
			echo wp_kses_post( nl2br( $text ) );
		}
		?>
	</p>
	<div class="wprt-cookie-consent__actions">
		<button type="button" class="wprt-cookie-consent__btn" data-cookie-action="reject"><?php esc_html_e( 'Reject', 'wpraffle-theme' ); ?></button>
		<button type="button" class="wprt-cookie-consent__btn wprt-cookie-consent__btn--primary" data-cookie-action="accept"><?php esc_html_e( 'Accept', 'wpraffle-theme' ); ?></button>
	</div>
</div>
<script>
( function() {
	var el = document.getElementById( 'wprt-cookie-consent' );
	if ( ! el ) { return; }
	// Show after a short delay (avoids layout shift on LCP).
	var CHOICE = '<?php echo esc_js( 'wprt_cc_choice' ); ?>';
	if ( ! document.cookie.match( new RegExp( '(^|; )' + CHOICE + '=([^;]*)' ) ) ) {
		setTimeout( function() {
			el.hidden = false;
			requestAnimationFrame( function() { el.classList.add( 'is-visible' ); } );
		}, 800 );
	}
	function setChoice( v ) {
		var d = new Date();
		d.setTime( d.getTime() + ( <?php echo (int) $days; ?> * 24 * 60 * 60 * 1000 ) );
		document.cookie = CHOICE + '=' + v + '; expires=' + d.toUTCString() + '; path=/; SameSite=Lax';
		el.classList.remove( 'is-visible' );
	}
	el.addEventListener( 'click', function( e ) {
		var btn = e.target.closest( '[data-cookie-action]' );
		if ( ! btn ) { return; }
		setChoice( btn.getAttribute( 'data-cookie-action' ) );
		// Fire an event so analytics scripts can react.
		document.dispatchEvent( new CustomEvent( 'wpraffle:cookie-choice', { detail: { choice: btn.getAttribute( 'data-cookie-action' ) } } ) );
	} );
} )();
</script>
