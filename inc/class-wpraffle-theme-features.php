<?php
/**
 * WPRaffle Theme v1.1.0 features integration.
 *
 * Handles: login page styling, maintenance mode, social proof AJAX,
 * dashboard widget, age gate cookie, import/export, mega menu walker.
 *
 * @package WPRaffle_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class WPRaffle_Theme_Features
 */
final class WPRaffle_Theme_Features {

	/** @var WPRaffle_Theme_Features|null */
	private static $instance = null;

	/**
	 * Get the singleton.
	 *
	 * @return WPRaffle_Theme_Features
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
		// Login page styling.
		add_action( 'login_enqueue_scripts', array( $this, 'login_styles' ) );
		add_filter( 'login_headerurl', array( $this, 'login_logo_url' ) );

		// Maintenance mode.
		add_action( 'template_redirect', array( $this, 'maintenance_mode' ), 0 );

		// Social proof AJAX.
		add_action( 'wp_ajax_wprt_social_proof', array( $this, 'ajax_social_proof' ) );
		add_action( 'wp_ajax_nopriv_wprt_social_proof', array( $this, 'ajax_social_proof' ) );

		// v1.2.0 — Category filter AJAX for the Active Competitions grid.
		add_action( 'wp_ajax_wprt_filter_raffles', array( $this, 'ajax_filter_raffles' ) );
		add_action( 'wp_ajax_nopriv_wprt_filter_raffles', array( $this, 'ajax_filter_raffles' ) );

		// v1.2.0 — Quick-view modal AJAX (returns single-raffle content fragment).
		add_action( 'wp_ajax_wprt_quickview', array( $this, 'ajax_quickview' ) );
		add_action( 'wp_ajax_nopriv_wprt_quickview', array( $this, 'ajax_quickview' ) );

		// v1.2.0 — Conditional footer inclusions (cookie consent, Trustpilot, chat button, share, sticky bar).
		add_action( 'wp_footer', array( $this, 'render_footer_extras' ), 20 );

		// v1.2.0 — Load the Trustpilot TrustBox bootstrap script when configured.
		add_action( 'wp_footer', array( $this, 'maybe_enqueue_trustpilot' ) );

		// Dashboard widget.
		add_action( 'wp_dashboard_setup', array( $this, 'register_dashboard_widget' ) );

		// Import/Export handlers.
		add_action( 'admin_init', array( $this, 'handle_export' ) );
		add_action( 'admin_init', array( $this, 'handle_import' ) );

		// Mega menu walker filter.
		add_filter( 'wp_nav_menu_args', array( $this, 'mega_menu_walker' ) );
	}

	/* ---------------------------------------------------------------------
	 * Login page styling
	 * ------------------------------------------------------------------- */

	/**
	 * Inject custom CSS on the WP login page.
	 */
	public function login_styles() {
		$s = WPRaffle_Theme_Settings::instance()->get_settings();
		$css = '';

		if ( $s['login_bg'] ) {
			$css .= 'body.login{background-image:url(' . esc_url( $s['login_bg'] ) . ') !important;background-size:cover !important;background-position:center !important;}';
			$css .= 'body.login::before{content:"";position:fixed;inset:0;background:rgba(0,0,0,0.4);z-index:0;}';
			$css .= '#login{position:relative;z-index:1;}';
		}
		if ( $s['login_logo'] ) {
			$css .= '#login h1 a{background-image:url(' . esc_url( $s['login_logo'] . ') !important;background-size:contain !important;width:200px !important;height:80px !important;' ) . ';}';
		}
		$css .= '.wp-core-ui .button-primary{background:' . $s['accent'] . ';border-color:' . $s['accent'] . ';}';
		$css .= '.wp-core-ui .button-primary:hover{background:' . WPRaffle_Theme_Settings::darken( $s['accent'], 12 ) . ';border-color:' . WPRaffle_Theme_Settings::darken( $s['accent'], 12 ) . ';}';
		$css .= 'body.login a{color:' . $s['accent'] . ';}';

		if ( $s['login_custom_css'] ) {
			$css .= $s['login_custom_css'];
		}

		if ( $css ) {
			echo '<style>' . $css . '</style>'; // phpcs:ignore WordPress.Security.OutputNotEscaped
		}
	}

	/**
	 * Point the login logo link to the site home.
	 *
	 * @return string
	 */
	public function login_logo_url() {
		return home_url( '/' );
	}

