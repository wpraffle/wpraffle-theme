<?php
/**
 * WPRaffle Theme functions.
 *
 * @package WPRaffle_Theme
 * @since   1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Theme version (bumped on each release).
 */
define( 'WPRAFFLE_THEME_VERSION', '1.3.1' );
define( 'WPRAFFLE_THEME_DIR', get_template_directory() );
define( 'WPRAFFLE_THEME_URI', get_template_directory_uri() );

/**
 * Migrate settings from the old "diamond" option key to the new one.
 * Runs once on after_setup_theme; deletes the old key when done.
 *
 * Also renames every legacy `wpr_*` theme_mod key to its new `wpr_*`
 * counterpart inside the `theme_mods` option row. Gated by a flag so it only
 * runs once; old keys are removed only after the new key is written, so no
 * saved value can be lost. The companion wpraffle_theme_mod() reader falls
 * back to the old key for one release as a belt-and-braces safety net.
 */
function wpraffle_theme_migrate_settings() {
	// 1. Old option key → new option key.
	$new = get_option( 'wpraffle_theme_settings' );
	$old = get_option( 'wpr_style_settings' );
	if ( false === $new && false !== $old ) {
		update_option( 'wpraffle_theme_settings', $old );
		delete_option( 'wpr_style_settings' );
	}

	// 2. Rename wpr_* theme_mods → wpr_* (one-time, flag-gated).
	if ( ! get_option( 'wpraffle_theme_migrated_diamond_mods_v1' ) ) {
		$mods = get_option( 'theme_mods_' . get_stylesheet(), array() );
		if ( is_array( $mods ) ) {
			$changed = false;
			foreach ( $mods as $key => $value ) {
				if ( is_string( $key ) && 0 === strpos( $key, 'wpr_' ) ) {
					$new_key = 'wpr_' . substr( $key, strlen( 'wpr_' ) );
					if ( ! isset( $mods[ $new_key ] ) ) {
						$mods[ $new_key ] = $value;
					}
					unset( $mods[ $key ] );
					$changed = true;
				}
			}
			if ( $changed ) {
				update_option( 'theme_mods_' . get_stylesheet(), $mods );
			}
		}
		update_option( 'wpraffle_theme_migrated_diamond_mods_v1', 1 );
	}
}
add_action( 'after_setup_theme', 'wpraffle_theme_migrate_settings' );

/**
 * Read a theme_mod with a one-release fallback to the legacy `wpr_*` key.
 *
 * After the diamond → wpr prefix rename, any value that was saved under the
 * old key (and somehow not caught by the migrator) is still readable. Use this
 * helper instead of get_theme_mod() for keys that were renamed.
 *
 * @param string $new_key The new wpr_* theme_mod key.
 * @param mixed  $default Optional default.
 * @return mixed
 */
function wpraffle_theme_mod( $new_key, $default = false ) {
	$val = get_theme_mod( $new_key );
	if ( false !== $val ) {
		return $val;
	}
	// Fall back to the legacy wpr_* key if the new key is unset.
	$legacy = 'wpr_' . substr( $new_key, strlen( 'wpr_' ) );
	return get_theme_mod( $legacy, $default );
}

/**
 * Load the Composer-less PHP class autoloader and helper files.
 *
 * Order matters: helpers first (template tags are used by templates), then
 * setup, then integration layers.
 */
require_once WPRAFFLE_THEME_DIR . '/inc/template-tags.php';

$wpraffle_theme_classes = array(
	'/inc/class-wpraffle-theme-setup.php',
	'/inc/class-wpraffle-theme-woocommerce.php',
	'/inc/class-wpraffle-theme-integration.php',
	'/inc/class-wpraffle-theme-settings.php',
	'/inc/class-wpraffle-theme-features.php',
	'/inc/class-wpraffle-theme-elementor.php',
	'/inc/class-wpraffle-theme-tgm.php',
	'/inc/class-wpraffle-theme-updater.php',
);

foreach ( $wpraffle_theme_classes as $file ) {
	$path = WPRAFFLE_THEME_DIR . $file;
	if ( file_exists( $path ) ) {
		require_once $path;
	}
}

/**
 * Initialise the core theme classes.
 *
 * IMPORTANT: instantiate immediately (at file-load time, which is during
 * `after_setup_theme` since functions.php loads then) — NOT on a delayed hook.
 * The setup class registers `add_theme_support`, `register_nav_menus` and
 * image sizes on `after_setup_theme`; if we delayed instantiation until that
 * action fired, those registrations would hook in too late and silently no-op
 * (menus would not appear, theme supports would be missing).
 *
 * Each class gates its own features (e.g. WooCommerce integration only runs
 * when WooCommerce is active) so it is safe to instantiate them all here.
 */
WPRaffle_Theme_Setup::instance();
WPRaffle_Theme_WooCommerce::instance();
WPRaffle_Theme_Integration::instance();
WPRaffle_Theme_Settings::instance();
WPRaffle_Theme_Features::instance();
WPRaffle_Theme_Elementor::instance();
WPRaffle_Theme_TGM::instance();
new WPRaffle_Theme_Updater();

/**
 * v1.1.0: Wire named widget areas into the homepage section hooks.
 */
add_action( 'wpraffle_theme_before_homepage_sections', function() {
	if ( is_active_sidebar( 'wprt-before-home' ) ) {
		echo '<div class="wprt-hook-area wprt-hook-before-home"><div class="container">';
		dynamic_sidebar( 'wprt-before-home' );
		echo '</div></div>';
	}
} );
add_action( 'wpraffle_theme_after_section_hero', function() {
	if ( is_active_sidebar( 'wprt-after-hero' ) ) {
		echo '<div class="wprt-hook-area wprt-hook-after-hero"><div class="container">';
		dynamic_sidebar( 'wprt-after-hero' );
		echo '</div></div>';
	}
} );
add_action( 'wpraffle_theme_before_section_active', function() {
	if ( is_active_sidebar( 'wprt-before-competitions' ) ) {
		echo '<div class="wprt-hook-area wprt-hook-before-competitions"><div class="container">';
		dynamic_sidebar( 'wprt-before-competitions' );
		echo '</div></div>';
	}
} );
add_action( 'wpraffle_theme_after_homepage_sections', function() {
	if ( is_active_sidebar( 'wprt-after-home' ) ) {
		echo '<div class="wprt-hook-area wprt-hook-after-home"><div class="container">';
		dynamic_sidebar( 'wprt-after-home' );
		echo '</div></div>';
	}
} );

/**
 * Helper: is the WPRaffles plugin active?
 *
 * Raffles are WooCommerce products carrying the `_raffle_id` meta. The theme
 * is fully usable without the plugin, but several template parts light up
 * extra sections (Active Competitions, Charity Donations, winner carousels)
 * when it is present.
 *
 * @return bool
 */
function wpraffle_theme_has_plugin() {
	return class_exists( 'Raffle_Public' ) && function_exists( 'wpraffle_get_raffle' );
}

/**
 * Helper: is Pro Elements (or Elementor Pro) active?
 *
 * Pro Elements (github.com/proelements/proelements) is the GPL fork of
 * Elementor Pro and uses the same `ElementorPro\` namespace, so a single
 * namespace check covers both.
 *
 * @return bool
 */
function wpraffle_theme_has_elementor_pro() {
	return did_action( 'elementor/loaded' ) && (
		defined( 'ELEMENTOR_PRO_VERSION' ) || did_action( 'elementor_pro/init' )
	);
}
