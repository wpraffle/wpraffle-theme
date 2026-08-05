<?php
/**
 * Responsible-play footer bar (DCMS Voluntary Code compliance).
 *
 * The Voluntary Code of Good Practice for Prize Draw Operators (in force
 * since 20 May 2026) requires operators to "signpost players to available
 * support" and to publish their player-protection measures. This slim bar
 * sits above the payment icons in the footer and surfaces:
 *   - 18+ badge
 *   - Links to GamCare / BeGambleAware (configurable)
 *   - A link to the player's responsible-gambling settings (plugin account page)
 *
 * @package WPRaffle_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$s = WPRaffle_Theme_Settings::instance()->get_settings();

// Resolve the RG account page URL when the plugin is active. The plugin's
// responsible-gambling view lives at the 'my-raffles' account endpoint with a
// ?sub=responsible-gambling query param (see class-raffle-account.php).
$rg_url = '';
if ( wpraffle_theme_has_plugin() && function_exists( 'wc_get_account_endpoint_url' ) ) {
	$my_raffles_url = wc_get_account_endpoint_url( 'my-raffles' );
	if ( $my_raffles_url ) {
		$rg_url = add_query_arg( 'sub', 'responsible-gambling', $my_raffles_url );
	}
}

// Configurable support links (defaults point to the UK's main services).
$gamcare_url  = ! empty( $s['rg_gamcare_url'] )  ? $s['rg_gamcare_url']  : 'https://www.gamcare.org.uk/';
$begamble_url = ! empty( $s['rg_begambleaware_url'] ) ? $s['rg_begambleaware_url'] : 'https://www.begambleaware.org/';
?>
<div class="wprt-responsible-play" role="region" aria-label="<?php esc_attr_e( 'Player protection information', 'wpraffle-theme' ); ?>">
	<span class="wprt-responsible-play__age">18+</span>
	<span class="wprt-responsible-play__text"><?php esc_html_e( 'Play responsibly. Gambling problem?', 'wpraffle-theme' ); ?></span>
	<a href="<?php echo esc_url( $begamble_url ); ?>" target="_blank" rel="noopener nofollow"><?php esc_html_e( 'BeGambleAware', 'wpraffle-theme' ); ?></a>
	<span class="wprt-responsible-play__sep" aria-hidden="true">·</span>
	<a href="<?php echo esc_url( $gamcare_url ); ?>" target="_blank" rel="noopener nofollow"><?php esc_html_e( 'GamCare', 'wpraffle-theme' ); ?></a>
	<?php if ( $rg_url ) : ?>
		<span class="wprt-responsible-play__sep" aria-hidden="true">·</span>
		<a href="<?php echo esc_url( $rg_url ); ?>"><?php esc_html_e( 'Your spend limits', 'wpraffle-theme' ); ?></a>
	<?php endif; ?>
</div>