	/* ---------------------------------------------------------------------
	 * Maintenance mode
	 * ------------------------------------------------------------------- */

	/**
	 * Redirect non-logged-in users to a maintenance page.
	 */
	public function maintenance_mode() {
		$s = WPRaffle_Theme_Settings::instance()->get_settings();
		if ( 'on' !== $s['maintenance'] ) {
			return;
		}
		if ( current_user_can( 'edit_themes' ) || is_user_logged_in() ) {
			return;
		}
		// Allow login page + admin + AJAX through.
		if ( is_admin() || ( function_exists( 'wp_doing_ajax' ) && wp_doing_ajax() ) || false !== strpos( $_SERVER['REQUEST_URI'], 'wp-login.php' ) ) {
			return;
		}

		status_header( 503 );
		nocache_headers();
		include WPRAFFLE_THEME_DIR . '/template-parts/maintenance.php';
		exit;
	}

	/* ---------------------------------------------------------------------
	 * Social proof (AJAX)
	 * ------------------------------------------------------------------- */

	/**
	 * AJAX endpoint: returns a recent purchase for the social proof toast.
	 */
	public function ajax_social_proof() {
		check_ajax_referer( 'wpr_nonce', 'nonce' );

		if ( ! wpraffle_theme_has_plugin() ) {
			wp_send_json_error();
		}

		global $wpdb;
		// Fetch a random recent purchase. Column names must match the actual
		// raffle_purchases schema: payment_status ('completed'), purchase_date
		// (NOT created_at — that column does not exist on this table and triggers
		// a DB error on every request, which on a single-threaded PHP-FPM
		// (Local by Flywheel) saturates the process and freezes the page).
		$purchase = $wpdb->get_row( $wpdb->prepare(
			"SELECT p.buyer_name, p.purchase_date, r.title
			 FROM {$wpdb->prefix}raffle_purchases p
			 JOIN {$wpdb->prefix}raffles r ON p.raffle_id = r.id
			 WHERE p.payment_status = 'completed'
			 ORDER BY p.purchase_date DESC
			 LIMIT 1 OFFSET %d",
			wp_rand( 0, 20 )
		) );

		if ( ! $purchase ) {
			wp_send_json_error();
		}

		$name = '';
		if ( $purchase->buyer_name ) {
			// Show first name + initial for privacy.
			$parts = explode( ' ', trim( $purchase->buyer_name ) );
			$name  = $parts[0];
			if ( isset( $parts[1] ) ) {
				$name .= ' ' . strtoupper( substr( $parts[1], 0, 1 ) ) . '.';
			}
		}

		// Guard human_time_diff against a missing/unparseable date.
		$time_str = __( 'recently', 'wpraffle-theme' );
		if ( ! empty( $purchase->purchase_date ) && strtotime( $purchase->purchase_date ) ) {
			$time_str = human_time_diff( strtotime( $purchase->purchase_date ), current_time( 'timestamp' ) );
		}

		wp_send_json_success( array(
			'name'  => $name ?: __( 'Someone', 'wpraffle-theme' ),
			'prize' => $purchase->title,
			'time'  => $time_str,
		) );
	}

	/* ---------------------------------------------------------------------
	 * Dashboard widget
	 * ------------------------------------------------------------------- */

	/**
	 * Register the dashboard widget.
	 */
	public function register_dashboard_widget() {
		wp_add_dashboard_widget( 'wprt_dashboard', __( 'WPRaffle Theme', 'wpraffle-theme' ), array( $this, 'render_dashboard_widget' ) );
	}

