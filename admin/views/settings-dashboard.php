<?php
if ( ! defined( 'ABSPATH' ) ) { exit; }
$checks = WPRaffle_Theme_Control_Center::readiness();
$passed = count( array_filter( $checks, static function( $check ) { return ! empty( $check['ok'] ); } ) );
$total = count( $checks );
$settings = WPRaffle_Theme_Settings::instance()->get_settings();
$preset = isset( $settings['preset'] ) ? $settings['preset'] : 'default';
$starters = WPRaffle_Theme_Control_Center::starter_sites();
$setup_url = admin_url( 'themes.php?page=wpraffle-theme-settings&tab=setup' );
$setup_status = WPRaffle_Theme_Control_Center::setup_status();
$setup_complete = ! empty( $setup_status['complete'] );

?>
<?php if ( $setup_complete ) : ?>
<section class="wprt-hero-card wprt-hero-card--complete">
	<div>
		<span class="wprt-eyebrow"><?php esc_html_e( 'WPRaffle Control Centre', 'wpraffle-theme' ); ?></span>
		<h2><span class="dashicons dashicons-yes-alt" aria-hidden="true"></span> <?php esc_html_e( 'Site setup complete.', 'wpraffle-theme' ); ?></h2>
		<p><?php esc_html_e( 'Your WPRaffle site framework is configured. The readiness panel below will continue to flag operational issues without reopening onboarding.', 'wpraffle-theme' ); ?></p>
		<div class="wprt-button-row">
			<a class="button button-primary button-hero" href="<?php echo esc_url( home_url( '/' ) ); ?>" target="_blank" rel="noopener"><?php esc_html_e( 'View Site', 'wpraffle-theme' ); ?></a>
			<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" class="wprt-inline-form">
				<input type="hidden" name="action" value="wprt_reopen_setup">
				<?php wp_nonce_field( 'wprt_reopen_setup' ); ?>
				<button class="button button-secondary button-hero" type="submit"><?php esc_html_e( 'Reopen Setup', 'wpraffle-theme' ); ?></button>
			</form>
		</div>
	</div>
	<div class="wprt-setup-complete-mark" aria-label="<?php esc_attr_e( 'Setup complete', 'wpraffle-theme' ); ?>">
		<span class="dashicons dashicons-yes-alt"></span>
		<strong><?php esc_html_e( 'Ready', 'wpraffle-theme' ); ?></strong>
		<small><?php echo 'manual' === $setup_status['mode'] ? esc_html__( 'Marked complete', 'wpraffle-theme' ) : esc_html__( 'Auto completed', 'wpraffle-theme' ); ?></small>
	</div>
</section>
<?php else : ?>
<section class="wprt-hero-card">
	<div>
		<span class="wprt-eyebrow"><?php esc_html_e( 'WPRaffle Control Centre', 'wpraffle-theme' ); ?></span>
		<h2><?php esc_html_e( 'Build a competition site that feels premium.', 'wpraffle-theme' ); ?></h2>
		<p><?php esc_html_e( 'Configure the essentials, choose a complete visual direction and launch the core pages from one place. Setup will automatically complete when the required framework is ready.', 'wpraffle-theme' ); ?></p>
		<div class="wprt-button-row">
			<a class="button button-primary button-hero" href="<?php echo esc_url( $setup_url ); ?>"><?php esc_html_e( 'Continue Site Setup', 'wpraffle-theme' ); ?></a>
			<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" class="wprt-inline-form">
				<input type="hidden" name="action" value="wprt_complete_setup">
				<?php wp_nonce_field( 'wprt_complete_setup' ); ?>
				<button class="button button-secondary button-hero" type="submit"><?php esc_html_e( 'Mark Setup Complete', 'wpraffle-theme' ); ?></button>
			</form>
		</div>
	</div>
	<div class="wprt-readiness-ring" style="--progress:<?php echo esc_attr( round( ( $setup_status['passed'] / max( 1, $setup_status['total'] ) ) * 100 ) ); ?>%;">
		<strong><?php echo esc_html( $setup_status['passed'] ); ?>/<?php echo esc_html( $setup_status['total'] ); ?></strong>
		<span><?php esc_html_e( 'setup', 'wpraffle-theme' ); ?></span>
	</div>
</section>
<?php endif; ?>

