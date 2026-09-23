<?php
/**
 * Premium control centre, setup helpers and theme tools.
 *
 * @package WPRaffle_Theme
 * @since   1.4.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

final class WPRaffle_Theme_Control_Center {

	private static $instance = null;

	public static function instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	private function __construct() {
		add_action( 'admin_post_wprt_create_pages', array( $this, 'create_recommended_pages' ) );
		add_action( 'admin_post_wprt_apply_starter', array( $this, 'apply_starter' ) );
		add_action( 'admin_post_wprt_export_settings', array( $this, 'export_settings' ) );
		add_action( 'admin_post_wprt_import_settings', array( $this, 'import_settings' ) );
		add_action( 'admin_post_wprt_create_menu', array( $this, 'create_primary_menu' ) );
		add_action( 'admin_post_wprt_download_system_report', array( $this, 'download_system_report' ) );
		add_action( 'admin_post_wprt_generate_child_theme', array( $this, 'generate_child_theme' ) );
		add_action( 'admin_post_wprt_complete_setup', array( $this, 'complete_setup' ) );
		add_action( 'admin_post_wprt_reopen_setup', array( $this, 'reopen_setup' ) );
		add_action( 'admin_post_wprt_install_bundled_plugin', array( $this, 'install_bundled_plugin' ) );
		add_action( 'after_switch_theme', array( $this, 'mark_fresh_activation' ) );
		add_action( 'admin_notices', array( $this, 'activation_notice' ) );
	}

	public function mark_fresh_activation() {
		update_option( 'wpraffle_theme_show_welcome', 1, false );
	}

	public function activation_notice() {
		if ( ! current_user_can( 'manage_options' ) || ! get_option( 'wpraffle_theme_show_welcome' ) ) {
			return;
		}
		$screen = get_current_screen();
		if ( $screen && 'appearance_page_wpraffle-theme-settings' === $screen->id ) {
			delete_option( 'wpraffle_theme_show_welcome' );
			return;
		}
		$url = admin_url( 'themes.php?page=wpraffle-theme-settings&tab=dashboard' );
		?>
		<div class="notice notice-info is-dismissible wprt-welcome-notice">
			<p><strong><?php esc_html_e( 'Welcome to WPRaffle Theme.', 'wpraffle-theme' ); ?></strong> <?php esc_html_e( 'Your competition-site control centre is ready.', 'wpraffle-theme' ); ?></p>
			<p><a class="button button-primary" href="<?php echo esc_url( $url ); ?>"><?php esc_html_e( 'Open Theme Setup', 'wpraffle-theme' ); ?></a></p>
		</div>
		<?php
	}

	public static function recommended_pages() {
		return array(
			'home' => array(
				'title'    => __( 'Home', 'wpraffle-theme' ),
				'slug'     => 'home',
				'template' => 'page-full-width.php',
			),
			'competitions' => array(
				'title'    => __( 'Competitions', 'wpraffle-theme' ),
				'slug'     => 'competitions',
				'template' => 'page-competitions.php',
			),
			'winners' => array(
				'title'    => __( 'Winners', 'wpraffle-theme' ),
				'slug'     => 'winners',
				'template' => 'page-winners.php',
			),
			'how-it-works' => array(
				'title'    => __( 'How It Works', 'wpraffle-theme' ),
				'slug'     => 'how-it-works',
				'template' => 'page-how-it-works.php',
			),
			'faq' => array(
				'title'    => __( 'FAQs', 'wpraffle-theme' ),
				'slug'     => 'faq',
				'template' => 'page-faq.php',
			),
			'draw-results' => array(
				'title'    => __( 'Draw Results', 'wpraffle-theme' ),
				'slug'     => 'draw-results',
				'template' => 'page-draw-results.php',
			),
			'instant-wins' => array(
				'title'    => __( 'Instant Wins', 'wpraffle-theme' ),
				'slug'     => 'instant-wins',
				'template' => 'page-instant-wins.php',
			),
			'about' => array(
				'title'    => __( 'About', 'wpraffle-theme' ),
				'slug'     => 'about',
				'template' => 'page-about.php',
			),
			'contact' => array(
				'title'    => __( 'Contact', 'wpraffle-theme' ),
				'slug'     => 'contact',
				'template' => 'page-contact.php',
			),
			'terms' => array(
				'title'    => __( 'Terms & Conditions', 'wpraffle-theme' ),
				'slug'     => 'terms-and-conditions',
				'template' => 'page-legal.php',
			),
			'privacy' => array(
				'title'    => __( 'Privacy Policy', 'wpraffle-theme' ),
				'slug'     => 'privacy-policy',
				'template' => 'page-legal.php',
			),
			'responsible-play' => array(
				'title'    => __( 'Responsible Play', 'wpraffle-theme' ),
				'slug'     => 'responsible-play',
				'template' => 'page-legal.php',
			),
		);
	}

	public static function find_page( $slug ) {
		$page = get_page_by_path( $slug, OBJECT, 'page' );
		return $page instanceof WP_Post ? $page : null;
	}

	public static function readiness() {
		$checks = array();
		$checks['woocommerce'] = array(
			'label' => __( 'WooCommerce active', 'wpraffle-theme' ),
			'ok'    => class_exists( 'WooCommerce' ),
			'hint'  => __( 'Required for products, basket, checkout and customer accounts.', 'wpraffle-theme' ),
		);
		$checks['wpraffle'] = array(
			'label' => __( 'WPRaffle active', 'wpraffle-theme' ),
			'ok'    => function_exists( 'wpraffle_theme_has_plugin' ) && wpraffle_theme_has_plugin(),
			'hint'  => __( 'Adds competition-specific functionality and live raffle data.', 'wpraffle-theme' ),
		);
		$checks['home'] = array(
			'label' => __( 'Homepage configured', 'wpraffle-theme' ),
			'ok'    => 'page' === get_option( 'show_on_front' ) && (int) get_option( 'page_on_front' ) > 0,
			'hint'  => __( 'A static homepage gives the theme full control over the landing experience.', 'wpraffle-theme' ),
		);
		$locations = get_nav_menu_locations();
		$checks['menu'] = array(
			'label' => __( 'Primary menu assigned', 'wpraffle-theme' ),
			'ok'    => ! empty( $locations['primary'] ),
			'hint'  => __( 'Create or assign a menu to the Primary Menu location.', 'wpraffle-theme' ),
		);
		$checks['logo'] = array(
			'label' => __( 'Logo configured', 'wpraffle-theme' ),
			'ok'    => (bool) get_theme_mod( 'custom_logo' ),
			'hint'  => __( 'A custom logo completes the branded header and login experience.', 'wpraffle-theme' ),
		);
		$checks['permalinks'] = array(
			'label' => __( 'Pretty permalinks enabled', 'wpraffle-theme' ),
			'ok'    => '' !== (string) get_option( 'permalink_structure' ),
			'hint'  => __( 'Recommended for clean competition and page URLs.', 'wpraffle-theme' ),
		);

		if ( function_exists( 'wpraffle_get_health_status' ) ) {
			$health = wpraffle_get_health_status();
			$checks['raffle_tables'] = array(
				'label' => __( 'WPRaffle database ready', 'wpraffle-theme' ),
				'ok'    => ! empty( $health['database']['raffles_table'] ) && ! empty( $health['database']['tickets_table'] ),
				'hint'  => __( 'Core raffle and ticket tables are present.', 'wpraffle-theme' ),
			);
			$checks['competition_content'] = array(
				'label' => __( 'Competition content available', 'wpraffle-theme' ),
				'ok'    => ! empty( $health['competitions']['total'] ),
				'hint'  => __( 'Add at least one competition so cards and templates can be previewed with real data.', 'wpraffle-theme' ),
			);
			if ( ! empty( $health['woocommerce_pages'] ) ) {
				$wc_ok = true;
				foreach ( $health['woocommerce_pages'] as $wc_page ) { $wc_ok = $wc_ok && ! empty( $wc_page['ok'] ); }
				$checks['woocommerce_pages'] = array(
					'label' => __( 'WooCommerce pages configured', 'wpraffle-theme' ),
					'ok'    => $wc_ok,
					'hint'  => __( 'Shop, basket, checkout and account pages are assigned and published.', 'wpraffle-theme' ),
				);
			}
		}
		return $checks;
	}

	/**
	 * Return onboarding/setup completion independently of ongoing site health.
	 *
	 * "Readiness" can legitimately change after launch (for example, no active
	 * competitions between draws). That must not make the onboarding wizard
	 * reappear. Setup therefore tracks only the tasks required to establish the
	 * site framework.
	 *
	 * @return array
	 */
	public static function setup_status() {
		$manual = get_option( 'wpraffle_theme_setup_complete', '' );
		if ( 'manual' === $manual ) {
			return array(
				'complete' => true,
				'mode'     => 'manual',
				'passed'   => 4,
				'total'    => 4,
				'checks'   => self::setup_checks(),
			);
		}

		$checks = self::setup_checks();
		$passed = count( array_filter( $checks, static function( $check ) {
			return ! empty( $check['ok'] );
		} ) );
		$total = count( $checks );
		$complete = $total > 0 && $passed === $total;

		// Persist auto-completion so the product remembers that onboarding was
		// successfully finished, but never downgrade it merely because site
		// health changes later.
		if ( $complete && 'auto' !== $manual ) {
			update_option( 'wpraffle_theme_setup_complete', 'auto', false );
		}
		if ( 'auto' === $manual ) {
			$complete = true;
		}

		return array(
			'complete' => $complete,
			'mode'     => $complete ? ( 'auto' === $manual ? 'auto' : 'auto' ) : '',
			'passed'   => $passed,
			'total'    => $total,
			'checks'   => $checks,
		);
	}

	/**
	 * Checks that represent completion of the guided setup wizard.
	 *
	 * These intentionally exclude volatile health checks such as competition
	 * count and database state. A launched site should not become "unsetup"
	 * because it temporarily has no active competitions.
	 *
	 * @return array
	 */
	public static function setup_checks() {
		$settings = WPRaffle_Theme_Settings::instance()->get_settings();
		$preset   = isset( $settings['preset'] ) ? sanitize_key( $settings['preset'] ) : 'default';
		$presets  = WPRaffle_Theme_Settings::get_presets();

		$core_pages_ok = true;
		$core_slugs = array( 'home', 'competitions', 'winners', 'how-it-works', 'faq' );
		foreach ( $core_slugs as $slug ) {
			if ( ! self::find_page( $slug ) ) {
				$core_pages_ok = false;
				break;
			}
		}

		$locations = get_nav_menu_locations();
		$branding_ok = (bool) get_theme_mod( 'custom_logo' ) || '' !== trim( (string) get_bloginfo( 'name' ) );

		return array(
			'branding' => array(
				'label' => __( 'Brand identity configured', 'wpraffle-theme' ),
				'ok'    => $branding_ok,
				'hint'  => __( 'A site title or custom logo is available.', 'wpraffle-theme' ),
			),
			'style' => array(
				'label' => __( 'Visual style selected', 'wpraffle-theme' ),
				'ok'    => isset( $presets[ $preset ] ),
				'hint'  => __( 'A valid WPRaffle design preset is active.', 'wpraffle-theme' ),
			),
			'pages' => array(
				'label' => __( 'Core pages configured', 'wpraffle-theme' ),
				'ok'    => $core_pages_ok && 'page' === get_option( 'show_on_front' ) && (int) get_option( 'page_on_front' ) > 0,
				'hint'  => __( 'Home, Competitions, Winners, How It Works and FAQ exist and a static homepage is selected.', 'wpraffle-theme' ),
			),
			'menu' => array(
				'label' => __( 'Primary navigation assigned', 'wpraffle-theme' ),
				'ok'    => ! empty( $locations['primary'] ),
				'hint'  => __( 'A menu is assigned to the Primary Menu location.', 'wpraffle-theme' ),
			),
		);
	}

	/**
	 * Manually mark onboarding complete.
	 */
	public function complete_setup() {
		$this->verify_action( 'wprt_complete_setup' );
		update_option( 'wpraffle_theme_setup_complete', 'manual', false );
		$this->redirect( 'dashboard', array( 'setup_complete' => 1 ) );
	}

	/**
	 * Re-open onboarding without changing configured site content.
	 */
	public function reopen_setup() {
		$this->verify_action( 'wprt_reopen_setup' );
		delete_option( 'wpraffle_theme_setup_complete' );
		$this->redirect( 'setup', array( 'setup_reopened' => 1 ) );
	}

	public static function starter_sites() {
		return array(
			'default' => array(
				'name' => __( 'WPRaffle', 'wpraffle-theme' ),
				'tagline' => __( 'Bold modern competition site', 'wpraffle-theme' ),
				'description' => __( 'The balanced all-purpose WPRaffle look with strong yellow conversion accents.', 'wpraffle-theme' ),
				'class' => 'is-default',
			),
			'golf' => array(
				'name' => __( 'Golf', 'wpraffle-theme' ),
				'tagline' => __( 'Premium golf competitions', 'wpraffle-theme' ),
				'description' => __( 'Clubhouse greens, fairway details and competition-first cards for golf prizes.', 'wpraffle-theme' ),
				'class' => 'is-golf',
			),
			'car' => array(
				'name' => __( 'Performance', 'wpraffle-theme' ),
				'tagline' => __( 'Automotive giveaway platform', 'wpraffle-theme' ),
				'description' => __( 'Graphite showroom surfaces, racing accents and telemetry-inspired competition UI.', 'wpraffle-theme' ),
				'class' => 'is-car',
			),
			'retro' => array(
				'name' => __( 'Gaming', 'wpraffle-theme' ),
				'tagline' => __( 'Gaming and arcade competitions', 'wpraffle-theme' ),
				'description' => __( 'RGB gaming energy, HUD progress and arcade details for gaming and hobby prizes.', 'wpraffle-theme' ),
				'class' => 'is-retro',
			),
			'diamond' => array(
				'name' => __( 'Diamond', 'wpraffle-theme' ),
				'tagline' => __( 'Premium black and gold', 'wpraffle-theme' ),
				'description' => __( 'Gold-led premium competition styling with strong winner and trust presentation.', 'wpraffle-theme' ),
				'class' => 'is-diamond',
			),
			'elite' => array(
				'name' => __( 'Elite', 'wpraffle-theme' ),
				'tagline' => __( 'High-ticket luxury competitions', 'wpraffle-theme' ),
				'description' => __( 'Restrained obsidian, platinum and champagne styling for homes, supercars and watches.', 'wpraffle-theme' ),
				'class' => 'is-elite',
			),
		);
	}

	private function verify_action( $action ) {
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( esc_html__( 'You do not have permission to perform this action.', 'wpraffle-theme' ) );
		}
		check_admin_referer( $action );
	}

	private function redirect( $tab, $args = array() ) {
		$url = add_query_arg( array_merge( array(
			'page' => 'wpraffle-theme-settings',
			'tab'  => $tab,
		), $args ), admin_url( 'themes.php' ) );
		wp_safe_redirect( $url );
		exit;
	}

	public function create_recommended_pages() {
		$this->verify_action( 'wprt_create_pages' );
		$created = 0;
		$ids = array();

		foreach ( self::recommended_pages() as $key => $page ) {
			$existing = self::find_page( $page['slug'] );
			if ( $existing ) {
				$ids[ $key ] = $existing->ID;
				continue;
			}
			$content = '';
			if ( in_array( $key, array( 'terms', 'privacy', 'responsible-play' ), true ) ) {
				$content = __( 'Replace this placeholder with your business-specific content. This theme does not provide legal advice or legally sufficient terms.', 'wpraffle-theme' );
			}
			$id = wp_insert_post( array(
				'post_type'    => 'page',
				'post_status'  => 'publish',
				'post_title'   => $page['title'],
				'post_name'    => $page['slug'],
				'post_content' => $content,
			), true );
			if ( ! is_wp_error( $id ) ) {
				$ids[ $key ] = $id;
				update_post_meta( $id, '_wp_page_template', $page['template'] );
				$created++;
			}
		}

		if ( ! empty( $ids['home'] ) ) {
			update_option( 'show_on_front', 'page' );
			update_option( 'page_on_front', (int) $ids['home'] );
		}
		if ( ! empty( $ids['competitions'] ) ) {
			set_theme_mod( 'diamond_page_competitions', (int) $ids['competitions'] );
		}
		if ( ! empty( $ids['winners'] ) ) {
			set_theme_mod( 'diamond_page_winners', (int) $ids['winners'] );
		}
		$this->redirect( 'setup', array( 'pages_created' => $created ) );
	}

	public function create_primary_menu() {
		$this->verify_action( 'wprt_create_menu' );
		$menu_name = __( 'WPRaffle Primary', 'wpraffle-theme' );
		$menu_obj = wp_get_nav_menu_object( $menu_name );
		$menu_id = $menu_obj ? (int) $menu_obj->term_id : wp_create_nav_menu( $menu_name );
		if ( ! is_wp_error( $menu_id ) ) {
			$desired = array( 'home', 'competitions', 'winners', 'how-it-works', 'faq' );
			$existing_items = wp_get_nav_menu_items( $menu_id );
			$existing_ids = is_array( $existing_items ) ? wp_list_pluck( $existing_items, 'object_id' ) : array();
			foreach ( $desired as $slug ) {
				$page = self::find_page( $slug );
				if ( $page && ! in_array( (string) $page->ID, array_map( 'strval', $existing_ids ), true ) ) {
					wp_update_nav_menu_item( $menu_id, 0, array(
						'menu-item-title'     => $page->post_title,
						'menu-item-object'    => 'page',
						'menu-item-object-id' => $page->ID,
						'menu-item-type'      => 'post_type',
						'menu-item-status'    => 'publish',
					) );
				}
			}
			$locations = get_theme_mod( 'nav_menu_locations', array() );
			$locations['primary'] = (int) $menu_id;
			set_theme_mod( 'nav_menu_locations', $locations );
		}
		$this->redirect( 'setup', array( 'menu_created' => 1 ) );
	}

	/**
	 * Install an allow-listed plugin bundled inside the theme.
	 *
	 * Currently used for PRO Elements. The archive path is never accepted from
	 * the request; callers may only request a known slug mapped below.
	 */
	public function install_bundled_plugin() {
		$this->verify_action( 'wprt_install_bundled_plugin' );

		$slug = isset( $_POST['plugin'] ) ? sanitize_key( wp_unslash( $_POST['plugin'] ) ) : '';
		$bundled = array(
			'pro-elements' => array(
				'archive' => WPRAFFLE_THEME_DIR . '/lib/proelements/proelements.zip',
				'plugin'  => 'pro-elements/pro-elements.php',
			),
		);

		if ( ! isset( $bundled[ $slug ] ) || ! file_exists( $bundled[ $slug ]['archive'] ) ) {
			$this->redirect( 'plugins', array( 'plugin_error' => 'missing' ) );
		}

		if ( ! current_user_can( 'install_plugins' ) ) {
			wp_die( esc_html__( 'You do not have permission to install plugins.', 'wpraffle-theme' ) );
		}

		require_once ABSPATH . 'wp-admin/includes/file.php';
		require_once ABSPATH . 'wp-admin/includes/plugin-install.php';
		require_once ABSPATH . 'wp-admin/includes/class-wp-upgrader.php';
		require_once ABSPATH . 'wp-admin/includes/plugin.php';

		WP_Filesystem();

		$skin     = new Automatic_Upgrader_Skin();
		$upgrader = new Plugin_Upgrader( $skin );
		$result   = $upgrader->install( $bundled[ $slug ]['archive'] );

		if ( is_wp_error( $result ) || ! $result ) {
			$this->redirect( 'plugins', array( 'plugin_error' => 'install' ) );
		}

		$plugin_file = $bundled[ $slug ]['plugin'];
		if ( file_exists( WP_PLUGIN_DIR . '/' . $plugin_file ) && current_user_can( 'activate_plugins' ) ) {
			$activation = activate_plugin( $plugin_file, '', false, false );
			if ( is_wp_error( $activation ) ) {
				$this->redirect( 'plugins', array( 'plugin_installed' => $slug, 'activation_error' => 1 ) );
			}
		}

		$this->redirect( 'plugins', array( 'plugin_installed' => $slug ) );
	}

	public function apply_starter() {
		$this->verify_action( 'wprt_apply_starter' );
		$preset = isset( $_POST['preset'] ) ? sanitize_key( wp_unslash( $_POST['preset'] ) ) : 'default';
		$starters = self::starter_sites();
		$presets = WPRaffle_Theme_Settings::get_presets();
		if ( ! isset( $starters[ $preset ], $presets[ $preset ] ) ) {
			$this->redirect( 'starter-sites', array( 'starter_error' => 1 ) );
		}

		$saved = get_option( WPRaffle_Theme_Settings::OPTION, array() );
		$saved = is_array( $saved ) ? $saved : array();
		$saved['preset'] = $preset;
		$saved = array_merge( $saved, $presets[ $preset ]['colours'] );

		$layout_defaults = array(
			'default' => array( 'card_ratio' => '4-3', 'card_progress' => 'thick', 'card_hover' => 'lift', 'header_scroll' => 'shrink' ),
			'golf'    => array( 'card_ratio' => '4-3', 'card_progress' => 'thick', 'card_hover' => 'lift', 'header_scroll' => 'shrink' ),
			'car'     => array( 'card_ratio' => '16-9', 'card_progress' => 'thick', 'card_hover' => 'zoom', 'header_scroll' => 'shrink' ),
			'retro'   => array( 'card_ratio' => '4-3', 'card_progress' => 'thick', 'card_hover' => 'lift', 'header_scroll' => 'shrink' ),
			'diamond' => array( 'card_ratio' => '4-3', 'card_progress' => 'thin', 'card_hover' => 'lift', 'header_scroll' => 'shrink' ),
			'elite'   => array( 'card_ratio' => '16-9', 'card_progress' => 'thin', 'card_hover' => 'lift', 'header_scroll' => 'shrink' ),
		);
		$saved = array_merge( $saved, $layout_defaults[ $preset ] );
		update_option( WPRaffle_Theme_Settings::OPTION, $saved );
		$this->redirect( 'starter-sites', array( 'starter_applied' => $preset ) );
	}

	public function export_settings() {
		$this->verify_action( 'wprt_export_settings' );
		$payload = array(
			'product'  => 'WPRaffle Theme',
			'version'  => WPRAFFLE_THEME_VERSION,
			'exported' => gmdate( 'c' ),
			'settings' => get_option( WPRaffle_Theme_Settings::OPTION, array() ),
			'theme_mods' => get_theme_mods(),
		);
		nocache_headers();
		header( 'Content-Type: application/json; charset=utf-8' );
		header( 'Content-Disposition: attachment; filename=wpraffle-theme-settings-' . gmdate( 'Y-m-d' ) . '.json' );
		echo wp_json_encode( $payload, JSON_PRETTY_PRINT ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		exit;
	}

	public function import_settings() {
		$this->verify_action( 'wprt_import_settings' );
		if (
			empty( $_FILES['settings_file']['tmp_name'] )
			|| ! is_uploaded_file( $_FILES['settings_file']['tmp_name'] )
			|| ! isset( $_FILES['settings_file']['error'] )
			|| UPLOAD_ERR_OK !== (int) $_FILES['settings_file']['error']
		) {
			$this->redirect( 'tools', array( 'import_error' => 'missing' ) );
		}
		if ( ! empty( $_FILES['settings_file']['size'] ) && (int) $_FILES['settings_file']['size'] > 1048576 ) {
			$this->redirect( 'tools', array( 'import_error' => 'size' ) );
		}
		$name = isset( $_FILES['settings_file']['name'] ) ? sanitize_file_name( wp_unslash( $_FILES['settings_file']['name'] ) ) : '';
		if ( 'json' !== strtolower( pathinfo( $name, PATHINFO_EXTENSION ) ) ) {
			$this->redirect( 'tools', array( 'import_error' => 'type' ) );
		}
		$raw = file_get_contents( $_FILES['settings_file']['tmp_name'] ); // phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents
		$data = json_decode( $raw, true );
		if ( ! is_array( $data ) || empty( $data['settings'] ) || ! is_array( $data['settings'] ) ) {
			$this->redirect( 'tools', array( 'import_error' => 'invalid' ) );
		}
		$settings = WPRaffle_Theme_Settings::sanitize_imported_settings( $data['settings'] );
		update_option( WPRaffle_Theme_Settings::OPTION, $settings );

		if ( isset( $data['theme_mods'] ) && is_array( $data['theme_mods'] ) ) {
			$allowed_mods = array(
				'custom_logo', 'nav_menu_locations', 'diamond_page_competitions', 'diamond_page_winners', 'diamond_page_charities',
				'diamond_hero_heading', 'diamond_hero_subheading', 'diamond_hero_cta_label', 'diamond_hero_bg',
			);
			foreach ( $allowed_mods as $mod ) {
				if ( ! array_key_exists( $mod, $data['theme_mods'] ) ) {
					continue;
				}
				$value = $data['theme_mods'][ $mod ];
				if ( in_array( $mod, array( 'custom_logo', 'diamond_page_competitions', 'diamond_page_winners', 'diamond_page_charities', 'diamond_hero_bg' ), true ) ) {
					$value = absint( $value );
				} elseif ( 'nav_menu_locations' === $mod ) {
					$locations = is_array( $value ) ? $value : array();
					$value = array_map( 'absint', $locations );
				} else {
					$value = sanitize_text_field( $value );
				}
				set_theme_mod( $mod, $value );
			}
		}
		$this->redirect( 'tools', array( 'imported' => 1 ) );
	}

	public static function system_status() {
		global $wp_version;
		$theme = wp_get_theme();
		$status = array(
			array( 'label' => __( 'WordPress', 'wpraffle-theme' ), 'value' => $wp_version, 'ok' => version_compare( $wp_version, '6.5', '>=' ) ),
			array( 'label' => __( 'PHP', 'wpraffle-theme' ), 'value' => PHP_VERSION, 'ok' => version_compare( PHP_VERSION, '8.1', '>=' ) ),
			array( 'label' => __( 'Theme', 'wpraffle-theme' ), 'value' => $theme->get( 'Version' ), 'ok' => true ),
			array( 'label' => __( 'WooCommerce', 'wpraffle-theme' ), 'value' => class_exists( 'WooCommerce' ) && defined( 'WC_VERSION' ) ? WC_VERSION : __( 'Not active', 'wpraffle-theme' ), 'ok' => class_exists( 'WooCommerce' ) ),
			array( 'label' => __( 'WPRaffle', 'wpraffle-theme' ), 'value' => function_exists( 'wpraffle_get_integration_manifest' ) ? ( wpraffle_get_integration_manifest()['version'] ?? __( 'Active', 'wpraffle-theme' ) ) : ( function_exists( 'wpraffle_theme_has_plugin' ) && wpraffle_theme_has_plugin() ? __( 'Legacy / active', 'wpraffle-theme' ) : __( 'Not active', 'wpraffle-theme' ) ), 'ok' => function_exists( 'wpraffle_theme_has_plugin' ) && wpraffle_theme_has_plugin() ),
			array( 'label' => __( 'Elementor', 'wpraffle-theme' ), 'value' => did_action( 'elementor/loaded' ) && defined( 'ELEMENTOR_VERSION' ) ? ELEMENTOR_VERSION : __( 'Optional / inactive', 'wpraffle-theme' ), 'ok' => true ),
			array( 'label' => __( 'HTTPS', 'wpraffle-theme' ), 'value' => is_ssl() ? __( 'Enabled', 'wpraffle-theme' ) : __( 'Not detected', 'wpraffle-theme' ), 'ok' => is_ssl() ),
			array( 'label' => __( 'Memory limit', 'wpraffle-theme' ), 'value' => WP_MEMORY_LIMIT, 'ok' => wp_convert_hr_to_bytes( WP_MEMORY_LIMIT ) >= 128 * MB_IN_BYTES ),
			array( 'label' => __( 'Permalinks', 'wpraffle-theme' ), 'value' => get_option( 'permalink_structure' ) ? get_option( 'permalink_structure' ) : __( 'Plain', 'wpraffle-theme' ), 'ok' => (bool) get_option( 'permalink_structure' ) ),
		);
		return $status;
	}

	public static function system_report_text() {
		$lines = array();
		$lines[] = 'WPRaffle Theme System Report';
		$lines[] = 'Generated: ' . gmdate( 'c' );
		$lines[] = str_repeat( '=', 36 );
		foreach ( self::system_status() as $row ) {
			$lines[] = $row['label'] . ': ' . $row['value'] . ' [' . ( $row['ok'] ? 'OK' : 'CHECK' ) . ']';
		}
		$lines[] = '';
		$lines[] = 'Readiness';
		foreach ( self::readiness() as $row ) {
			$lines[] = '- ' . $row['label'] . ': ' . ( $row['ok'] ? 'OK' : 'CHECK' );
		}
		if ( function_exists( 'wpraffle_get_health_status' ) ) {
			$lines[] = '';
			$lines[] = 'WPRaffle Health';
			$lines[] = wp_json_encode( wpraffle_get_health_status(), JSON_PRETTY_PRINT );
		}
		return implode( "\n", $lines ) . "\n";
	}

	public function download_system_report() {
		$this->verify_action( 'wprt_download_system_report' );
		nocache_headers();
		header( 'Content-Type: text/plain; charset=utf-8' );
		header( 'Content-Disposition: attachment; filename=wpraffle-system-report-' . gmdate( 'Y-m-d' ) . '.txt' );
		echo self::system_report_text(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		exit;
	}

	public function generate_child_theme() {
		$this->verify_action( 'wprt_generate_child_theme' );
		if ( ! class_exists( 'ZipArchive' ) ) {
			$this->redirect( 'tools', array( 'child_error' => 'zip' ) );
		}
		$name = isset( $_POST['child_name'] ) ? sanitize_text_field( wp_unslash( $_POST['child_name'] ) ) : __( 'WPRaffle Child', 'wpraffle-theme' );
		$slug = sanitize_title( $name );
		if ( ! $slug ) { $slug = 'wpraffle-child'; }
		$style = "/*\nTheme Name: " . $name . "\nTemplate: wpraffle-theme\nVersion: 1.0.0\nText Domain: " . $slug . "\n*/\n";
		$functions = "<?php\n/** WPRaffle child theme customisations. */\nif ( ! defined( 'ABSPATH' ) ) { exit; }\n";
		$tmp = wp_tempnam( $slug . '.zip' );
		$zip = new ZipArchive();
		if ( true !== $zip->open( $tmp, ZipArchive::CREATE | ZipArchive::OVERWRITE ) ) {
			$this->redirect( 'tools', array( 'child_error' => 'create' ) );
		}
		$zip->addFromString( $slug . '/style.css', $style );
		$zip->addFromString( $slug . '/functions.php', $functions );
		$zip->addFromString( $slug . '/README.txt', "WPRaffle Child Theme\n\nAdd safe custom PHP to functions.php and CSS to style.css.\n" );
		$zip->close();
		nocache_headers();
		header( 'Content-Type: application/zip' );
		header( 'Content-Disposition: attachment; filename=' . $slug . '.zip' );
		header( 'Content-Length: ' . filesize( $tmp ) );
		readfile( $tmp ); // phpcs:ignore WordPress.WP.AlternativeFunctions.file_system_operations_readfile
		@unlink( $tmp ); // phpcs:ignore WordPress.PHP.NoSilencedErrors.Discouraged
		exit;
	}

}