	/**
	 * Render the dashboard widget content.
	 */
	public function render_dashboard_widget() {
		$s        = WPRaffle_Theme_Settings::instance()->get_settings();
		$sections = isset( $s['sections'] ) ? $s['sections'] : array();
		$enabled  = 0;
		foreach ( $sections as $sec ) {
			if ( ! empty( $sec['enabled'] ) ) {
				$enabled++;
			}
		}
		$latest = get_transient( 'wpraffle_theme_latest_version' );
		$update = $latest && version_compare( $latest, WPRAFFLE_THEME_VERSION, '>' );
		?>
		<div class="wprt-dash-widget">
			<p>
				<strong><?php esc_html_e( 'Version', 'wpraffle-theme' ); ?>:</strong> v<?php echo esc_html( WPRAFFLE_THEME_VERSION); ?>
				<?php if ( $update ) : ?>
					&mdash; <a href="<?php echo esc_url( admin_url( 'themes.php?page=wpraffle-theme-settings&tab=advanced' ) ); ?>" style="color:#d63638;font-weight:600;"><?php esc_html_e( 'Update available!', 'wpraffle-theme' ); ?></a>
				<?php else : ?>
					&mdash; <span style="color:#00a32a;">✓ <?php esc_html_e( 'Up to date', 'wpraffle-theme' ); ?></span>
				<?php endif; ?>
			</p>
			<p><strong><?php esc_html_e( 'Homepage sections', 'wpraffle-theme' ); ?>:</strong> <?php echo esc_html( $enabled ); ?> / <?php echo esc_html( count( $sections ) ); ?> enabled</p>
			<p>
				<a href="<?php echo esc_url( admin_url( 'themes.php?page=wpraffle-theme-settings' ) ); ?>" class="button button-primary"><?php esc_html_e( 'Theme Options', 'wpraffle-theme' ); ?></a>
			</p>
		</div>
		<?php
	}

	/* ---------------------------------------------------------------------
	 * Import / Export
	 * ------------------------------------------------------------------- */

	/**
	 * Handle the export action (downloads a JSON file).
	 */
	public function handle_export() {
		if ( ! isset( $_GET['wprt_export'] ) || ! current_user_can( 'manage_options' ) ) {
			return;
		}
		check_admin_referer( 'wprt_export' );

		$settings = get_option( 'wpraffle_theme_settings', array() );
		$json     = wp_json_encode( $settings );

		header( 'Content-Type: application/json' );
		header( 'Content-Disposition: attachment; filename="wpraffle-theme-settings.json"' );
		header( 'Cache-Control: no-cache' );
		echo $json; // phpcs:ignore WordPress.Security.OutputNotEscaped
		exit;
	}

	/**
	 * Handle the import action (uploads + applies a JSON file).
	 */
	public function handle_import() {
		if ( ! isset( $_POST['wprt_import_submit'] ) || ! current_user_can( 'manage_options' ) ) {
			return;
		}
		check_admin_referer( 'wprt_import' );

		if ( empty( $_FILES['wprt_import_file']['tmp_name'] ) ) {
			wp_safe_redirect( admin_url( 'themes.php?page=wpraffle-theme-settings&tab=advanced&import=error' ) );
			exit;
		}

		$json = file_get_contents( $_FILES['wprt_import_file']['tmp_name'] ); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput
		$data = json_decode( $json, true );

		if ( ! is_array( $data ) ) {
			wp_safe_redirect( admin_url( 'themes.php?page=wpraffle-theme-settings&tab=advanced&import=error' ) );
			exit;
		}

		update_option( 'wpraffle_theme_settings', $data );
		wp_safe_redirect( admin_url( 'themes.php?page=wpraffle-theme-settings&tab=advanced&import=success' ) );
		exit;
	}

	/* ---------------------------------------------------------------------
	 * Mega menu walker
	 * ------------------------------------------------------------------- */

	/**
	 * Swap in a mega-menu-aware walker when mega menu is enabled.
	 *
	 * @param array $args Nav menu args.
	 * @return array
	 */
	public function mega_menu_walker( $args ) {
		$s = WPRaffle_Theme_Settings::instance()->get_settings();
		if ( 'on' !== $s['mega_menu'] ) {
			return $args;
		}
		// Only apply to the primary theme location.
		if ( ! isset( $args['theme_location'] ) || 'primary' !== $args['theme_location'] ) {
			return $args;
		}
		// Add a body class via the menu class so CSS can target it.
		if ( ! isset( $args['menu_class'] ) ) {
			$args['menu_class'] = '';
		}
		$args['menu_class'] .= ' wprt-mega-menu';
		return $args;
	}

	/* ---------------------------------------------------------------------
	 * v1.2.0 — Enhancements
	 * ------------------------------------------------------------------- */

	/**
	 * AJAX endpoint for the Active Competitions category filter.
	 *
	 * Returns the rendered `[raffle_list category="X"]` markup for the
	 * requested term. Falls back to an empty-state message if nothing matches.
	 */
	public function ajax_filter_raffles() {
		check_ajax_referer( 'wpr_nonce', 'nonce' );

		$category = isset( $_POST['category'] ) ? sanitize_text_field( wp_unslash( $_POST['category'] ) ) : '';

		if ( ! wpraffle_theme_has_plugin() ) {
			wp_send_json( '' );
		}

		// The plugin (v1.3.1+) supports a `category` attribute on [raffle_list].
		// If the plugin predates v1.3.1 the attribute is silently ignored and the
		// full list is returned — acceptable graceful degradation.
		$out = do_shortcode( '[raffle_list status="active" columns="3" per_page="9" category="' . esc_attr( $category ) . '"]' );
		wp_send_json( $out );
	}

