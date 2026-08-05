<?php
/**
 * Core theme setup: supports, menus, image sizes, enqueues.
 *
 * @package WPRaffle_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class WPRaffle_Theme_Setup
 */
final class WPRaffle_Theme_Setup {

	/** @var WPRaffle_Theme_Setup|null */
	private static $instance = null;

	/**
	 * Get the singleton.
	 *
	 * @return WPRaffle_Theme_Setup
	 */
	public static function instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * Hook everything in.
	 *
	 * `setup()` (theme supports, nav menus, image sizes) must run during
	 * `after_setup_theme`. Since this class is instantiated at file-load time
	 * (which itself happens during `after_setup_theme`), we register on that
	 * action at priority 11 — just after the default — so the menus and theme
	 * supports are reliably picked up.
	 */
	private function __construct() {
		add_action( 'after_setup_theme', array( $this, 'setup' ), 11 );
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_styles' ), 5 );
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
		add_action( 'widgets_init', array( $this, 'register_sidebars' ) );
		add_filter( 'body_class', array( $this, 'body_classes' ) );

		// v1.1.0 optimization features.
		add_action( 'init', array( $this, 'disable_emoji' ), 1 );
		add_filter( 'script_loader_src', array( $this, 'remove_version_qs' ), 9999 );
		add_filter( 'style_loader_src', array( $this, 'remove_version_qs' ), 9999 );
		add_action( 'wp_head', array( $this, 'preload_hero' ), 1 );
	}

	/**
	 * Theme supports, menus, image sizes.
	 */
	public function setup() {
		// Make theme available for translation.
		load_theme_textdomain( 'wpraffle-theme', WPRAFFLE_THEME_DIR . '/languages' );

		add_theme_support( 'automatic-feed-links' );
		add_theme_support( 'title-tag' );
		add_theme_support( 'post-thumbnails' );
		add_theme_support( 'custom-logo', array(
			'height'      => 60,
			'width'       => 220,
			'flex-height' => true,
			'flex-width'  => true,
		) );
		add_theme_support( 'customize-selective-refresh-widgets' );
		add_theme_support( 'html5', array(
			'search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script',
		) );
		add_theme_support( 'responsive-embeds' );
		add_theme_support( 'align-wide' );
		add_theme_support( 'editor-styles' );
		add_theme_support( 'wp-block-styles' );

		// WooCommerce.
		add_theme_support( 'woocommerce' );
		add_theme_support( 'wc-product-gallery-zoom' );
		add_theme_support( 'wc-product-gallery-lightbox' );
		add_theme_support( 'wc-product-gallery-slider' );

		// Menus.
		register_nav_menus( array(
			'primary'   => __( 'Primary Menu', 'wpraffle-theme' ),
			'top_bar'   => __( 'Top Bar Menu', 'wpraffle-theme' ),
			'footer'    => __( 'Footer Menu', 'wpraffle-theme' ),
			'mobile'    => __( 'Mobile Menu', 'wpraffle-theme' ),
			'account'   => __( 'Account Menu', 'wpraffle-theme' ),
		) );

		// Image sizes sized to the competition-card pattern.
		add_image_size( 'wpr-card', 600, 450, true );
		add_image_size( 'wpr-card-wide', 800, 500, true );
		add_image_size( 'wpr-hero', 1920, 800, true );
		add_image_size( 'wpr-winner', 360, 360, true );

		add_editor_style( 'assets/css/base.css' );
	}

