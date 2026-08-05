<?php
/**
 * WPRaffles plugin integration.
 *
 * Bridges the theme to the plugin via its documented public surfaces:
 *  - CSS custom properties (the --wpr-* styling variables).
 *  - Shortcodes ([raffle_list], [raffle_charities]).
 *  - Static helper methods on Raffle_Public / Raffle_Charity.
 *
 * @package WPRaffle_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class WPRaffle_Theme_Integration
 */
final class WPRaffle_Theme_Integration {

	/** @var WPRaffle_Theme_Integration|null */
	private static $instance = null;

	/**
	 * Get the singleton.
	 *
	 * @return WPRaffle_Theme_Integration
	 */
	public static function instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * Hook into the plugin when it is present.
	 */
	private function __construct() {
		// The Theme Options Style tab now owns the full --wpr-* / --wpr-*
		// palette. Suppress the plugin's own inline styling so it never competes.
		add_filter( 'pre_option_wpraffle_styling_settings', array( $this, 'disable_plugin_styling' ), 10, 1 );

		// Hide the plugin's redundant "Styling" settings tab (it's an internal
		// tab with no filter, so we hide it via CSS on that admin screen only).
		add_action( 'admin_enqueue_scripts', array( $this, 'hide_plugin_styling_tab' ) );
		// Show a notice on the styling tab body if someone reaches it via URL.
		add_action( 'admin_notices', array( $this, 'plugin_styling_tab_notice' ) );

		// Force the plugin's front-end assets (public.css/public.js) on pages
		// where the theme embeds raffle shortcodes via do_shortcode() — namely
		// the homepage. The plugin only enqueues them when the *page content*
		// contains the shortcode, but our homepage renders them from template
		// parts, so the plugin's has_shortcode() check fails and the .rc-card
		// markup ships without its stylesheet. This makes cards render
		// identically on the homepage, the shop, and the raffles page.
		add_action( 'wp_enqueue_scripts', array( $this, 'force_plugin_assets' ), 20 );

		// Expose the live charity total as a shortcode so it can be used in
		// Elementor templates (and anywhere else) instead of a hardcoded value.
		add_shortcode( 'wpraffle_charity_total', array( __CLASS__, 'render_total_shortcode' ) );
	}

	/**
	 * Shortcode wrapper around get_total_raised() — outputs the formatted,
	 * always-current charity total (e.g. "£2,800,000").
	 *
	 * @return string
	 */
	public static function render_total_shortcode() {
		return self::get_total_raised();
	}

	/**
	 * Force-enqueue the plugin's raffle-public assets on the homepage and any
	 * page that uses the theme's raffle template parts. Without this, cards
	 * rendered via do_shortcode() on the front page are unstyled because the
	 * plugin only enqueues its CSS when has_shortcode() finds the shortcode in
	 * the page's own post_content.
	 */
	public function force_plugin_assets() {
		if ( ! wpraffle_theme_has_plugin() ) {
			return;
		}
		if ( ! defined( 'RAFFLE_SYSTEM_URL' ) || ! defined( 'RAFFLE_SYSTEM_VERSION' ) ) {
			return;
		}

		// Front page renders [raffle_list] + [raffle_charities] via template parts.
		$needs = is_front_page();

		// Also cover any page/template that calls these via do_shortcode — the
		// Charities template part is used by page-charities.php too.
		if ( ! $needs && is_page() ) {
			$template = get_page_template_slug( get_queried_object_id() );
			if ( 'page-charities.php' === $template || 'page-winners.php' === $template ) {
				$needs = true;
			}
		}

		if ( $needs ) {
			wp_enqueue_style( 'wpraffle-icons', RAFFLE_SYSTEM_URL . 'assets/css/icons.css', array(), RAFFLE_SYSTEM_VERSION );
			wp_enqueue_style( 'raffle-public', RAFFLE_SYSTEM_URL . 'assets/css/public.css', array( 'wpraffle-icons' ), RAFFLE_SYSTEM_VERSION );
			wp_enqueue_script( 'raffle-public', RAFFLE_SYSTEM_URL . 'assets/js/public.js', array( 'jquery' ), RAFFLE_SYSTEM_VERSION, true );
		}
	}

	/**
	 * Hide the plugin's "Styling" settings tab via CSS.
	 *
	 * The plugin's Settings page hardcodes its tab list with no filter, so we
	 * inject CSS on that specific admin screen only to hide the "Styling" tab
	 * link (its href contains tab=styling). The theme already forces the
	 * plugin's styling output off, so the tab is redundant.
	 *
	 * @param string $hook Current admin page hook suffix.
	 */
	public function hide_plugin_styling_tab( $hook ) {
		if ( 'raffle-system_page_wpraffle-settings' !== $hook ) {
			return;
		}
		wp_add_inline_style( 'common', '.nav-tab[href*="tab=styling"]{display:none !important;}' );
	}

