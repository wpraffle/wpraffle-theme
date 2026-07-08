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
define( 'WPRAFFLE_THEME_VERSION', '1.0.0' );
define( 'WPRAFFLE_THEME_DIR', get_template_directory() );
define( 'WPRAFFLE_THEME_URI', get_template_directory_uri() );

/**
 * Migrate settings from the old "diamond" option key to the new one.
 * Runs once on after_setup_theme; deletes the old key when done.
 */
function wpraffle_theme_migrate_settings() {
	$new = get_option( 'wpraffle_theme_settings' );
	$old = get_option( 'diamond_style_settings' );
	if ( false === $new && false !== $old ) {
		update_option( 'wpraffle_theme_settings', $old );
		delete_option( 'diamond_style_settings' );
	}
}
add_action( 'after_setup_theme', 'wpraffle_theme_migrate_settings' );

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
WPRaffle_Theme_Elementor::instance();
WPRaffle_Theme_TGM::instance();
new WPRaffle_Theme_Updater();

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