	/**
	 * Styles: vendor stack, then theme, then plugin overrides.
	 *
	 * Load order matters. base.css must come before the plugin's public.css so
	 * the plugin can be overridden by wpraffle.css afterwards.
	 */
	public function enqueue_styles() {
		$ver = WPRAFFLE_THEME_VERSION;
		$s   = WPRaffle_Theme_Settings::instance()->get_settings();

		// Vendor: Bootstrap 5.3 grid/utilities, Swiper 11, Fancybox 6, Font Awesome 6.
		// Each is conditional on the Optimization tab toggles (v1.1.0).
		wp_enqueue_style( 'bootstrap', 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css', array(), '5.3.3' );
		if ( 'on' === $s['load_swiper'] ) {
			wp_enqueue_style( 'swiper', 'https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css', array(), '11.1.14' );
		}
		if ( 'on' === $s['load_fancybox'] ) {
			wp_enqueue_style( 'fancybox', 'https://cdn.jsdelivr.net/npm/@fancyapps/ui@6/dist/fancybox/fancybox.css', array(), '6.0' );
		}
		if ( 'on' === $s['load_font_awesome'] ) {
			wp_enqueue_style( 'font-awesome', 'https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.5.2/css/all.min.css', array(), '6.5.2' );
		}

		// Theme base styles.
		wp_enqueue_style( 'wpraffle-theme-base', WPRAFFLE_THEME_URI . '/assets/css/base.css', array( 'bootstrap' ), $ver );
		wp_enqueue_style( 'wpraffle-theme-components', WPRAFFLE_THEME_URI . '/assets/css/components.css', array( 'wpraffle-theme-base' ), $ver );
		wp_enqueue_style( 'wpraffle-theme-woocommerce', WPRAFFLE_THEME_URI . '/assets/css/woocommerce.css', array( 'wpraffle-theme-components' ), $ver );
		// v1.1.0 features (typography, header layouts, sections, blog).
		wp_enqueue_style( 'wpraffle-theme-v110', WPRAFFLE_THEME_URI . '/assets/css/v1.1.0.css', array( 'wpraffle-theme-components' ), $ver );
		// v1.1.0 15 features (dark mode, promo, mobile CTA, social proof, age gate, footer, product cards, mega menu).
		wp_enqueue_style( 'wpraffle-theme-features', WPRAFFLE_THEME_URI . '/assets/css/v1.1.0-features.css', array( 'wpraffle-theme-v110' ), $ver );
		// v1.2.0 enhancements (scroll reveal, counters, back-to-top, new sections, etc.).
		wp_enqueue_style( 'wpraffle-theme-v120', WPRAFFLE_THEME_URI . '/assets/css/v1.2.0.css', array( 'wpraffle-theme-features' ), $ver );

		// WPRaffle plugin overrides — declared as dependent on the plugin's
		// 'raffle-public' stylesheet so this always loads AFTER it (equal
		// specificity then wins by source order). If the plugin isn't active
		// the handle simply isn't registered and WP falls back to the others.
		wp_enqueue_style( 'wpraffle-theme-integration', WPRAFFLE_THEME_URI . '/assets/css/wpraffle.css', array( 'wpraffle-theme-components', 'raffle-public' ), $ver );

		// The parent style.css (kept light for backwards-compat discovery).
		wp_enqueue_style( 'wpraffle-theme-style', get_stylesheet_uri(), array( 'wpraffle-theme-base' ), $ver );
	}

	/**
	 * Scripts: vendor + theme.
	 */
	public function enqueue_scripts() {
		$ver = WPRAFFLE_THEME_VERSION;
		$s   = WPRaffle_Theme_Settings::instance()->get_settings();

		wp_enqueue_script( 'bootstrap', 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js', array( 'jquery' ), '5.3.3', true );
		if ( 'on' === $s['load_swiper'] ) {
			wp_enqueue_script( 'swiper', 'https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js', array(), '11.1.14', true );
		}
		if ( 'on' === $s['load_fancybox'] ) {
			wp_enqueue_script( 'fancybox', 'https://cdn.jsdelivr.net/npm/@fancyapps/ui@6/dist/fancybox/fancybox.umd.js', array(), '6.0', true );
		}

		$deps = array( 'jquery', 'bootstrap' );
		if ( 'on' === $s['load_swiper'] ) {
			$deps[] = 'swiper';
		}
		wp_enqueue_script( 'wpraffle-theme-script', WPRAFFLE_THEME_URI . '/assets/js/wpraffle-theme.js', $deps, $ver, true );
		wp_enqueue_script( 'wpraffle-theme-features', WPRAFFLE_THEME_URI . '/assets/js/v1.1.0-features.js', array( 'wpraffle-theme-script' ), $ver, true );
		// v1.2.0 enhancements — depends on the features script so it can extend its helpers.
		wp_enqueue_script( 'wpraffle-theme-v120', WPRAFFLE_THEME_URI . '/assets/js/v1.2.0.js', array( 'wpraffle-theme-features' ), $ver, true );

		wp_localize_script( 'wpraffle-theme-script', 'wprThemeData', array(
			'ajaxUrl'         => admin_url( 'admin-ajax.php' ),
			'nonce'           => wp_create_nonce( 'wpr_nonce' ),
			'isRaffle'        => wpraffle_theme_has_plugin(),
			'headerScroll'    => $s['header_scroll'],
			'headerLayout'    => $s['header_layout'],
			'darkMode'        => $s['dark_mode'],
			'socialProofFreq' => isset( $s['social_proof_freq'] ) ? intval( $s['social_proof_freq'] ) : 30,
			'socialProofPos'  => isset( $s['social_proof_pos'] ) ? $s['social_proof_pos'] : 'bottom-left',
			// v1.2.0 feature toggles.
			'scrollReveal'    => isset( $s['scroll_reveal'] ) ? $s['scroll_reveal'] : 'on',
			'backToTop'       => isset( $s['back_to_top'] ) ? $s['back_to_top'] : 'on',
			'confettiWinners' => isset( $s['confetti_winners'] ) ? $s['confetti_winners'] : 'on',
			'progressAnimate' => isset( $s['progress_animate'] ) ? $s['progress_animate'] : 'on',
			'heroCounters'    => isset( $s['hero_counters'] ) ? $s['hero_counters'] : 'on',
		) );

		if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
			wp_enqueue_script( 'comment-reply' );
		}
	}

	/**
	 * Sidebars.
	 */
	public function register_sidebars() {
		register_sidebar( array(
			'name'          => __( 'Blog Sidebar', 'wpraffle-theme' ),
			'id'            => 'sidebar-1',
			'description'   => __( 'Widgets shown alongside blog posts and archives.', 'wpraffle-theme' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		) );

		register_sidebar( array(
			'name'          => __( 'Footer Column 1', 'wpraffle-theme' ),
			'id'            => 'footer-1',
			'before_widget' => '<div id="%1$s" class="footer-widget %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<h3 class="footer-widget-title">',
			'after_title'   => '</h3>',
		) );
		register_sidebar( array(
			'name'          => __( 'Footer Column 2', 'wpraffle-theme' ),
			'id'            => 'footer-2',
			'before_widget' => '<div id="%1$s" class="footer-widget %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<h3 class="footer-widget-title">',
			'after_title'   => '</h3>',
		) );
		register_sidebar( array(
			'name'          => __( 'Footer Column 3', 'wpraffle-theme' ),
			'id'            => 'footer-3',
			'before_widget' => '<div id="%1$s" class="footer-widget %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<h3 class="footer-widget-title">',
			'after_title'   => '</h3>',
		) );

		// v1.1.0: Named hook widget areas for no-code page composition.
		register_sidebar( array(
			'name'          => __( 'Before Homepage Sections', 'wpraffle-theme' ),
			'id'            => 'wprt-before-home',
			'description'   => __( 'Appears above the first homepage section (great for promo banners).', 'wpraffle-theme' ),
			'before_widget' => '<div id="%1$s" class="wprt-hook-widget %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<h3 class="wprt-hook-widget-title">',
			'after_title'   => '</h3>',
		) );
		register_sidebar( array(
			'name'          => __( 'After Hero', 'wpraffle-theme' ),
			'id'            => 'wprt-after-hero',
			'description'   => __( 'Appears directly after the homepage hero.', 'wpraffle-theme' ),
			'before_widget' => '<div id="%1$s" class="wprt-hook-widget %2$s">',
			'after_widget'  => '</div>',
		) );
		register_sidebar( array(
			'name'          => __( 'Before Competitions', 'wpraffle-theme' ),
			'id'            => 'wprt-before-competitions',
			'description'   => __( 'Appears above the Active Competitions section.', 'wpraffle-theme' ),
			'before_widget' => '<div id="%1$s" class="wprt-hook-widget %2$s">',
			'after_widget'  => '</div>',
		) );
		register_sidebar( array(
			'name'          => __( 'After Homepage Sections', 'wpraffle-theme' ),
			'id'            => 'wprt-after-home',
			'description'   => __( 'Appears after the last homepage section (above the footer).', 'wpraffle-theme' ),
			'before_widget' => '<div id="%1$s" class="wprt-hook-widget %2$s">',
			'after_widget'  => '</div>',
		) );
	}

	/**
	 * Body classes.
	 *
	 * @param array $classes Current body classes.
	 * @return array
	 */
	public function body_classes( $classes ) {
		$classes[] = 'wpr-theme';
		$s = WPRaffle_Theme_Settings::instance()->get_settings();

		if ( wpraffle_theme_has_plugin() ) {
			$classes[] = 'has-wpraffle';
		}
		if ( wpraffle_theme_has_elementor_pro() ) {
			$classes[] = 'has-elementor-pro';
		}
		if ( is_front_page() ) {
			$classes[] = 'wpr-front';
		}

		// v1.1.0 layout classes.
		$classes[] = 'header-layout-' . sanitize_html_class( $s['header_layout'] );
		$classes[] = 'header-scroll-' . sanitize_html_class( $s['header_scroll'] );
		if ( 'on' === $s['header_overlay'] && is_front_page() ) {
			$classes[] = 'header-overlay';
		}
		if ( is_home() || is_archive() ) {
			$classes[] = 'blog-layout-' . sanitize_html_class( $s['blog_layout'] );
			$classes[] = 'blog-cols-' . absint( $s['blog_columns'] );
		}
		if ( 'on' === $s['btn_hover_lift'] ) {
			$classes[] = 'btn-hover-lift';
		}

		// v1.1.0 product card classes.
		$classes[] = 'card-ratio-' . sanitize_html_class( $s['card_ratio'] );
		$classes[] = 'card-title-' . sanitize_html_class( $s['card_title_pos'] );
		$classes[] = 'card-progress-' . sanitize_html_class( $s['card_progress'] );
		$classes[] = 'card-hover-' . sanitize_html_class( $s['card_hover'] );

		// v1.2.0 — Winners template body class (drives confetti targeting).
		if ( is_page_template( 'page-winners.php' ) ) {
			$classes[] = 'wprt-is-winners-page';
		}

		return $classes;
	}

	/* ---------------------------------------------------------------------
	 * Optimization (v1.1.0)
	 * ------------------------------------------------------------------- */

	/**
	 * Disable the WordPress emoji script if the toggle is on.
	 */
	public function disable_emoji() {
		$s = WPRaffle_Theme_Settings::instance()->get_settings();
		if ( 'on' !== $s['disable_emoji'] ) {
			return;
		}
		remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
		remove_action( 'wp_print_styles', 'print_emoji_styles' );
		remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
		remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );
		remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );
	}

	/**
	 * Remove the ?ver= query string from script/style URLs.
	 *
	 * @param string $src Asset URL.
	 * @return string
	 */
	public function remove_version_qs( $src ) {
		$s = WPRaffle_Theme_Settings::instance()->get_settings();
		if ( 'on' !== $s['disable_version_qs'] ) {
			return $src;
		}
		if ( false !== strpos( $src, 'ver=' ) ) {
			$src = remove_query_arg( 'ver', $src );
		}
		return $src;
	}

	/**
	 * Preload the hero background image on the homepage.
	 */
	public function preload_hero() {
		$s = WPRaffle_Theme_Settings::instance()->get_settings();
		if ( 'on' !== $s['preload_hero'] || ! is_front_page() ) {
			return;
		}
		$bg = get_theme_mod( 'wpr_hero_bg' );
		if ( $bg ) {
			echo '<link rel="preload" as="image" href="' . esc_url( $bg ) . '">' . "\n";
		}
	}
}
