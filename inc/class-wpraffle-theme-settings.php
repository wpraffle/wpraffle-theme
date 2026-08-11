<?php
/**
 * WPRaffle Theme settings panel.
 *
 * Registers an admin menu under Appearance with three tabs (Style, Content,
 * Advanced). The Style tab's colour pickers drive BOTH the theme's --wpr-*
 * CSS variables AND the plugin's --wpr-* variables, making this the single
 * source of truth for the site's palette.
 *
 * @package WPRaffle_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class WPRaffle_Theme_Settings
 */
final class WPRaffle_Theme_Settings {

	/** @var WPRaffle_Theme_Settings|null */
	private static $instance = null;

	/** Option key under which all style settings are stored. */
	const OPTION = 'wpraffle_theme_settings';

	/** @var array|null Cached settings. */
	private $settings = null;

	/**
	 * Get the singleton.
	 *
	 * @return WPRaffle_Theme_Settings
	 */
	public static function instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * Hook in.
	 */
	private function __construct() {
		add_action( 'admin_menu', array( $this, 'register_menu' ) );
		add_action( 'admin_init', array( $this, 'handle_save' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_assets' ) );
		add_action( 'admin_head', array( $this, 'output_admin_custom_css' ) );

		// Output the palette on the front end (replaces the hardcoded override).
		add_action( 'wp_enqueue_scripts', array( $this, 'output_css_variables' ), 210 );
		// Dynamic Google Fonts (v1.1.0 typography).
		add_action( 'wp_enqueue_scripts', array( $this, 'load_dynamic_fonts' ), 1 );
	}

	/* ---------------------------------------------------------------------
	 * Defaults & presets
	 * ------------------------------------------------------------------- */

	/**
	 * Default settings (the WPRaffle main-site palette).
	 *
	 * @return array
	 */
	public static function get_defaults() {
		return array(
			// Style.
			'preset'      => 'default',
			'accent'      => '#ffdc25',
			'accent_2'    => '#f2c800',
			'dark'        => '#111113',
			'light'       => '#f3f1ea',
			'success'     => '#63dd92',
			'danger'      => '#dc3545',
			'warning'     => '#ffc107',
			'body'        => '#111113',
			// Typography.
			'heading_font'       => 'Inter',
			'body_font'          => 'Inter',
			'heading_weight'     => '800',
			'body_weight'        => '400',
			'body_size'          => 16,
			'h1_size'            => 40,
			'h2_size'            => 32,
			'h3_size'            => 24,
			'h4_size'            => 20,
			'h5_size'            => 16,
			'h6_size'            => 14,
			'letter_spacing'     => 0,
			'line_height'        => 1.55,
			// Buttons.
			'btn_radius'         => 50,
			'btn_padding_x'      => 24,
			'btn_padding_y'      => 12,
			'btn_weight'         => '800',
			'btn_transform'      => 'none',
			'btn_hover_lift'     => 'on',
			// Header.
			'header_layout'      => 'default',
			'header_scroll'      => 'shrink',
			'header_overlay'     => 'off',
			// Homepage sections (order + enabled state).
			'sections'           => array(
				'hero'           => array( 'enabled' => true,  'order' => 1 ),
				'winners'        => array( 'enabled' => true,  'order' => 2 ),
				'active'         => array( 'enabled' => true,  'order' => 3 ),
				'countdown'      => array( 'enabled' => true,  'order' => 4 ),
				'live_draw'      => array( 'enabled' => false, 'order' => 5 ),
				'charity'        => array( 'enabled' => true,  'order' => 6 ),
				'testimonials'   => array( 'enabled' => true,  'order' => 7 ),
				'faq'            => array( 'enabled' => true,  'order' => 8 ),
				'trust'          => array( 'enabled' => true,  'order' => 9 ),
				// v1.2.0 new sections (disabled by default — opt in from Theme Options).
				'how_it_works'   => array( 'enabled' => false, 'order' => 10 ),
				'stats_counter'  => array( 'enabled' => false, 'order' => 11 ),
				'featured'       => array( 'enabled' => false, 'order' => 12 ),
			),
			// FAQ entries (repeatable fields).
			'faqs'              => array(),
			// Testimonial entries (repeatable fields).
			'testimonial_items' => array(),
			// Blog.
			'blog_layout'        => 'grid',
			'blog_columns'       => 2,
			'excerpt_length'     => 25,
			'show_author'        => 'off',
			'show_date'          => 'on',
			'show_category'      => 'on',
			// Optimization.
			'load_google_fonts'  => 'on',
			'load_font_awesome'  => 'on',
			'load_fancybox'      => 'on',
			'load_swiper'        => 'on',
			'disable_emoji'      => 'off',
			'disable_version_qs' => 'off',
			'preload_hero'       => 'off',
			// Custom code.
			'custom_css'         => '',
			'custom_js'          => '',
			'admin_custom_css'   => '',
			// Dark mode.
			'dark_mode'          => 'auto', // auto | manual | off.
			'dark_bg'            => '#0b0b0d',
			'dark_surface'       => '#121215',
			'dark_text'          => '#f5f4ef',
			'dark_muted'         => '#99999f',
			'dark_border'        => '#2a2a2e',
			// Mobile CTA bar.
			'mobile_cta'         => 'off',
			'mobile_cta_text'    => '',
			'mobile_cta_url'     => '',
			// Promo bar.
			'promo_bar'          => 'off',
			'promo_text'         => '',
			'promo_url'          => '',
			'promo_bg'           => '#e4678a',
			'promo_start'        => '',
			'promo_end'          => '',
			// Social proof.
			'social_proof'       => 'off',
			'social_proof_freq'  => 30,
			'social_proof_pos'   => 'bottom-left',
			// Age gate.
			'age_gate'           => 'off',
			'age_title'          => 'Are you 18 or over?',
			'age_text'           => 'You must be 18 or over to enter our competitions.',
			'age_btn_yes'        => 'Yes, I am',
			'age_btn_no'         => 'No',
			'age_no_url'         => '',
			'age_duration'       => 30,
			// Footer.
			'footer_columns'     => 4,
			'footer_cta'         => 'off',
			'footer_cta_title'   => '',
			'footer_cta_text'    => '',
			'footer_cta_btn'     => '',
			'footer_cta_url'     => '',
			'footer_newsletter'  => 'off',
			'footer_instagram'   => 'off',
			// Mega menu.
			'mega_menu'          => 'off',
			// v1.2.0 Responsible-play bar (DCMS Voluntary Code compliance).
			'responsible_play'     => 'on',
			'rg_gamcare_url'       => '',
			'rg_begambleaware_url' => '',
			// v1.2.0 Draw details / odds disclosure.
			'draw_details'         => 'on',
			'draw_mechanism'       => '', // e.g. "Computer-randomised draw, independently verified."
			'terms_url'            => '',
			// Product card.
			'card_ratio'         => '4-3', // 4-3 | 16-9 | square.
			'card_title_pos'     => 'below', // below | overlay.
			'card_progress'      => 'thick', // thick | thin | hidden.
			'card_hover'         => 'lift', // lift | zoom | border.
			// Login page.
			'login_bg'           => '',
			'login_logo'         => '',
			'login_custom_css'   => '',
			// Maintenance mode.
			'maintenance'        => 'off',
			'maintenance_title'  => 'Coming Soon',
			'maintenance_text'   => 'We are launching something exciting. Check back soon!',
			'maintenance_bg'     => '',
			'maintenance_email'  => 'off',
			'maintenance_countdown' => '',
			// 404 page.
			'error_heading'      => "We can't find that page",
			'error_text'         => "The page you're looking for may have moved or no longer exists.",
			'error_show_search'  => 'on',
			'error_show_comps'   => 'on',
			'error_bg'           => '',
			// Advanced.
			'container_width'    => 1220,
			'radius'             => 22,
			'sticky_header'      => 'on',
			'show_topbar'        => 'on',
			'fullwidth_header'   => 'off',
			// v1.2.0 enhancement toggles (all on by default; opt-out via Theme Options).
			'scroll_reveal'      => 'on',
			'back_to_top'        => 'on',
			'confetti_winners'   => 'on',
			'progress_animate'   => 'on',
			'hero_counters'      => 'on',
			// v1.2.0 Trustpilot integration.
			'trustpilot_business_id' => '',
			'trustpilot_position'    => 'off', // hero | footer | both | off.
			// v1.2.0 Trustpilot testimonials (real reviews, not fake styling).
			'testimonials_trustpilot' => 'off',
			// v1.2.0 Cookie consent (theme bar; suppress if a plugin is detected).
			'cookie_consent'        => 'off',
			'cookie_consent_text'   => '',
			'cookie_consent_link'   => '',
			'cookie_consent_duration' => 30,
			// v1.2.0 Video hero.
			'hero_video'            => '',
			// v1.2.0 Instagram feed (completes the footer_instagram stub).
			'instagram_feed_url'    => '',
			// v1.2.0 Chat button.
			'chat_provider'         => 'off', // whatsapp | tawk | crisp | off.
			'chat_number'           => '',
			'chat_id'               => '',
			// v1.2.0 How It Works section content.
			'hiw_title'             => '',
			'hiw_subtitle'          => '',
			'hiw_step1_icon'        => 'fa-mouse-pointer',
			'hiw_step1_title'       => '',
			'hiw_step1_text'        => '',
			'hiw_step2_icon'        => 'fa-question',
			'hiw_step2_title'       => '',
			'hiw_step2_text'        => '',
			'hiw_step3_icon'        => 'fa-hourglass-half',
			'hiw_step3_title'       => '',
			'hiw_step3_text'        => '',
			'hiw_step4_icon'        => 'fa-trophy',
			'hiw_step4_title'       => '',
			'hiw_step4_text'        => '',
			// v1.2.0 Featured spotlight.
			'featured_raffle_id'    => 0, // Admin-picked raffle ID (approach 1).
			'featured_title'        => '',
			'featured_badge'        => '',
		);
	}

	/**
	 * Pre-1.3.1 fallback values for explicitly selected legacy presets.
	 *
	 * Older installations may have saved only a preset and its colours. Using
	 * these fallbacks prevents the new Default typography/shape defaults from
	 * silently changing Diamond, Golf, Retro, Car or Elite after an update.
	 *
	 * @return array
	 */
	private static function get_legacy_defaults() {
		return array_merge( self::get_defaults(), array(
			'heading_font'   => 'Montserrat',
			'body_font'      => 'Montserrat',
			'heading_weight' => '700',
			'body_weight'    => '400',
			'line_height'    => 1.6,
			'btn_weight'     => '600',
			'dark_bg'        => '#1a1a1a',
			'dark_surface'   => '#2c2c2c',
			'dark_text'      => '#e0e0e0',
			'dark_muted'     => '#9ca3af',
			'dark_border'    => '#404040',
			'container_width'=> 1280,
			'radius'         => 8,
		) );
	}

	/**
	 * Colour presets. Mirrors the WPRaffles plugin's own preset names so the
	 * switcher feels consistent. Each preset sets the 8 base colours.
	 *
	 * @return array
	 */
	public static function get_presets() {
		return array(
			'default' => array(
				'name'    => __( 'Default', 'wpraffle-theme' ),
				'colours' => array(
					'accent'   => '#ffdc25',
					'accent_2' => '#f2c800',
					'dark'     => '#111113',
					'light'    => '#f3f1ea',
					'success'  => '#63dd92',
					'danger'   => '#dc3545',
					'warning'  => '#ffc107',
					'body'     => '#111113',
				),
			),
			'diamond' => array(
				'name'    => __( 'Diamond', 'wpraffle-theme' ),
				'colours' => array(
					'accent'   => '#e4678a',
					'accent_2' => '#5caeed',
					'dark'     => '#2c2c2c',
					'light'    => '#f6f6f6',
					'success'  => '#63dd92',
					'danger'   => '#dc3545',
					'warning'  => '#ffc107',
					'body'     => '#2c2c2c',
				),
			),
			'golf' => array(
				'name'    => __( 'Golf', 'wpraffle-theme' ),
				'colours' => array(
					'accent'   => '#2e7d32',
					'accent_2' => '#81c784',
					'dark'     => '#1b3a1f',
					'light'    => '#f1f8f1',
					'success'  => '#43a047',
					'danger'   => '#c62828',
					'warning'  => '#f9a825',
					'body'     => '#1b3a1f',
				),
			),
			'car' => array(
				'name'    => __( 'Car', 'wpraffle-theme' ),
				'colours' => array(
					'accent'   => '#d32f2f',
					'accent_2' => '#ff7043',
					'dark'     => '#212121',
					'light'    => '#fafafa',
					'success'  => '#43a047',
					'danger'   => '#b71c1c',
					'warning'  => '#ffb300',
					'body'     => '#212121',
				),
			),
			'retro' => array(
				'name'    => __( 'Retro', 'wpraffle-theme' ),
				'colours' => array(
					'accent'   => '#7c3aed',
					'accent_2' => '#ec4899',
					'dark'     => '#2d1b4e',
					'light'    => '#f5f0ff',
					'success'  => '#10b981',
					'danger'   => '#ef4444',
					'warning'  => '#f59e0b',
					'body'     => '#2d1b4e',
				),
			),
			'elite' => array(
				'name'    => __( 'Elite', 'wpraffle-theme' ),
				'colours' => array(
					'accent'   => '#c80a0a',
					'accent_2' => '#d4af37',
					'dark'     => '#0a0a0a',
					'light'    => '#f5f5f5',
					'success'  => '#2e7d32',
					'danger'   => '#b71c1c',
					'warning'  => '#fbc02d',
					'body'     => '#1a1a1a',
				),
			),
		);
	}

	/**
	 * Get all merged settings (saved + defaults).
	 *
	 * @return array
	 */
	public function get_settings() {
		if ( null !== $this->settings ) {
			return $this->settings;
		}
		$saved   = get_option( self::OPTION, array() );
		$saved   = is_array( $saved ) ? $saved : array();
		$legacy_presets = array( 'diamond', 'golf', 'retro', 'car', 'elite' );
		$defaults = isset( $saved['preset'] ) && in_array( $saved['preset'], $legacy_presets, true )
			? self::get_legacy_defaults()
			: self::get_defaults();
		$merged  = wp_parse_args( $saved, $defaults );
		$this->settings = $merged;
		return $merged;
	}

	/**
	 * Get a single setting value.
	 *
	 * @param string $key Setting key.
	 * @return mixed
	 */
	public function get( $key ) {
		$s = $this->get_settings();
		return isset( $s[ $key ] ) ? $s[ $key ] : null;
	}

	/* ---------------------------------------------------------------------
	 * Menu + page
	 * ------------------------------------------------------------------- */

	/**
	 * Register the settings page as a sub-menu under Appearance.
	 *
	 * Uses add_theme_page() so "Theme Options" appears under Appearance,
	 * keeping the admin clean (no extra top-level menu) while remaining
	 * easy to find.
	 */
	public function register_menu() {
		add_theme_page(
			__( 'WPRaffle Theme Options', 'wpraffle-theme' ),
			__( 'Theme Options', 'wpraffle-theme' ),
			'manage_options',
			'wpraffle-theme-settings',
			array( $this, 'render_page' )
		);
	}

	/**
	 * Enqueue admin assets only on our settings page.
	 *
	 * @param string $hook Current admin page hook.
	 */
	public function enqueue_admin_assets( $hook ) {
		if ( 'appearance_page_wpraffle-theme-settings' !== $hook ) {
			return;
		}
		wp_enqueue_style( 'wp-color-picker' );
		wp_enqueue_media();
		wp_enqueue_style( 'wpraffle-theme-admin', WPRAFFLE_THEME_URI . '/admin/css/wpraffle-theme-admin.css', array( 'wp-color-picker' ), WPRAFFLE_THEME_VERSION );
		wp_enqueue_script( 'wpraffle-theme-admin', WPRAFFLE_THEME_URI . '/admin/js/wpraffle-theme-admin.js', array( 'jquery', 'wp-color-picker' ), WPRAFFLE_THEME_VERSION, true );
		// v1.2.0 — drag-and-drop homepage builder uses WP's bundled jQuery UI Sortable.
		wp_enqueue_script( 'jquery-ui-sortable' );
	}

	/**
	 * Render the settings page (Enfold-style left-sidebar panel).
	 */
	public function render_page() {
		$current_tab = isset( $_GET['tab'] ) ? sanitize_key( wp_unslash( $_GET['tab'] ) ) : 'style'; // phpcs:ignore WordPress.Security.NonceVerification.Recommended

		// Tab labels.
		$tab_labels = array(
			'style'         => __( 'Style', 'wpraffle-theme' ),
			'typography'    => __( 'Typography', 'wpraffle-theme' ),
			'buttons'       => __( 'Buttons', 'wpraffle-theme' ),
			'header'        => __( 'Header', 'wpraffle-theme' ),
			'footer'        => __( 'Footer', 'wpraffle-theme' ),
			'product-cards' => __( 'Product Cards', 'wpraffle-theme' ),
			'homepage'      => __( 'Homepage', 'wpraffle-theme' ),
			'blog'          => __( 'Blog', 'wpraffle-theme' ),
			'content'       => __( 'Content', 'wpraffle-theme' ),
			'faqs'          => __( 'FAQs', 'wpraffle-theme' ),
			'testimonials'  => __( 'Testimonials', 'wpraffle-theme' ),
			'promo'         => __( 'Promo Bar', 'wpraffle-theme' ),
			'social-proof'  => __( 'Social Proof', 'wpraffle-theme' ),
			'age-gate'      => __( 'Age Gate', 'wpraffle-theme' ),
			'maintenance'   => __( 'Maintenance', 'wpraffle-theme' ),
			'login'         => __( 'Login Page', 'wpraffle-theme' ),
			'error404'      => __( '404 Page', 'wpraffle-theme' ),
			'custom-code'   => __( 'Custom Code', 'wpraffle-theme' ),
			'optimization'  => __( 'Optimization', 'wpraffle-theme' ),
			'advanced'      => __( 'Advanced', 'wpraffle-theme' ),
			'enhancements'  => __( 'Enhancements (v1.2)', 'wpraffle-theme' ),
		);

		// Dashicon per tab.
		$tab_icons = array(
			'style'         => 'admin-appearance',
			'typography'    => 'editor-textcolor',
			'buttons'       => 'button',
			'header'        => 'admin-home',
			'footer'        => 'layout',
			'product-cards' => 'products',
			'homepage'      => 'welcome-widgets-menus',
			'blog'          => 'format-aside',
			'content'       => 'edit-page',
			'faqs'          => 'format-status',
			'testimonials'  => 'format-chat',
			'promo'         => 'megaphone',
			'social-proof'  => 'format-chat',
			'age-gate'      => 'lock',
			'maintenance'   => 'hammer',
			'login'         => 'lock',
			'error404'      => 'warning',
			'custom-code'   => 'editor-code',
			'optimization'  => 'dashboard',
			'advanced'      => 'admin-generic',
			'enhancements'  => 'star-filled',
		);

		// Grouped navigation.
		$tab_groups = array(
			__( 'Appearance', 'wpraffle-theme' ) => array( 'style', 'typography', 'buttons', 'header', 'footer', 'product-cards' ),
			__( 'Layout', 'wpraffle-theme' )     => array( 'homepage', 'blog' ),
			__( 'Content', 'wpraffle-theme' )     => array( 'content', 'faqs', 'testimonials' ),
			__( 'Marketing', 'wpraffle-theme' )   => array( 'promo', 'social-proof', 'age-gate' ),
			__( 'System', 'wpraffle-theme' )       => array( 'maintenance', 'login', 'error404' ),
			__( 'Advanced', 'wpraffle-theme' )    => array( 'custom-code', 'optimization', 'advanced', 'enhancements' ),
		);
		?>
		<div class="wprt-admin">
			<aside class="wprt-admin__sidebar">
				<div class="wprt-admin__brand">
					<span class="dashicons dashicons-admin-appearance"></span>
					<div>
						<strong><?php esc_html_e( 'WPRaffle Theme', 'wpraffle-theme' ); ?></strong>
						<small><?php esc_html_e( 'Options', 'wpraffle-theme' ); ?></small>
					</div>
				</div>

				<nav class="wprt-admin__nav">
					<?php foreach ( $tab_groups as $group_label => $group_tabs ) : ?>
						<div class="wprt-admin__group">
							<span class="wprt-admin__group-label"><?php echo esc_html( $group_label ); ?></span>
							<?php foreach ( $group_tabs as $slug ) : ?>
								<a href="<?php echo esc_url( admin_url( 'themes.php?page=wpraffle-theme-settings&tab=' . $slug ) ); ?>"
									class="wprt-admin__nav-item <?php echo $current_tab === $slug ? 'is-active' : ''; ?>"
									<?php echo $current_tab === $slug ? 'aria-current="page"' : ''; ?>>
									<span class="dashicons dashicons-<?php echo esc_attr( $tab_icons[ $slug ] ); ?>"></span>
									<span class="wprt-admin__nav-label"><?php echo esc_html( $tab_labels[ $slug ] ); ?></span>
								</a>
							<?php endforeach; ?>
						</div>
					<?php endforeach; ?>
				</nav>

				<div class="wprt-admin__sidebar-footer">
					<span class="wprt-admin__version">v<?php echo esc_html( WPRAFFLE_THEME_VERSION ); ?></span>
				</div>
			</aside>

			<main class="wprt-admin__content">
				<?php $this->maybe_show_notice(); ?>

				<div class="wprt-admin__content-header">
					<h1>
						<span class="dashicons dashicons-<?php echo esc_attr( isset( $tab_icons[ $current_tab ] ) ? $tab_icons[ $current_tab ] : 'admin-generic' ); ?>"></span>
						<?php echo esc_html( isset( $tab_labels[ $current_tab ] ) ? $tab_labels[ $current_tab ] : __( 'Settings', 'wpraffle-theme' ) ); ?>
					</h1>
				</div>

				<form method="post" action="" class="wpr-form">
					<?php wp_nonce_field( 'diamond_save_settings', 'diamond_nonce' ); ?>
					<input type="hidden" name="diamond_action" value="save_settings">
					<input type="hidden" name="diamond_tab" value="<?php echo esc_attr( $current_tab ); ?>">

					<?php
					$view = WPRAFFLE_THEME_DIR . '/admin/views/settings-' . $current_tab . '.php';
					if ( file_exists( $view ) ) {
						include $view;
					}
					?>

					<div class="wprt-admin__savebar">
						<button type="submit" class="button button-primary button-large">
							<span class="dashicons dashicons-saved" style="vertical-align:text-top;"></span>
							<?php esc_html_e( 'Save Changes', 'wpraffle-theme' ); ?>
						</button>
					</div>
				</form>
			</main>
		</div>
		<?php
	}

	/**
	 * Show a saved/updated notice.
	 */
	private function maybe_show_notice() {
		if ( isset( $_GET['updated'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
			echo '<div class="notice notice-success is-dismissible"><p>' . esc_html__( 'Settings saved.', 'wpraffle-theme' ) . '</p></div>';
		}
		if ( isset( $_GET['preset'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
			echo '<div class="notice notice-info is-dismissible"><p>' . esc_html__( 'Preset applied. Save changes to confirm.', 'wpraffle-theme' ) . '</p></div>';
		}
		if ( isset( $_GET['reset'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
			echo '<div class="notice notice-warning is-dismissible"><p>' . esc_html__( 'All settings have been reset to defaults.', 'wpraffle-theme' ) . '</p></div>';
		}
	}

	/* ---------------------------------------------------------------------
	 * Save handler
	 * ------------------------------------------------------------------- */

	/**
	 * Handle the form submission + preset GET switching.
	 */
	public function handle_save() {
		// Reset to defaults.
		if ( isset( $_GET['wprt_reset'] ) ) {
			if ( current_user_can( 'manage_options' ) && wp_verify_nonce( sanitize_key( wp_unslash( $_GET['_wpnonce'] ) ), 'wprt_reset' ) ) {
				delete_option( self::OPTION );
				wp_safe_redirect( admin_url( 'themes.php?page=wpraffle-theme-settings&tab=advanced&reset=1' ) );
				exit;
			}
		}

		// Preset switching via GET (the preset buttons).
		if ( isset( $_GET['preset'] ) && isset( $_GET['diamond_preset_nonce'] ) ) {
			if ( current_user_can( 'manage_options' ) && wp_verify_nonce( sanitize_key( wp_unslash( $_GET['diamond_preset_nonce'] ) ), 'diamond_preset' ) ) {
				$preset  = sanitize_key( wp_unslash( $_GET['preset'] ) );
				$presets = self::get_presets();
				if ( isset( $presets[ $preset ] ) ) {
					$saved = get_option( self::OPTION, array() );
					$saved = is_array( $saved ) ? $saved : array();
					$saved['preset'] = $preset;
					$saved = array_merge( $saved, $presets[ $preset ]['colours'] );
					update_option( self::OPTION, $saved );
					wp_safe_redirect( admin_url( 'themes.php?page=wpraffle-theme-settings&tab=style&preset=1' ) );
					exit;
				}
			}
		}

		if ( ! isset( $_POST['diamond_action'] ) || 'save_settings' !== $_POST['diamond_action'] ) {
			return;
		}
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}
		check_admin_referer( 'diamond_save_settings', 'diamond_nonce' );

		$tab    = isset( $_POST['diamond_tab'] ) ? sanitize_key( wp_unslash( $_POST['diamond_tab'] ) ) : 'style';
		$saved  = get_option( self::OPTION, array() );
		$saved  = is_array( $saved ) ? $saved : array();

		if ( 'style' === $tab ) {
			$saved = $this->save_style_tab( $saved );
		} elseif ( 'typography' === $tab ) {
			$saved = $this->save_simple_keys( $saved, array(
				'heading_font', 'body_font', 'heading_weight', 'body_weight',
				'body_size', 'h1_size', 'h2_size', 'h3_size', 'h4_size', 'h5_size', 'h6_size',
				'letter_spacing', 'line_height',
			) );
		} elseif ( 'buttons' === $tab ) {
			$saved = $this->save_simple_keys( $saved, array(
				'btn_radius', 'btn_padding_x', 'btn_padding_y', 'btn_weight', 'btn_transform', 'btn_hover_lift',
			) );
		} elseif ( 'header' === $tab ) {
			$saved = $this->save_simple_keys( $saved, array( 'header_layout', 'header_scroll', 'header_overlay', 'dark_mode', 'mobile_cta', 'mega_menu' ) );
		} elseif ( 'footer' === $tab ) {
			$saved = $this->save_simple_keys( $saved, array( 'footer_columns', 'footer_cta', 'footer_newsletter', 'footer_instagram' ) );
			$saved = $this->save_simple_keys( $saved, array( 'footer_cta_title', 'footer_cta_text', 'footer_cta_btn', 'footer_cta_url' ) );
		} elseif ( 'product-cards' === $tab ) {
			$saved = $this->save_simple_keys( $saved, array( 'card_ratio', 'card_title_pos', 'card_progress', 'card_hover' ) );
		} elseif ( 'promo' === $tab ) {
			$saved = $this->save_simple_keys( $saved, array( 'promo_bar', 'promo_text', 'promo_url', 'promo_bg', 'promo_start', 'promo_end' ) );
		} elseif ( 'social-proof' === $tab ) {
			$saved = $this->save_simple_keys( $saved, array( 'social_proof', 'social_proof_freq', 'social_proof_pos' ) );
		} elseif ( 'age-gate' === $tab ) {
			$saved = $this->save_simple_keys( $saved, array( 'age_gate', 'age_title', 'age_text', 'age_btn_yes', 'age_btn_no', 'age_no_url', 'age_duration' ) );
		} elseif ( 'maintenance' === $tab ) {
			$saved = $this->save_simple_keys( $saved, array( 'maintenance', 'maintenance_title', 'maintenance_text', 'maintenance_bg', 'maintenance_email', 'maintenance_countdown' ) );
		} elseif ( 'login' === $tab ) {
			$saved = $this->save_simple_keys( $saved, array( 'login_bg', 'login_logo' ) );
			$posted = isset( $_POST['wpr_settings'] ) ? wp_unslash( $_POST['wpr_settings'] ) : array(); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput
			$saved['login_custom_css'] = isset( $posted['login_custom_css'] ) ? wp_strip_all_tags( $posted['login_custom_css'] ) : '';
		} elseif ( 'error404' === $tab ) {
			$saved = $this->save_simple_keys( $saved, array( 'error_heading', 'error_text', 'error_show_search', 'error_show_comps', 'error_bg' ) );
		} elseif ( 'homepage' === $tab ) {
			$saved = $this->save_homepage_tab( $saved );
		} elseif ( 'blog' === $tab ) {
			$saved = $this->save_simple_keys( $saved, array(
				'blog_layout', 'blog_columns', 'excerpt_length', 'show_author', 'show_date', 'show_category',
			) );
		} elseif ( 'custom-code' === $tab ) {
			$saved = $this->save_custom_code_tab( $saved );
		} elseif ( 'faqs' === $tab ) {
			$saved = $this->save_repeatable_tab( $saved, 'faqs', array( 'question' => 'wp_kses_post', 'answer' => 'wp_kses_post' ) );
		} elseif ( 'testimonials' === $tab ) {
			$saved = $this->save_repeatable_tab( $saved, 'testimonial_items', array( 'name' => 'sanitize_text_field', 'content' => 'wp_kses_post', 'photo' => 'esc_url_raw' ) );
		} elseif ( 'optimization' === $tab ) {
			$saved = $this->save_simple_keys( $saved, array(
				'load_google_fonts', 'load_font_awesome', 'load_fancybox', 'load_swiper',
				'disable_emoji', 'disable_version_qs', 'preload_hero',
			) );
		} elseif ( 'advanced' === $tab ) {
			$saved = $this->save_advanced_tab( $saved );
		} elseif ( 'enhancements' === $tab ) {
			$saved = $this->save_enhancements_tab( $saved );
		} elseif ( 'content' === $tab ) {
			$this->save_content_tab();
		}

		update_option( self::OPTION, $saved );

		wp_safe_redirect( admin_url( 'themes.php?page=wpraffle-theme-settings&tab=' . $tab . '&updated=1' ) );
		exit;
	}

	/**
	 * Save the Style tab fields.
	 *
	 * @param array $saved Existing saved settings.
	 * @return array
	 */
	private function save_style_tab( $saved ) {
		$posted = isset( $_POST['wpr_settings'] ) ? wp_unslash( $_POST['wpr_settings'] ) : array(); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput
		$posted = is_array( $posted ) ? $posted : array();

		$colour_keys = array( 'accent', 'accent_2', 'dark', 'light', 'success', 'danger', 'warning', 'body', 'dark_bg', 'dark_surface', 'dark_text', 'dark_muted', 'dark_border' );
		foreach ( $colour_keys as $key ) {
			if ( isset( $posted[ $key ] ) ) {
				$saved[ $key ] = sanitize_hex_color( $posted[ $key ] );
			}
		}

		// Preset switching via the preset buttons (AJAX-free GET redirect).
		$preset = isset( $_GET['preset'] ) ? sanitize_key( wp_unslash( $_GET['preset'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification
		if ( ! $preset && isset( $posted['preset'] ) ) {
			$preset = sanitize_key( $posted['preset'] );
		}
		$presets = self::get_presets();
		if ( $preset && isset( $presets[ $preset ] ) ) {
			$saved['preset']   = $preset;
			$saved = array_merge( $saved, $presets[ $preset ]['colours'] );
		} elseif ( isset( $posted['preset'] ) ) {
			$saved['preset'] = 'custom';
		}

		return $saved;
	}

	/**
	 * Save the Advanced tab fields.
	 *
	 * @param array $saved Existing saved settings.
	 * @return array
	 */
	private function save_advanced_tab( $saved ) {
		$posted = isset( $_POST['wpr_settings'] ) ? wp_unslash( $_POST['wpr_settings'] ) : array(); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput
		$posted = is_array( $posted ) ? $posted : array();

		$saved['container_width']  = isset( $posted['container_width'] ) ? max( 800, min( 1920, absint( $posted['container_width'] ) ) ) : 1280;
		$saved['radius']           = isset( $posted['radius'] ) ? max( 0, min( 30, absint( $posted['radius'] ) ) ) : 8;
		$saved['sticky_header']    = isset( $posted['sticky_header'] ) ? 'on' : 'off';
		$saved['show_topbar']      = isset( $posted['show_topbar'] ) ? 'on' : 'off';
		$saved['fullwidth_header'] = isset( $posted['fullwidth_header'] ) ? 'on' : 'off';

		// Update settings: only the auto-update toggle is stored. The GitHub
		// repo is hard-coded in WPRaffle_Theme_Updater::REPO and is no longer
		// user-editable, so it is intentionally NOT read from POST.
		$update_posted = isset( $_POST['wpraffle_theme_update'] ) ? wp_unslash( $_POST['wpraffle_theme_update'] ) : array(); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput
		$update_posted = is_array( $update_posted ) ? $update_posted : array();
		$update_settings = array(
			'github_repo' => 'wpraffle/wpraffle-theme',
			'auto_update' => isset( $update_posted['auto_update'] ) ? '1' : '0',
		);
		update_option( 'wpraffle_theme_update_settings', $update_settings );

		// Clear the cached release info so the new repo is polled.
		delete_transient( 'wpraffle_theme_release_info' );
		delete_transient( 'wpraffle_theme_latest_version' );

		return $saved;
	}

	/**
	 * Save the v1.2.0 Enhancements tab fields.
	 *
	 * Groups all the fact-checked enhancement toggles and content fields in
	 * one place: scroll animations, Trustpilot, cookie consent, video hero,
	 * chat button, How It Works content, and featured spotlight pick.
	 *
	 * @param array $saved Existing settings.
	 * @return array
	 */
	private function save_enhancements_tab( $saved ) {
		// Checkbox toggles (on/off).
		$saved = $this->save_simple_keys( $saved, array(
			'scroll_reveal', 'back_to_top', 'confetti_winners', 'progress_animate', 'hero_counters',
			'testimonials_trustpilot', 'cookie_consent',
			'responsible_play', 'draw_details',
		) );

		// Selects + simple text fields.
		$saved = $this->save_simple_keys( $saved, array(
			'trustpilot_position', 'trustpilot_business_id',
			'chat_provider', 'chat_number', 'chat_id',
			'hero_video', 'instagram_feed_url',
			'featured_raffle_id', 'featured_title', 'featured_badge',
			'hiw_title', 'hiw_subtitle',
			'rg_gamcare_url', 'rg_begambleaware_url', 'draw_mechanism', 'terms_url',
		) );

		// Cookie consent text (allow links) + duration.
		$posted = isset( $_POST['wpr_settings'] ) ? wp_unslash( $_POST['wpr_settings'] ) : array(); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput
		$posted = is_array( $posted ) ? $posted : array();
		$saved['cookie_consent_text'] = isset( $posted['cookie_consent_text'] ) ? wp_kses_post( $posted['cookie_consent_text'] ) : '';
		$saved['cookie_consent_link'] = isset( $posted['cookie_consent_link'] ) ? esc_url_raw( $posted['cookie_consent_link'] ) : '';
		$saved['cookie_consent_duration'] = isset( $posted['cookie_consent_duration'] ) ? max( 1, absint( $posted['cookie_consent_duration'] ) ) : 30;

		// How It Works step content (4 steps × icon/title/text).
		for ( $i = 1; $i <= 4; $i++ ) {
			$saved[ 'hiw_step' . $i . '_icon' ]  = isset( $posted[ 'hiw_step' . $i . '_icon' ] ) ? sanitize_text_field( $posted[ 'hiw_step' . $i . '_icon' ] ) : '';
			$saved[ 'hiw_step' . $i . '_title' ] = isset( $posted[ 'hiw_step' . $i . '_title' ] ) ? sanitize_text_field( $posted[ 'hiw_step' . $i . '_title' ] ) : '';
			$saved[ 'hiw_step' . $i . '_text' ]  = isset( $posted[ 'hiw_step' . $i . '_text' ] ) ? sanitize_text_field( $posted[ 'hiw_step' . $i . '_text' ] ) : '';
		}

		// Featured raffle ID (absint).
		$saved['featured_raffle_id'] = isset( $posted['featured_raffle_id'] ) ? absint( $posted['featured_raffle_id'] ) : 0;

		return $saved;
	}

	/**
	 * Save the Content tab fields into theme_mods so existing templates keep
	 * working unchanged.
	 */
	private function save_content_tab() {
		$posted = isset( $_POST['wpr_settings'] ) ? wp_unslash( $_POST['wpr_settings'] ) : array(); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput
		$posted = is_array( $posted ) ? $posted : array();

		$text_fields = array(
			'diamond_hero_eyebrow', 'diamond_hero_title', 'diamond_hero_lead', 'diamond_hero_bg',
			'diamond_stat_winners', 'diamond_stat_raised', 'diamond_stat_rating',
			'diamond_topbar_text',
			'diamond_active_title', 'diamond_active_subtitle',
			'diamond_winners_title', 'diamond_winners_subtitle',
			'diamond_charity_title', 'diamond_charity_subtitle',
			'diamond_trust_title', 'diamond_trust_subtitle',
			'wpraffle_theme_footer_about',
			'diamond_social_facebook', 'diamond_social_instagram', 'diamond_social_x', 'diamond_social_tiktok', 'diamond_social_youtube',
		);

		foreach ( $text_fields as $key ) {
			if ( isset( $posted[ $key ] ) ) {
				$val = $posted[ $key ];
				// URLs vs text.
				if ( false !== strpos( $key, '_social_' ) || 'diamond_hero_bg' === $key ) {
					set_theme_mod( $key, esc_url_raw( $val ) );
				} else {
					set_theme_mod( $key, sanitize_text_field( $val ) );
				}
			}
		}

		// Page assignments (dropdowns). Stored as theme_mods so the resolvers
		// in template-tags.php find them via get_theme_mod().
		$page_keys = array( 'diamond_page_competitions', 'diamond_page_winners', 'diamond_page_charities' );
		foreach ( $page_keys as $key ) {
			$val = isset( $posted[ $key ] ) ? absint( $posted[ $key ] ) : 0;
			set_theme_mod( $key, $val );
		}
	}

	/**
	 * Save a list of keys from the posted data. Numeric keys get absint/floatval,
	 * checkbox keys get on/off, the rest are sanitised text.
	 *
	 * @param array $saved Existing settings.
	 * @param array $keys  Keys to save.
	 * @return array
	 */
	private function save_simple_keys( $saved, $keys ) {
		$posted = isset( $_POST['wpr_settings'] ) ? wp_unslash( $_POST['wpr_settings'] ) : array(); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput
		$posted = is_array( $posted ) ? $posted : array();

		$numeric = array( 'body_size', 'h1_size', 'h2_size', 'h3_size', 'h4_size', 'h5_size', 'h6_size', 'letter_spacing', 'btn_radius', 'btn_padding_x', 'btn_padding_y', 'blog_columns', 'excerpt_length' );
		$float   = array( 'line_height' );
		$checkbox = array( 'btn_hover_lift', 'show_author', 'show_date', 'show_category', 'load_google_fonts', 'load_font_awesome', 'load_fancybox', 'load_swiper', 'disable_emoji', 'disable_version_qs', 'preload_hero', 'scroll_reveal', 'back_to_top', 'confetti_winners', 'progress_animate', 'hero_counters', 'testimonials_trustpilot', 'cookie_consent', 'responsible_play', 'draw_details' );

		foreach ( $keys as $key ) {
			if ( in_array( $key, $checkbox, true ) ) {
				$saved[ $key ] = isset( $posted[ $key ] ) ? 'on' : 'off';
			} elseif ( in_array( $key, $numeric, true ) ) {
				$saved[ $key ] = isset( $posted[ $key ] ) ? absint( $posted[ $key ] ) : 0;
			} elseif ( in_array( $key, $float, true ) ) {
				$saved[ $key ] = isset( $posted[ $key ] ) ? floatval( $posted[ $key ] ) : 1.6;
			} else {
				$saved[ $key ] = isset( $posted[ $key ] ) ? sanitize_text_field( $posted[ $key ] ) : '';
			}
		}
		return $saved;
	}

	/**
	 * Save the Homepage (section manager) tab.
	 *
	 * @param array $saved Existing settings.
	 * @return array
	 */
	private function save_homepage_tab( $saved ) {
		$posted = isset( $_POST['wpr_settings'] ) ? wp_unslash( $_POST['wpr_settings'] ) : array(); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput
		$posted = is_array( $posted ) ? $posted : array();

		$sections    = isset( $posted['sections'] ) && is_array( $posted['sections'] ) ? $posted['sections'] : array();
		$defaults    = self::get_defaults();
		$clean       = array();
		$all_keys    = array_keys( $defaults['sections'] );

		foreach ( $all_keys as $key ) {
			$clean[ $key ] = array(
				'enabled' => isset( $sections[ $key ]['enabled'] ),
				'order'   => isset( $sections[ $key ]['order'] ) ? absint( $sections[ $key ]['order'] ) : $defaults['sections'][ $key ]['order'],
			);
		}
		$saved['sections'] = $clean;
		return $saved;
	}

	/**
	 * Save the Custom Code tab (CSS/JS textareas).
	 *
	 * @param array $saved Existing settings.
	 * @return array
	 */
	private function save_custom_code_tab( $saved ) {
		$posted = isset( $_POST['wpr_settings'] ) ? wp_unslash( $_POST['wpr_settings'] ) : array(); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput
		$posted = is_array( $posted ) ? $posted : array();

		// CSS is stripped of dangerous tags but otherwise preserved; JS is more
		// locked down (wp_kses_post strips script wrappers, but raw JS in the
		// footer is intentional here — admin-only capability gates this).
		$saved['custom_css']       = isset( $posted['custom_css'] ) ? wp_strip_all_tags( $posted['custom_css'] ) : '';
		$saved['custom_js']        = isset( $posted['custom_js'] ) ? wp_strip_all_tags( $posted['custom_js'] ) : '';
		$saved['admin_custom_css'] = isset( $posted['admin_custom_css'] ) ? wp_strip_all_tags( $posted['admin_custom_css'] ) : '';
		return $saved;
	}

	/**
	 * Save a repeatable-field tab (FAQs / Testimonials).
	 *
	 * Reads posted rows from diamond[<key>], sanitises each field per the
	 * provided callback map, drops empty rows, and re-indexes.
	 *
	 * @param array  $saved      Existing settings.
	 * @param string $key        Settings key (e.g. 'faqs').
	 * @param array  $sanitisers Map of field => callback.
	 * @return array
	 */
	private function save_repeatable_tab( $saved, $key, $sanitisers ) {
		$posted = isset( $_POST['wpr_settings'][ $key ] ) ? wp_unslash( $_POST['wpr_settings'][ $key ] ) : array(); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput
		$posted = is_array( $posted ) ? $posted : array();

		$clean = array();
		foreach ( $posted as $row ) {
			if ( ! is_array( $row ) ) {
				continue;
			}
			// Skip rows that are entirely empty.
			$has_content = false;
			$clean_row   = array();
			foreach ( $sanitisers as $field => $callback ) {
				$val = isset( $row[ $field ] ) ? call_user_func( $callback, $row[ $field ] ) : '';
				if ( '' !== $val ) {
					$has_content = true;
				}
				$clean_row[ $field ] = $val;
			}
			if ( $has_content ) {
				$clean[] = $clean_row;
			}
		}

		$saved[ $key ] = $clean;
		return $saved;
	}

	/* ---------------------------------------------------------------------
	 * Colour-math helpers
	 * ------------------------------------------------------------------- */

	/**
	 * Parse a hex colour to an RGB array.
	 *
	 * @param string $hex Hex colour (#rgb or #rrggbb).
	 * @return array [r,g,b] each 0-255.
	 */
	public static function hex_to_rgb( $hex ) {
		$hex = ltrim( $hex, '#' );
		if ( 3 === strlen( $hex ) ) {
			$hex = $hex[0] . $hex[0] . $hex[1] . $hex[1] . $hex[2] . $hex[2];
		}
		if ( 6 !== strlen( $hex ) ) {
			return array( 0, 0, 0 );
		}
		return array(
			hexdec( substr( $hex, 0, 2 ) ),
			hexdec( substr( $hex, 2, 2 ) ),
			hexdec( substr( $hex, 4, 2 ) ),
		);
	}

	/**
	 * Darken a hex colour by a percentage.
	 *
	 * @param string $hex     Hex colour.
	 * @param float  $percent Percent to darken (0-100).
	 * @return string Darkened hex.
	 */
	public static function darken( $hex, $percent ) {
		$rgb = self::hex_to_rgb( $hex );
		$f   = 1 - ( $percent / 100 );
		return sprintf( '#%02x%02x%02x', max( 0, min( 255, round( $rgb[0] * $f ) ) ), max( 0, min( 255, round( $rgb[1] * $f ) ) ), max( 0, min( 255, round( $rgb[2] * $f ) ) ) );
	}

	/**
	 * Tint a hex colour over white by a given opacity (for subtle backgrounds).
	 *
	 * @param string $hex     Hex colour.
	 * @param float  $opacity 0-1.
	 * @return string Hex result.
	 */
	public static function tint( $hex, $opacity ) {
		$rgb = self::hex_to_rgb( $hex );
		$r   = round( $rgb[0] + ( 255 - $rgb[0] ) * $opacity );
		$g   = round( $rgb[1] + ( 255 - $rgb[1] ) * $opacity );
		$b   = round( $rgb[2] + ( 255 - $rgb[2] ) * $opacity );
		return sprintf( '#%02x%02x%02x', $r, $g, $b );
	}

	/**
	 * Hex → "r, g, b" string for use in rgba().
	 *
	 * @param string $hex Hex colour.
	 * @return string
	 */
	public static function hex_to_rgb_string( $hex ) {
		$rgb = self::hex_to_rgb( $hex );
		return $rgb[0] . ', ' . $rgb[1] . ', ' . $rgb[2];
	}

	/* ---------------------------------------------------------------------
	 * CSS-variable output (front end)
	 * ------------------------------------------------------------------- */

	/**
	 * Build the :root:root CSS block from the saved palette and emit it.
	 * This replaces the old hardcoded override_plugin_variables().
	 */
	public function output_css_variables() {
		$s = $this->get_settings();

		$accent    = $s['accent'];
		$accent2   = $s['accent_2'];
		$dark      = $s['dark'];
		$light     = $s['light'];
		$success   = $s['success'];
		$danger    = $s['danger'];
		$warning   = $s['warning'];
		$body      = $s['body'];
		$container = absint( $s['container_width'] );
		$radius    = absint( $s['radius'] );

		// Derived colours.
		$accent_dark  = self::darken( $accent, 12 );
		$accent2_dark = self::darken( $accent2, 12 );
		$success_dark = self::darken( $success, 12 );
		$danger_dark  = self::darken( $danger, 12 );
		$accent_bg    = self::tint( $accent, 0.88 );
		$accent_bord  = self::tint( $accent, 0.72 );
		$accent_text  = self::darken( $accent, 18 );
		$border       = self::tint( $dark, 0.84 );
		$border_strong = self::tint( $dark, 0.72 );
		$muted        = self::tint( $dark, 0.62 );

		$vars = ':root:root{'
			// Theme tokens.
			. '--wpr-accent:' . $accent . ';'
			. '--wpr-accent-rgb:' . self::hex_to_rgb_string( $accent ) . ';'
			. '--wpr-accent-dark:' . $accent_dark . ';'
			. '--wpr-accent-2:' . $accent2 . ';'
			. '--wpr-accent-2-rgb:' . self::hex_to_rgb_string( $accent2 ) . ';'
			. '--wpr-accent-2-dark:' . $accent2_dark . ';'
			. '--wpr-success:' . $success . ';'
			. '--wpr-success-dark:' . $success_dark . ';'
			. '--wpr-danger:' . $danger . ';'
			. '--wpr-warning:' . $warning . ';'
			. '--wpr-dark:' . $dark . ';'
			. '--wpr-dark-rgb:' . self::hex_to_rgb_string( $dark ) . ';'
			. '--wpr-body:' . $body . ';'
			. '--wpr-muted:' . $muted . ';'
			. '--wpr-light:' . $light . ';'
			. '--wpr-lighter:#ffffff;'
			. '--wpr-border:' . $border . ';'
			. '--wpr-border-strong:' . $border_strong . ';'
			. '--wpr-radius:' . $radius . 'px;'
			. '--wpr-radius-sm:' . max( 0, $radius - 2 ) . 'px;'
			. '--wpr-radius-lg:' . ( $radius + 4 ) . 'px;'
			. '--wpr-container:' . $container . 'px;'
			// Plugin tokens (theme owns these now).
			. '--wpr-accent:' . $accent . ';'
			. '--wpr-accent-dark:' . $accent_dark . ';'
			. '--wpr-accent-bg:' . $accent_bg . ';'
			. '--wpr-accent-border:' . $accent_bord . ';'
			. '--wpr-accent-text:' . $accent_text . ';'
			. '--wpr-accent-text-dark:' . self::darken( $accent, 28 ) . ';'
			. '--wpr-accent-secondary:' . $accent2 . ';'
			. '--wpr-accent-secondary-dark:' . $accent2_dark . ';'
			. '--wpr-text-primary:' . $dark . ';'
			. '--wpr-text-secondary:' . $body . ';'
			. '--wpr-text-muted:' . $muted . ';'
			. '--wpr-text-inverse:#ffffff;'
			. '--wpr-text-dark:' . self::darken( $dark, 10 ) . ';'
			. '--wpr-bg-surface:#ffffff;'
			. '--wpr-bg-subtle:' . $light . ';'
			. '--wpr-bg-muted:' . self::tint( $dark, 0.90 ) . ';'
			. '--wpr-border-color:' . $border . ';'
			. '--wpr-border-strong:' . $border_strong . ';'
			. '--wpr-success:' . $success . ';'
			. '--wpr-success-dark:' . $success_dark . ';'
			. '--wpr-danger:' . $danger . ';'
			. '--wpr-danger-dark:' . $danger_dark . ';'
			. '--wpr-warning:' . $warning . ';'
			. '--wpr-warning-light:' . self::tint( $warning, 0.8 ) . ';'
			. '--wpr-live-color:' . $success . ';'
			. '--wpr-live-bg:' . self::tint( $success, 0.88 ) . ';'
			. '--wpr-draw-color:' . $warning . ';'
			. '--wpr-progress-low:' . $danger . ';'
			. '--wpr-progress-mid:' . $warning . ';'
			. '--wpr-progress-high:' . $success . ';'
			. '--wpr-radius:' . $radius . 'px;'
				. '--wpr-radius-sm:' . max( 0, $radius - 2 ) . 'px;'
				. '--wpr-radius-pill:' . intval( $s['btn_radius'] ) . 'px;'
				. '--wpr-btn-padding-x:' . intval( $s['btn_padding_x'] ) . 'px;'
				. '--wpr-btn-padding-y:' . intval( $s['btn_padding_y'] ) . 'px;'
				. '--wpr-btn-weight:' . intval( $s['btn_weight'] ) . ';'
				// Typography tokens (v1.1.0).
				. '--wpr-font-head:"' . $s['heading_font'] . '",system-ui,sans-serif;'
				. '--wpr-font-sans:"' . $s['body_font'] . '",system-ui,sans-serif;'
				. '--wpr-font-size-base:' . intval( $s['body_size'] ) . 'px;'
				. '--wpr-line-height:' . floatval( $s['line_height'] ) . ';'
				. '--wpr-h1-size:' . intval( $s['h1_size'] ) . 'px;'
				. '--wpr-h2-size:' . intval( $s['h2_size'] ) . 'px;'
				. '--wpr-h3-size:' . intval( $s['h3_size'] ) . 'px;'
				. '--wpr-h4-size:' . intval( $s['h4_size'] ) . 'px;'
				. '--wpr-h5-size:' . intval( $s['h5_size'] ) . 'px;'
				. '--wpr-h6-size:' . intval( $s['h6_size'] ) . 'px;'
				// Button tokens (v1.1.0).
				. '--wpr-btn-radius:' . intval( $s['btn_radius'] ) . 'px;'
				. '--wpr-btn-px:' . intval( $s['btn_padding_x'] ) . 'px;'
				. '--wpr-btn-py:' . intval( $s['btn_padding_y'] ) . 'px;'
				. '--wpr-btn-weight:' . intval( $s['btn_weight'] ) . ';'
				. '--wpr-btn-transform:' . $s['btn_transform'] . ';'
				. '}';

		wp_add_inline_style( 'wpraffle-theme-integration', $vars );

		// Advanced toggles (body classes are applied via setup; here we emit the
		// CSS that the toggles control).
		$toggle_css = '';
		if ( 'off' === $s['sticky_header'] ) {
			$toggle_css .= '.wpr-header{position:relative !important;}';
		}
		if ( 'off' === $s['show_topbar'] ) {
			$toggle_css .= '.wpr-topbar{display:none !important;}';
		}
		if ( 'on' === $s['fullwidth_header'] ) {
			$toggle_css .= '.wpr-header > .container,.wpr-header__inner{max-width:100% !important;padding-left:2rem !important;padding-right:2rem !important;}';
		}
		if ( $toggle_css ) {
			wp_add_inline_style( 'wpraffle-theme-base', $toggle_css );
		}

		// Dark mode CSS (applies when data-theme=dark on <html>).
		if ( 'off' !== $s['dark_mode'] ) {
			$dark_css = '[data-theme="dark"]{'
				. '--wpr-dark:' . $s['dark_bg'] . ';'
				. '--wpr-dark-rgb:' . self::hex_to_rgb_string( $s['dark_bg'] ) . ';'
				. '--wpr-body:' . $s['dark_text'] . ';'
				. '--wpr-muted:' . $s['dark_muted'] . ';'
				. '--wpr-light:' . $s['dark_surface'] . ';'
				. '--wpr-lighter:' . $s['dark_surface'] . ';'
				. '--wpr-border:' . $s['dark_border'] . ';'
				. '--wpr-border-strong:' . self::darken( $s['dark_border'], 15 ) . ';'
				. '--wpr-bg:#ffffff;--wpr-text:' . $s['dark_text'] . ';'
				. '}'
				. '[data-theme="dark"] body{background:' . $s['dark_bg'] . ';color:' . $s['dark_text'] . ';}'
				. '[data-theme="dark"] .wpr-header,[data-theme="dark"] .wpr-topbar{background:' . $s['dark_surface'] . ';}'
				. '[data-theme="dark"] .wpr-header{border-color:' . $s['dark_border'] . ';}'
				. '[data-theme="dark"] .wpr-logo__text,[data-theme="dark"] .wpr-nav a{color:' . $s['dark_text'] . ';}'
				. '[data-theme="dark"] .wpr-card,[data-theme="dark"] .wpr-panel{background:' . $s['dark_surface'] . ';border-color:' . $s['dark_border'] . ';}'
				. '[data-theme="dark"] .wpr-card__title a{color:' . $s['dark_text'] . ';}'
				. '[data-theme="dark"] .wpr-footer{background:' . $s['dark_bg'] . ';}'
				. '[data-theme="dark"] h1,[data-theme="dark"] h2,[data-theme="dark"] h3,[data-theme="dark"] h4,[data-theme="dark"] h5,[data-theme="dark"] h6{color:' . $s['dark_text'] . ';}'
				. '[data-theme="dark"] .section--tint{background:' . $s['dark_surface'] . ';}';
			wp_add_inline_style( 'wpraffle-theme-base', $dark_css );
		}

		// Custom CSS from the Custom Code tab.
		if ( ! empty( $s['custom_css'] ) ) {
			wp_add_inline_style( 'wpraffle-theme-base', "\n/* Custom CSS */\n" . $s['custom_css'] );
		}

		// Custom JS in the footer.
		if ( ! empty( $s['custom_js'] ) ) {
			add_action( 'wp_footer', function() use ( $s ) {
				echo "\n<script>\n/* Custom JS */\n" . $s['custom_js'] . "\n</script>\n"; // phpcs:ignore WordPress.Security.OutputNotEscaped
			}, 999 );
		}
	}

	/**
	 * Load Google Fonts dynamically based on the typography settings.
	 * Replaces the hardcoded Montserrat enqueue in the setup class when active.
	 */
	public function load_dynamic_fonts() {
		$s = $this->get_settings();
		if ( 'off' === $s['load_google_fonts'] ) {
			return;
		}

		$fonts   = array_filter( array_unique( array( $s['heading_font'], $s['body_font'] ) ) );
		if ( empty( $fonts ) ) {
			return;
		}

		$weights = array_filter( array_unique( array( $s['heading_weight'], $s['body_weight'], '400', '600', '700', '800' ) ) );
		$w_str   = implode( ';', array_map( function( $w ) {
			return '0,' . $w . ';1,' . $w;
		}, $weights ) );

		$families = array();
		foreach ( $fonts as $font ) {
			$families[] = 'family=' . rawurlencode( $font . ':' . $w_str ) . ':ital,wght@0,400;0,600;0,700;0,800';
		}
		$url = 'https://fonts.googleapis.com/css2?' . implode( '&', array_unique( array(
			'family=' . rawurlencode( $fonts[0] . ':wght@300;400;500;600;700;800' ),
		) ) );

		// Combine all selected fonts.
		$parts = array();
		foreach ( $fonts as $font ) {
			$parts[] = 'family=' . str_replace( ' ', '+', $font ) . ':wght@300;400;500;600;700;800';
		}
		$url = 'https://fonts.googleapis.com/css2?' . implode( '&', $parts ) . '&display=swap';

		wp_enqueue_style( 'wpraffle-theme-fonts', $url, array(), null );
	}

	/**
	 * Output admin custom CSS on admin pages.
	 */
	public function output_admin_custom_css() {
		$s = $this->get_settings();
		if ( ! empty( $s['admin_custom_css'] ) ) {
			echo '<style>' . "\n/* Custom Admin CSS */\n" . $s['admin_custom_css'] . "\n</style>\n"; // phpcs:ignore WordPress.Security.OutputNotEscaped
		}
	}
}