	/**
	 * AJAX endpoint for the Quick-view modal (Enhancement U).
	 *
	 * Returns a JSON fragment with the raffle's title, image, price, countdown,
	 * progress, and entry URL — enough to populate the modal without a full
	 * page navigation. Intentionally does NOT render the entry form itself
	 * (that needs the full single-raffle page's nonce/scripts); the modal's CTA
	 * links through to the page.
	 */
	public function ajax_quickview() {
		check_ajax_referer( 'wpr_nonce', 'nonce' );

		$raffle_id = isset( $_POST['raffle_id'] ) ? absint( $_POST['raffle_id'] ) : 0;
		if ( ! $raffle_id || ! wpraffle_theme_has_plugin() ) {
			wp_send_json_error();
		}

		global $wpdb;
		$r = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM {$wpdb->prefix}raffles WHERE id = %d LIMIT 1", $raffle_id ) );
		if ( ! $r ) {
			wp_send_json_error();
		}

		$product   = $r->wc_product_id ? wc_get_product( $r->wc_product_id ) : null;
		$enter_url = $product ? $product->get_permalink() : wpraffle_theme_competitions_url();
		$image_id  = $product ? $product->get_image_id() : 0;
		$image_url = $image_id ? wp_get_attachment_image_url( $image_id, 'wpr-card-wide' ) : '';
		$price     = $product ? $product->get_price_html() : '';

		$total = (int) ( $r->total_tickets ?: 0 );
		$sold  = (int) ( $r->sold_tickets ?: 0 );
		$pct   = $total > 0 ? min( 100, round( ( $sold / $total ) * 100 ) ) : 0;
		$remain = max( 0, $total - $sold );

		wp_send_json_success( array(
			'title'      => $r->title,
			'image'      => $image_url,
			'price'      => wp_kses_post( $price ),
			'enter_url'  => $enter_url,
			'pct'        => $pct,
			'sold'       => $sold,
			'remain'     => $remain,
			'draw_iso'   => $r->draw_date ? gmdate( 'Y-m-d\TH:i:s\Z', strtotime( $r->draw_date ) ) : '',
			'draw_label' => $r->draw_date ? date_i18n( get_option( 'date_format' ), strtotime( $r->draw_date ) ) : '',
		) );
	}

	/**
	 * Render the v1.2.0 footer extras: cookie-consent bar, footer TrustBox
	 * slot, Instagram feed, chat button, share row, sticky entry bar, and the
	 * notification bell — each gated by its Theme Options toggle.
	 */
	public function render_footer_extras() {
		$s = WPRaffle_Theme_Settings::instance()->get_settings();

		// Cookie consent bar.
		if ( 'on' === $s['cookie_consent'] ) {
			get_template_part( 'template-parts/cookie-consent' );
		}

		// Footer Trustpilot slot.
		$tp_id = ! empty( $s['trustpilot_business_id'] ) ? $s['trustpilot_business_id'] : '';
		if ( in_array( $s['trustpilot_position'], array( 'footer', 'both' ), true ) && $tp_id ) {
			echo '<div class="wprt-trustpilot-slot wprt-trustpilot-slot--footer"><div class="trustpilot-widget" data-locale="en-GB" data-template-id="5419b6a0b044e7f3bfc0cadd" data-businessunit-id="' . esc_attr( $tp_id ) . '" data-style-height="80px" data-style-width="100%"><a href="https://uk.trustpilot.com/review/' . esc_attr( wp_parse_url( home_url(), PHP_URL_HOST ) ) . '" target="_blank" rel="noopener">' . esc_html__( 'Trustpilot', 'wpraffle-theme' ) . '</a></div></div>';
		}

		// Chat button (Enhancement T). Third-party scripts (Tawk/Crisp) are injected here.
		$this->render_chat_button( $s );

		// Sticky entry bar + notification bell — only on single product/raffle pages.
		if ( is_singular( 'product' ) || ( function_exists( 'is_product' ) && is_product() ) ) {
			get_template_part( 'template-parts/sticky-entry-bar' );
		}

		// Quick-view modal — only on pages that show competition grids/cards.
		$show_quickview = wpraffle_theme_has_plugin() && (
			is_front_page() ||
			( function_exists( 'is_shop' ) && is_shop() ) ||
			( function_exists( 'is_product_category' ) && is_product_category() )
		);
		if ( $show_quickview ) {
			get_template_part( 'template-parts/quickview-modal' );
		}

		// JS i18n strings for v1.2.0.js (back-to-top label, quick-view labels, etc.).
		echo '<script>window.wpraffleTheme = { i18n: ' . wp_json_encode( array(
			'backToTop'  => __( 'Back to top', 'wpraffle-theme' ),
			'quickView'  => __( 'Quick view', 'wpraffle-theme' ),
			'enterNow'   => __( 'Enter Now', 'wpraffle-theme' ),
			'loading'    => __( 'Loading…', 'wpraffle-theme' ),
			'loadError'  => __( 'Could not load this competition.', 'wpraffle-theme' ),
		) ) . ' };</script>';
	}

