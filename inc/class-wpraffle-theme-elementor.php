<?php
/**
 * Elementor / Pro Elements integration.
 *
 * Registers Elementor Theme Builder locations so the JSON templates in
 * /elementor/theme-builder/ snap into place once imported. Works with both
 * Elementor Pro and the GPL Pro Elements fork (github.com/proelements/proelements)
 * — both expose the same `elementor/theme/register_locations` hook.
 *
 * @package Diamond
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
	 * Elementor canvas styles. Smooths the seam between theme base styles and
	 * Elementor's own stylesheet.
	 */
	public function enqueue_elementor_styles() {
		wp_add_inline_style( 'wpraffle-theme-base', '.elementor-section.elementor-section-boxed > .elementor-container{max-width:1280px;}' );
	}
}
