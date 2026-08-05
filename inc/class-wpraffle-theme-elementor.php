<?php
/**
 * Elementor / Pro Elements integration.
 *
 * Registers Elementor Theme Builder locations so the JSON templates in
 * /elementor/theme-builder/ snap into place once imported. Works with both
 * Elementor Pro and the GPL Pro Elements fork (github.com/proelements/proelements)
 * — both expose the same `elementor/theme/register_locations` hook.
 *
 * @package WPRaffle_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class WPRaffle_Theme_Elementor
 */
final class WPRaffle_Theme_Elementor {

	/** @var WPRaffle_Theme_Elementor|null */
	private static $instance = null;

	/**
	 * Get the singleton.
	 *
	 * @return WPRaffle_Theme_Elementor
	 */
	public static function instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * Hook in only when Elementor (free) is loaded.
	 */
	private function __construct() {
		if ( ! did_action( 'elementor/loaded' ) ) {
			return;
		}

		add_action( 'elementor/theme/register_locations', array( $this, 'register_locations' ) );
		add_action( 'elementor/dynamic_tags/register', array( $this, 'register_dynamic_tags' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_elementor_styles' ), 100 );
	}

	/**
	 * Register the Theme Builder locations the theme ships templates for.
	 *
	 * @param \ElementorPro\Modules\ThemeBuilder\Classes\Locations_Manager $manager Location manager.
	 */
	public function register_locations( $manager ) {
		$manager->register_location(
			'header',
			array(
				'label'           => __( 'Header', 'wpraffle-theme' ),
				'multiple'        => false,
				'edit_in_content' => false,
				'hook'            => 'wpraffle_theme_header',
				'remove_hooks'    => array( 'wpraffle_theme_header_markup' ),
			)
		);
		$manager->register_location(
			'footer',
			array(
				'label'           => __( 'Footer', 'wpraffle-theme' ),
				'multiple'        => false,
				'edit_in_content' => false,
				'hook'            => 'wpraffle_theme_footer',
				'remove_hooks'    => array( 'wpraffle_theme_footer_markup' ),
			)
		);
		$manager->register_core_location( 'single' );
		$manager->register_core_location( 'archive' );
	}

	/**
	 * Enqueue the dedicated Elementor override stylesheet (centralises the
	 * canvas max-width + responsive stacking + section padding for the wpr-*
	 * classes used inside Elementor templates). Loaded after the theme's main
	 * cascade so it wins without !important, on both the frontend and the
	 * editor canvas.
	 */
	public function enqueue_elementor_styles() {
		$ver = defined( 'WPRAFFLE_THEME_VERSION' ) ? WPRAFFLE_THEME_VERSION : '1.3.0';
		wp_enqueue_style(
			'wpraffle-theme-elementor',
			WPRAFFLE_THEME_URI . '/assets/css/elementor.css',
			array( 'wpraffle-theme-base' ),
			$ver
		);
	}

	/**
	 * Register the theme's dynamic tags (raffle id / price / draw date / charity
	 * total) under a `wpraffle-theme` tag group, so any native Elementor widget
	 * can bind to live values instead of hardcoding them in JSON templates.
	 *
	 * @param \Elementor\Core\DynamicTags\Manager $dynamic_tags
	 */
	public function register_dynamic_tags( $dynamic_tags ) {
		require_once WPRAFFLE_THEME_DIR . '/inc/class-wpraffle-theme-elementor-tags.php';

		$groups = \Elementor\Plugin::$instance->dynamic_tags->get_config( 'groups' );
		if ( ! is_array( $groups ) || ! isset( $groups['wpraffle-theme'] ) ) {
			\Elementor\Plugin::$instance->dynamic_tags->register_group( 'wpraffle-theme', array(
				'title' => '🎁 ' . __( 'WPRaffle Theme', 'wpraffle-theme' ),
			) );
		}

		$tags = array(
			'WPRaffle_Theme_Tag_Raffle_Id',
			'WPRaffle_Theme_Tag_Ticket_Price',
			'WPRaffle_Theme_Tag_Draw_Date',
			'WPRaffle_Theme_Tag_Charity_Total',
		);
		foreach ( $tags as $class ) {
			if ( class_exists( $class ) ) {
				$dynamic_tags->register( new $class() );
			}
		}
	}
}
