<?php
/**
 * TGM Plugin Activation wiring.
 *
 * - Requires WooCommerce.
 * - Recommends Elementor (free) and Pro Elements (the GPL fork of Elementor Pro).
 *
 * Pro Elements is bundled at /lib/proelements/proelements.zip if present, or
 * installed from the GitHub release URL otherwise. The TGMPA library itself is
 * loaded from /lib/tgmpa/tgm-plugin-activation.php.
 *
 * @package Diamond
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class WPRaffle_Theme_TGM
 */
final class WPRaffle_Theme_TGM {

	/** @var WPRaffle_Theme_TGM|null */
	private static $instance = null;

	/**
	 * Get the singleton.
	 *
	 * @return WPRaffle_Theme_TGM
	 */
	public static function instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * Load TGMPA and register the notice.
	 */
	private function __construct() {
		$tgmpa = WPRAFFLE_THEME_DIR . '/lib/tgmpa/tgm-plugin-activation.php';
		if ( file_exists( $tgmpa ) ) {
			require_once $tgmpa;
		} else {
			return; // TGMPA not bundled — skip silently.
		}

		add_action( 'tgmpa_register', array( $this, 'register_required_plugins' ) );
	}

	/**
	 * Register the plugins.
	 */
	public function register_required_plugins() {
		/*
		 * Pro Elements source: prefer a locally bundled zip, fall back to the
		 * GitHub releases feed.
		 */
		$bundled_proelements = WPRAFFLE_THEME_DIR . '/lib/proelements/proelements.zip';
		if ( file_exists( $bundled_proelements ) ) {
			$proelements_source = get_stylesheet_directory_uri() . '/lib/proelements/proelements.zip';
		} else {
			$proelements_source = 'https://github.com/proelements/proelements/releases/latest/download/proelements.zip';
		}

		$plugins = array(
			array(
				'name'     => 'WooCommerce',
				'slug'     => 'woocommerce',
				'required' => true,
			),
			array(
				'name'         => 'WPRaffles',
				'slug'         => 'wpraffle',
				'source'       => 'bundled', // Shipped with the site or installed manually.
				'required'     => false,
				'external_url' => 'https://wpraffles.dev/',
			),
			array(
				'name'         => 'Elementor',
				'slug'         => 'elementor',
				'required'     => false,
			),
			array(
				'name'         => 'PRO Elements',
				'slug'         => 'pro-elements',
				'source'       => $proelements_source,
				'required'     => false,
				'external_url' => 'https://github.com/proelements/proelements',
			),
		);

		$config = array(
			'id'           => 'diamond',
			'default_path' => '',
			'menu'         => 'diamond-install-plugins',
			'has_notices'  => true,
			'dismissable'  => true,
			'dismiss_msg'  => '',
			'is_automatic' => false,
			'message'      => '',
		);

		tgmpa( $plugins, $config );
	}
}
