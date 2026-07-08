<?php
/**
 * Diamond theme settings panel.
 *
 * Registers a top-level "Diamond" admin menu with three tabs (Style, Content,
 * Advanced). The Style tab's colour pickers drive BOTH the theme's --diamond-*
 * CSS variables AND the plugin's --wpr-* variables, making this the single
 * source of truth for the site's palette.
 *
 * @package Diamond
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

		// Output the palette on the front end (replaces the hardcoded override).
		add_action( 'wp_enqueue_scripts', array( $this, 'output_css_variables' ), 210 );
	}

	/* ---------------------------------------------------------------------
	 * Defaults & presets
	 * ------------------------------------------------------------------- */

	/**
	 * Default settings (the Paragon-inspired Diamond palette).
	 *
	 * @return array
	 */
	public static function get_defaults() {
		return array(
			// Style.
			'preset'      => 'diamond',
			'accent'      => '#e4678a',
			'accent_2'    => '#5caeed',
			'dark'        => '#2c2c2c',
			'light'       => '#f6f6f6',
			'success'     => '#63dd92',
			'danger'      => '#dc3545',
			'warning'     => '#ffc107',
			'body'        => '#2c2c2c',
			// Advanced.
			'container_width'   => 1280,
			'radius'            => 8,
			'sticky_header'     => 'on',
			'show_topbar'       => 'on',
			'fullwidth_header'  => 'off',
		);
	}

	/**
	 * Colour presets. Mirrors the WPRaffles plugin's own preset names so the
	 * switcher feels consistent. Each preset sets the 8 base colours.
	 *
	 * @return array
	 */
	public static function get_presets() {
		return array(
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
		$merged  = wp_parse_args( $saved, self::get_defaults() );
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
			'diamond-settings',
			array( $this, 'render_page' )
		);
	}

	/**
	 * Enqueue admin assets only on our settings page.
	 *
	 * @param string $hook Current admin page hook.
	 */
	public function enqueue_admin_assets( $hook ) {
		if ( 'appearance_page_diamond-settings' !== $hook ) {
			return;
		}
		wp_enqueue_style( 'wp-color-picker' );
		wp_enqueue_media();
		wp_enqueue_style( 'wpraffle-theme-admin', WPRAFFLE_THEME_URI . '/admin/css/wpraffle-theme-admin.css', array( 'wp-color-picker' ), WPRAFFLE_THEME_VERSION );
		wp_enqueue_script( 'wpraffle-theme-admin', WPRAFFLE_THEME_URI . '/admin/js/wpraffle-theme-admin.js', array( 'jquery', 'wp-color-picker' ), WPRAFFLE_THEME_VERSION, true );
	}

	/**
	 * Render the settings page (tabbed).
	 */
	public function render_page() {
		$current_tab = isset( $_GET['tab'] ) ? sanitize_key( wp_unslash( $_GET['tab'] ) ) : 'style'; // phpcs:ignore WordPress.Security.NonceVerification.Recommended
		$tabs        = array(
			'style'    => __( 'Style', 'wpraffle-theme' ),
			'content'  => __( 'Content', 'wpraffle-theme' ),
			'advanced' => __( 'Advanced', 'wpraffle-theme' ),
		);
		?>
		<div class="wrap diamond-wrap">
			<h1><span class="dashicons dashicons-admin-appearance"></span> <?php esc_html_e( 'WPRaffle Theme Options', 'wpraffle-theme' ); ?></h1>
			<h2 class="nav-tab-wrapper">
				<?php foreach ( $tabs as $slug => $label ) : ?>
					<a href="<?php echo esc_url( admin_url( 'themes.php?page=diamond-settings&tab=' . $slug ) ); ?>" class="nav-tab <?php echo $current_tab === $slug ? 'nav-tab-active' : ''; ?>"><?php echo esc_html( $label ); ?></a>
				<?php endforeach; ?>
			</h2>

			<?php $this->maybe_show_notice(); ?>

			<form method="post" action="" class="diamond-form">
				<?php wp_nonce_field( 'diamond_save_settings', 'diamond_nonce' ); ?>
				<input type="hidden" name="diamond_action" value="save_settings">
				<input type="hidden" name="diamond_tab" value="<?php echo esc_attr( $current_tab ); ?>">

				<?php
				$view = WPRAFFLE_THEME_DIR . '/admin/views/settings-' . $current_tab . '.php';
				if ( file_exists( $view ) ) {
					include $view;
				}
				?>

				<p class="submit">
					<button type="submit" class="button button-primary button-hero"><?php esc_html_e( 'Save Changes', 'wpraffle-theme' ); ?></button>
				</p>
			</form>
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
	}

	/* ---------------------------------------------------------------------
	 * Save handler
	 * ------------------------------------------------------------------- */

	/**
	 * Handle the form submission + preset GET switching.
	 */
	public function handle_save() {
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
					wp_safe_redirect( admin_url( 'themes.php?page=diamond-settings&tab=style&preset=1' ) );
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
		} elseif ( 'advanced' === $tab ) {
			$saved = $this->save_advanced_tab( $saved );
		} elseif ( 'content' === $tab ) {
			$this->save_content_tab();
		}

		update_option( self::OPTION, $saved );

		wp_safe_redirect( admin_url( 'themes.php?page=diamond-settings&tab=' . $tab . '&updated=1' ) );
		exit;
	}

	/**
	 * Save the Style tab fields.
	 *
	 * @param array $saved Existing saved settings.
	 * @return array
	 */
	private function save_style_tab( $saved ) {
		$posted = isset( $_POST['diamond'] ) ? wp_unslash( $_POST['diamond'] ) : array(); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput
		$posted = is_array( $posted ) ? $posted : array();

		$colour_keys = array( 'accent', 'accent_2', 'dark', 'light', 'success', 'danger', 'warning', 'body' );
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
		$posted = isset( $_POST['diamond'] ) ? wp_unslash( $_POST['diamond'] ) : array(); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput
		$posted = is_array( $posted ) ? $posted : array();

		$saved['container_width']  = isset( $posted['container_width'] ) ? max( 800, min( 1920, absint( $posted['container_width'] ) ) ) : 1280;
		$saved['radius']           = isset( $posted['radius'] ) ? max( 0, min( 30, absint( $posted['radius'] ) ) ) : 8;
		$saved['sticky_header']    = isset( $posted['sticky_header'] ) ? 'on' : 'off';
		$saved['show_topbar']      = isset( $posted['show_topbar'] ) ? 'on' : 'off';
		$saved['fullwidth_header'] = isset( $posted['fullwidth_header'] ) ? 'on' : 'off';

		// Update settings (GitHub repo + auto-update toggle) live in their own
		// option namespace so the updater class reads them directly.
		$update_posted = isset( $_POST['wpraffle_theme_update'] ) ? wp_unslash( $_POST['wpraffle_theme_update'] ) : array(); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput
		$update_posted = is_array( $update_posted ) ? $update_posted : array();
		$update_settings = array(
			'github_repo' => isset( $update_posted['github_repo'] ) ? sanitize_text_field( $update_posted['github_repo'] ) : 'wpraffle/wpraffle-theme',
			'auto_update' => isset( $update_posted['auto_update'] ) ? '1' : '0',
		);
		update_option( 'wpraffle_theme_update_settings', $update_settings );

		// Clear the cached release info so the new repo is polled.
		delete_transient( 'wpraffle_theme_release_info' );
		delete_transient( 'wpraffle_theme_latest_version' );

		return $saved;
	}

	/**
	 * Save the Content tab fields into theme_mods so existing templates keep
	 * working unchanged.
	 */
	private function save_content_tab() {
		$posted = isset( $_POST['diamond'] ) ? wp_unslash( $_POST['diamond'] ) : array(); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput
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
			. '--diamond-accent:' . $accent . ';'
			. '--diamond-accent-rgb:' . self::hex_to_rgb_string( $accent ) . ';'
			. '--diamond-accent-dark:' . $accent_dark . ';'
			. '--diamond-accent-2:' . $accent2 . ';'
			. '--diamond-accent-2-rgb:' . self::hex_to_rgb_string( $accent2 ) . ';'
			. '--diamond-accent-2-dark:' . $accent2_dark . ';'
			. '--diamond-success:' . $success . ';'
			. '--diamond-success-dark:' . $success_dark . ';'
			. '--diamond-danger:' . $danger . ';'
			. '--diamond-warning:' . $warning . ';'
			. '--diamond-dark:' . $dark . ';'
			. '--diamond-dark-rgb:' . self::hex_to_rgb_string( $dark ) . ';'
			. '--diamond-body:' . $body . ';'
			. '--diamond-muted:' . $muted . ';'
			. '--diamond-light:' . $light . ';'
			. '--diamond-lighter:#ffffff;'
			. '--diamond-border:' . $border . ';'
			. '--diamond-border-strong:' . $border_strong . ';'
			. '--diamond-radius:' . $radius . 'px;'
			. '--diamond-radius-sm:' . max( 0, $radius - 2 ) . 'px;'
			. '--diamond-radius-lg:' . ( $radius + 4 ) . 'px;'
			. '--diamond-container:' . $container . 'px;'
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
			. '--wpr-radius-pill:50rem;'
			. '}';

		wp_add_inline_style( 'wpraffle-theme-integration', $vars );

		// Advanced toggles (body classes are applied via setup; here we emit the
		// CSS that the toggles control).
		$toggle_css = '';
		if ( 'off' === $s['sticky_header'] ) {
			$toggle_css .= '.diamond-header{position:relative !important;}';
		}
		if ( 'off' === $s['show_topbar'] ) {
			$toggle_css .= '.diamond-topbar{display:none !important;}';
		}
		if ( 'on' === $s['fullwidth_header'] ) {
			$toggle_css .= '.diamond-header > .container,.diamond-header__inner{max-width:100% !important;padding-left:2rem !important;padding-right:2rem !important;}';
		}
		if ( $toggle_css ) {
			wp_add_inline_style( 'wpraffle-theme-base', $toggle_css );
		}
	}
}