	/**
	 * Output the floating chat button (Enhancement T).
	 *
	 * @param array $s Theme settings.
	 */
	private function render_chat_button( $s ) {
		$provider = isset( $s['chat_provider'] ) ? $s['chat_provider'] : 'off';
		if ( 'off' === $provider ) {
			return;
		}

		if ( 'whatsapp' === $provider && ! empty( $s['chat_number'] ) ) {
			$num = preg_replace( '/[^\d]/', '', $s['chat_number'] );
			echo '<a class="wprt-chat-button" href="https://wa.me/' . esc_attr( $num ) . '" target="_blank" rel="noopener" aria-label="' . esc_attr__( 'Chat on WhatsApp', 'wpraffle-theme' ) . '"><i class="fa-brands fa-whatsapp" aria-hidden="true"></i></a>';
			return;
		}

		if ( 'tawk' === $provider && ! empty( $s['chat_id'] ) ) {
			echo '<a class="wprt-chat-button wprt-chat-button--tawk" href="#" aria-label="' . esc_attr__( 'Live chat', 'wpraffle-theme' ) . '"><i class="fa-solid fa-comment-dots" aria-hidden="true"></i></a>';
			// Tawk.to embed. The $chat_id is "property_id/widget_id".
			echo "<script type=\"text/javascript\">var Tawk_API=Tawk_API||{},Tawk_LoadStart=new Date();(function(){var s1=document.createElement('script'),s0=document.getElementsByTagName('script')[0];s1.async=true;s1.src='https://embed.tawk.to/" . esc_js( $s['chat_id'] ) . "/default';s1.charset='UTF-8';s1.setAttribute('crossorigin','*');s0.parentNode.insertBefore(s1,s0);})();</script>";
			return;
		}

		if ( 'crisp' === $provider && ! empty( $s['chat_id'] ) ) {
			echo '<a class="wprt-chat-button wprt-chat-button--crisp" href="#" aria-label="' . esc_attr__( 'Live chat', 'wpraffle-theme' ) . '"><i class="fa-solid fa-comment-dots" aria-hidden="true"></i></a>';
			echo "<script type=\"text/javascript\">window.\$crisp=[];window.CRISP_WEBSITE_ID='" . esc_js( $s['chat_id'] ) . "';(function(){d=document;s=d.createElement('script');s.src='https://client.crisp.chat/l.js';s.async=1;d.getElementsByTagName('head')[0].appendChild(s);})();</script>";
		}
	}

	/**
	 * Load the Trustpilot TrustBox bootstrap script once, when a TrustBox is
	 * actually rendered (avoids loading a third-party tracker site-wide).
	 */
	public function maybe_enqueue_trustpilot() {
		$s = WPRaffle_Theme_Settings::instance()->get_settings();
		if ( empty( $s['trustpilot_business_id'] ) ) {
			return;
		}
		if ( ! in_array( $s['trustpilot_position'], array( 'hero', 'footer', 'both' ), true ) ) {
			return;
		}
		// Check a TrustBox slot was actually printed (quick DOM check via output isn't possible here;
		// the slot is always rendered when the position is set, so this is sufficient).
		echo '<script type="text/javascript" src="//widget.trustpilot.com/bootstrap/v5/tp.widget.bootstrap.min.js" async></script>';
	}
}