	/**
	 * Show a notice on the plugin's Styling tab if someone reaches it via URL.
	 */
	public function plugin_styling_tab_notice() {
		$screen = get_current_screen();
		if ( ! $screen || 'raffle-system_page_wpraffle-settings' !== $screen->id ) {
			return;
		}
		$tab = isset( $_GET['tab'] ) ? sanitize_key( wp_unslash( $_GET['tab'] ) ) : 'general'; // phpcs:ignore WordPress.Security.NonceVerification.Recommended
		if ( 'styling' !== $tab ) {
			return;
		}
		echo '<div class="notice notice-info"><p>'
			. esc_html__( 'Colour styling is managed by the WPRaffle Theme — see Appearance → Theme Options → Style.', 'wpraffle-theme' )
			. ' <a href="' . esc_url( admin_url( 'themes.php?page=wpraffle-theme-settings&tab=style' ) ) . '" class="button button-secondary">'
			. esc_html__( 'Go to Theme Options', 'wpraffle-theme' ) . '</a></p></div>';
	}

	/**
	 * Force the plugin's `disable_custom_styling` flag ON so its inline :root
	 * block is never printed. The theme (via WPRaffle_Theme_Settings::output_css_variables())
	 * is the single source of truth for the --wpr-* and --wpr-* variables.
	 *
	 * @param mixed $value The raw option value (false if unset).
	 * @return mixed
	 */
	public function disable_plugin_styling( $value ) {
		$value = is_array( $value ) ? $value : array();
		$value['disable_custom_styling'] = '1';
		return $value;
	}

	/* ---------------------------------------------------------------------
	 * Data helpers used by templates and template tags.
	 * ------------------------------------------------------------------- */

	/**
	 * Get featured winners from the plugin's featured-winners table.
	 *
	 * Uses Raffle_Featured_Winners::get_featured() which JOINs the raffles
	 * table so each row carries winner_name, winner_photo_id, raffle_title,
	 * prize_image and a testimonial. Falls back to an empty array when the
	 * plugin or the featured-winners class is absent.
	 *
	 * @param int $limit Number to fetch.
	 * @return array
	 */
	public static function get_featured_winners( $limit = 8 ) {
		if ( ! class_exists( 'Raffle_Featured_Winners' ) || ! method_exists( 'Raffle_Featured_Winners', 'get_featured' ) ) {
			return array();
		}
		$featured = Raffle_Featured_Winners::get_featured( $limit );
		return is_array( $featured ) ? $featured : array();
	}

	/**
	 * Resolve a featured-winner photo URL.
	 *
	 * @param object $fw   Featured-winner row from get_featured_winners().
	 * @param string $size Image size.
	 * @return string
	 */
	public static function get_winner_photo_url( $fw, $size = 'large' ) {
		if ( class_exists( 'Raffle_Featured_Winners' ) && method_exists( 'Raffle_Featured_Winners', 'get_photo_url' ) ) {
			return Raffle_Featured_Winners::get_photo_url( $fw, $size );
		}
		return '';
	}

	/**
	 * Total charity donations across ALL charities (formatted currency).
	 *
	 * The plugin has no built-in grand-total method, so we sum the per-charity
	 * totals via calculate_total_raised_for_charity(). Each charity row's id is
	 * the DB-row id on the raffle_charities table.
	 *
	 * @return string
	 */
	public static function get_total_raised() {
		if ( ! class_exists( 'Raffle_Charity' ) || ! method_exists( 'Raffle_Charity', 'calculate_total_raised_for_charity' ) ) {
			return function_exists( 'wpr_price' ) ? wpr_price( 0 ) : '£0.00';
		}

		$charities = Raffle_Charity::get_active_charities();
		$total     = 0.0;
		foreach ( (array) $charities as $charity ) {
			// get_active_charities() returns objects with ->id (DB row) AND ->ID (CPT post).
			$cid      = isset( $charity->id ) ? (int) $charity->id : ( isset( $charity->ID ) ? (int) $charity->ID : 0 );
			$total    += (float) Raffle_Charity::calculate_total_raised_for_charity( $cid );
		}

		return function_exists( 'wpr_price' ) ? wpr_price( $total, 0 ) : wp_strip_all_tags( wc_price( $total ) );
	}
}
