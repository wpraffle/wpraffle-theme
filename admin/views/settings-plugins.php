<?php
/**
 * Recommended Plugins — permanent Control Centre screen.
 *
 * @package WPRaffle_Theme
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }

$plugins = array(
	'woocommerce' => array(
		'name'       => 'WooCommerce',
		'candidates' => array( 'woocommerce/woocommerce.php' ),
		'required'   => true,
		'install'    => admin_url( 'plugin-install.php?s=WooCommerce&tab=search&type=term' ),
	),
	'wpraffle' => array(
		'name'       => 'WPRaffle',
		'candidates' => array(
			'wpraffle/raffle-system.php',
			'wpraffle/wpraffle.php', // legacy/future compatibility.
		),
		'required'   => false,
		'install'    => 'https://wpraffle.dev/',
	),
	'elementor' => array(
		'name'       => 'Elementor',
		'candidates' => array( 'elementor/elementor.php' ),
		'required'   => false,
		'install'    => admin_url( 'plugin-install.php?s=Elementor&tab=search&type=term' ),
	),
	'pro-elements' => array(
		'name'       => 'PRO Elements',
		'candidates' => array(
			'pro-elements/pro-elements.php',
			'proelements/pro-elements.php',
			'proelements/proelements.php',
		),
		'required'   => false,
		'bundled'    => file_exists( WPRAFFLE_THEME_DIR . '/lib/proelements/proelements.zip' ),
	),
);

require_once ABSPATH . 'wp-admin/includes/plugin.php';

if ( isset( $_GET['plugin_installed'] ) ) {
	$installed_slug = sanitize_key( wp_unslash( $_GET['plugin_installed'] ) ); // phpcs:ignore WordPress.Security.NonceVerification.Recommended
	echo '<div class="notice notice-success inline"><p>' . esc_html( sprintf( __( '%s installed successfully.', 'wpraffle-theme' ), 'pro-elements' === $installed_slug ? 'PRO Elements' : $installed_slug ) ) . '</p></div>';
}
if ( isset( $_GET['plugin_error'] ) ) {
	echo '<div class="notice notice-error inline"><p>' . esc_html__( 'The bundled plugin could not be installed. Check filesystem permissions and try again.', 'wpraffle-theme' ) . '</p></div>';
}
if ( isset( $_GET['activation_error'] ) ) {
	echo '<div class="notice notice-warning inline"><p>' . esc_html__( 'The plugin installed, but WordPress could not activate it automatically. Activate it from Plugins.', 'wpraffle-theme' ) . '</p></div>';
}
?>
<section class="wpr-panel">
	<div class="wprt-card-heading">
		<div><span class="dashicons dashicons-admin-plugins"></span><h2><?php esc_html_e( 'Recommended Plugins', 'wpraffle-theme' ); ?></h2></div>
	</div>
	<p class="wpr-panel-intro"><?php esc_html_e( 'These integrations extend WPRaffle. This screen is always available, even when WordPress/TGMPA has no pending plugin actions.', 'wpraffle-theme' ); ?></p>

	<div class="wprt-plugin-grid">
	<?php foreach ( $plugins as $slug => $plugin ) :
		$plugin_file = '';
		foreach ( $plugin['candidates'] as $candidate ) {
			if ( file_exists( WP_PLUGIN_DIR . '/' . $candidate ) ) {
				$plugin_file = $candidate;
				break;
			}
		}

		// WPRaffle can also be positively identified through its public 1.4.0
		// integration API even if a site uses a non-standard plugin directory.
		$api_active = 'wpraffle' === $slug && (
			function_exists( 'wpraffle_get_integration_manifest' )
			|| function_exists( 'wpraffle_get_health_status' )
		);

		$installed = '' !== $plugin_file || $api_active;
		$active    = $api_active || ( $plugin_file && is_plugin_active( $plugin_file ) );
		$status    = $active ? 'active' : ( $installed ? 'inactive' : 'missing' );
		?>
		<article class="wprt-plugin-card is-<?php echo esc_attr( $status ); ?>">
			<div class="wprt-plugin-card__icon"><span class="dashicons dashicons-admin-plugins"></span></div>
			<div class="wprt-plugin-card__body">
				<div class="wprt-plugin-card__title">
					<h3><?php echo esc_html( $plugin['name'] ); ?></h3>
					<?php if ( $plugin['required'] ) : ?><span class="wprt-required-pill"><?php esc_html_e( 'Required', 'wpraffle-theme' ); ?></span><?php endif; ?>
					<?php if ( 'pro-elements' === $slug && ! empty( $plugin['bundled'] ) ) : ?><span class="wprt-bundled-pill"><?php esc_html_e( 'Bundled', 'wpraffle-theme' ); ?></span><?php endif; ?>
				</div>
				<p class="wprt-plugin-status">
					<span class="dashicons dashicons-<?php echo $active ? 'yes-alt' : ( $installed ? 'warning' : 'minus' ); ?>"></span>
					<?php
					if ( $active ) {
						esc_html_e( 'Active', 'wpraffle-theme' );
					} elseif ( $installed ) {
						esc_html_e( 'Installed but inactive', 'wpraffle-theme' );
					} else {
						esc_html_e( 'Not installed', 'wpraffle-theme' );
					}
					?>
				</p>
				<div class="wprt-plugin-card__actions">
				<?php if ( $active ) : ?>
					<a class="button" href="<?php echo esc_url( admin_url( 'plugins.php' ) ); ?>"><?php esc_html_e( 'Manage', 'wpraffle-theme' ); ?></a>
				<?php elseif ( $installed ) : ?>
					<a class="button button-primary" href="<?php echo esc_url( admin_url( 'plugins.php' ) ); ?>"><?php esc_html_e( 'Activate in Plugins', 'wpraffle-theme' ); ?></a>
				<?php elseif ( 'pro-elements' === $slug && ! empty( $plugin['bundled'] ) ) : ?>
					<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" class="wprt-inline-form">
						<input type="hidden" name="action" value="wprt_install_bundled_plugin">
						<input type="hidden" name="plugin" value="pro-elements">
						<?php wp_nonce_field( 'wprt_install_bundled_plugin' ); ?>
						<button type="submit" class="button button-primary"><?php esc_html_e( 'Install Bundled PRO Elements', 'wpraffle-theme' ); ?></button>
					</form>
				<?php else : ?>
					<a class="button button-primary" href="<?php echo esc_url( $plugin['install'] ); ?>" <?php echo 0 === strpos( $plugin['install'], 'http' ) && false === strpos( $plugin['install'], admin_url() ) ? 'target="_blank" rel="noopener noreferrer"' : ''; ?>><?php esc_html_e( 'Install / Get Plugin', 'wpraffle-theme' ); ?></a>
				<?php endif; ?>
				</div>
			</div>
		</article>
	<?php endforeach; ?>
	</div>

	<div class="wprt-plugin-manager-footer">
		<a class="button" href="<?php echo esc_url( admin_url( 'plugins.php' ) ); ?>"><?php esc_html_e( 'Open WordPress Plugins', 'wpraffle-theme' ); ?></a>
		<?php if ( class_exists( 'TGM_Plugin_Activation' ) ) :
			$tgmpa = TGM_Plugin_Activation::$instance;
			$tgmpa_url = $tgmpa && ! empty( $tgmpa->menu ) ? admin_url( 'themes.php?page=' . $tgmpa->menu ) : '';
			if ( $tgmpa_url ) :
			?>
			<a class="button" href="<?php echo esc_url( $tgmpa_url ); ?>"><?php esc_html_e( 'Open Installer', 'wpraffle-theme' ); ?></a>
			<?php endif;
		endif; ?>
	</div>
</section>