<div class="wprt-dashboard-grid">
	<section class="wpr-panel wprt-dashboard-card">
		<div class="wprt-card-heading"><div><span class="dashicons dashicons-yes-alt"></span><h2><?php esc_html_e( 'Site readiness', 'wpraffle-theme' ); ?></h2></div><a href="<?php echo esc_url( $setup_complete ? admin_url( 'themes.php?page=wpraffle-theme-settings&tab=status' ) : $setup_url ); ?>"><?php echo esc_html( $setup_complete ? __( 'View status', 'wpraffle-theme' ) : __( 'Fix setup', 'wpraffle-theme' ) ); ?> →</a></div>
		<div class="wprt-check-list">
			<?php foreach ( $checks as $check ) : ?>
			<div class="wprt-check <?php echo $check['ok'] ? 'is-ok' : 'is-warning'; ?>">
				<span class="dashicons dashicons-<?php echo $check['ok'] ? 'yes-alt' : 'warning'; ?>"></span>
				<div><strong><?php echo esc_html( $check['label'] ); ?></strong><small><?php echo esc_html( $check['hint'] ); ?></small></div>
			</div>
			<?php endforeach; ?>
		</div>
	</section>

	<section class="wpr-panel wprt-dashboard-card">
		<div class="wprt-card-heading"><div><span class="dashicons dashicons-admin-appearance"></span><h2><?php esc_html_e( 'Current style', 'wpraffle-theme' ); ?></h2></div><a href="<?php echo esc_url( admin_url( 'themes.php?page=wpraffle-theme-settings&tab=starter-sites' ) ); ?>"><?php esc_html_e( 'Change', 'wpraffle-theme' ); ?> →</a></div>
		<div class="wprt-mini-style <?php echo esc_attr( isset( $starters[ $preset ]['class'] ) ? $starters[ $preset ]['class'] : 'is-default' ); ?>"><div class="wprt-mini-browser"><i></i><i></i><i></i><span></span></div><div class="wprt-mini-hero"><b>WIN BIG.</b><em></em></div><div class="wprt-mini-cards"><i></i><i></i><i></i></div></div>
		<h3><?php echo esc_html( isset( $starters[ $preset ]['name'] ) ? $starters[ $preset ]['name'] : ucfirst( $preset ) ); ?></h3>
		<p><?php echo esc_html( isset( $starters[ $preset ]['description'] ) ? $starters[ $preset ]['description'] : '' ); ?></p>
	</section>
</div>

<section class="wpr-panel">
	<div class="wprt-card-heading"><div><span class="dashicons dashicons-admin-generic"></span><h2><?php esc_html_e( 'Quick actions', 'wpraffle-theme' ); ?></h2></div></div>
	<div class="wprt-quick-actions">
		<a href="<?php echo esc_url( admin_url( 'themes.php?page=wpraffle-theme-settings&tab=starter-sites' ) ); ?>"><span class="dashicons dashicons-layout"></span><strong><?php esc_html_e( 'Starter Sites', 'wpraffle-theme' ); ?></strong><small><?php esc_html_e( 'Apply a complete design direction.', 'wpraffle-theme' ); ?></small></a>
		<a href="<?php echo esc_url( admin_url( 'themes.php?page=wpraffle-theme-settings&tab=homepage' ) ); ?>"><span class="dashicons dashicons-welcome-widgets-menus"></span><strong><?php esc_html_e( 'Homepage Builder', 'wpraffle-theme' ); ?></strong><small><?php esc_html_e( 'Order and enable native sections.', 'wpraffle-theme' ); ?></small></a>
		<a href="<?php echo esc_url( admin_url( 'themes.php?page=wpraffle-theme-settings&tab=templates' ) ); ?>"><span class="dashicons dashicons-screenoptions"></span><strong><?php esc_html_e( 'Template Library', 'wpraffle-theme' ); ?></strong><small><?php esc_html_e( 'Native and Elementor templates.', 'wpraffle-theme' ); ?></small></a>
		<a href="<?php echo esc_url( admin_url( 'themes.php?page=wpraffle-theme-settings&tab=status' ) ); ?>"><span class="dashicons dashicons-dashboard"></span><strong><?php esc_html_e( 'System Status', 'wpraffle-theme' ); ?></strong><small><?php esc_html_e( 'Check site and dependency health.', 'wpraffle-theme' ); ?></small></a>
	</div>
</section>
<?php if ( function_exists( 'wpraffle_get_integration_manifest' ) ) : $wpr_manifest = wpraffle_get_integration_manifest(); ?>
<section class="wpr-panel">
  <div class="wprt-card-heading"><div><span class="dashicons dashicons-admin-plugins"></span><h2><?php esc_html_e( 'Plugin Integration', 'wpraffle-theme' ); ?></h2></div><span class="wprt-ready-pill"><?php echo esc_html( 'WPRaffle ' . ( $wpr_manifest['version'] ?? '' ) ); ?></span></div>
  <p class="wpr-panel-intro"><?php printf( esc_html__( 'Theme and plugin are using the coordinated integration API. %d Elementor widgets are available from WPRaffle.', 'wpraffle-theme' ), absint( $wpr_manifest['features']['elementor_widgets'] ?? 0 ) ); ?></p>
  <p><a class="button" href="<?php echo esc_url( admin_url( 'themes.php?page=wpraffle-theme-settings&tab=plugins' ) ); ?>"><?php esc_html_e( 'Manage Recommended Plugins', 'wpraffle-theme' ); ?></a></p>
</section>
<?php endif; ?>
