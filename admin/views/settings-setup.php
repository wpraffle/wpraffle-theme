<?php
if ( ! defined( 'ABSPATH' ) ) { exit; }
$checks = WPRaffle_Theme_Control_Center::readiness();
$pages = WPRaffle_Theme_Control_Center::recommended_pages();
if ( isset( $_GET['pages_created'] ) ) { echo '<div class="notice notice-success inline"><p>' . esc_html( absint( $_GET['pages_created'] ) ) . ' ' . esc_html__( 'recommended pages created.', 'wpraffle-theme' ) . '</p></div>'; }
if ( isset( $_GET['menu_created'] ) ) { echo '<div class="notice notice-success inline"><p>' . esc_html__( 'Primary navigation created and assigned.', 'wpraffle-theme' ) . '</p></div>'; }

$setup_status = WPRaffle_Theme_Control_Center::setup_status();
$setup_complete = ! empty( $setup_status['complete'] );
?>

<?php if ( $setup_complete ) : ?>
<div class="wprt-setup-status-banner is-complete">
	<span class="dashicons dashicons-yes-alt" aria-hidden="true"></span>
	<div>
		<strong><?php esc_html_e( 'Setup complete', 'wpraffle-theme' ); ?></strong>
		<span><?php esc_html_e( 'You can still use any setup action below. The Dashboard will no longer prompt you to complete onboarding.', 'wpraffle-theme' ); ?></span>
	</div>
</div>
<?php else : ?>
<div class="wprt-setup-status-banner">
	<div>
		<strong><?php printf( esc_html__( '%1$d of %2$d setup requirements complete', 'wpraffle-theme' ), absint( $setup_status['passed'] ), absint( $setup_status['total'] ) ); ?></strong>
		<span><?php esc_html_e( 'This will auto-complete when all four setup requirements pass, or you can mark it complete manually.', 'wpraffle-theme' ); ?></span>
	</div>
	<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
		<input type="hidden" name="action" value="wprt_complete_setup">
		<?php wp_nonce_field( 'wprt_complete_setup' ); ?>
		<button type="submit" class="button"><?php esc_html_e( 'Mark Complete', 'wpraffle-theme' ); ?></button>
	</form>
</div>
<?php endif; ?>

<section class="wpr-panel">
	<div class="wprt-card-heading"><div><span class="dashicons dashicons-admin-settings"></span><h2><?php esc_html_e( 'Guided site setup', 'wpraffle-theme' ); ?></h2></div></div>
	<p class="wpr-panel-intro"><?php esc_html_e( 'Use these safe setup actions to create the site framework. Existing pages are never overwritten.', 'wpraffle-theme' ); ?></p>
	<div class="wprt-setup-steps">
		<div class="wprt-setup-step"><span>1</span><div><h3><?php esc_html_e( 'Branding', 'wpraffle-theme' ); ?></h3><p><?php esc_html_e( 'Upload your logo and site icon using WordPress Site Identity.', 'wpraffle-theme' ); ?></p><a class="button" href="<?php echo esc_url( admin_url( 'customize.php?autofocus[section]=title_tagline' ) ); ?>"><?php esc_html_e( 'Open Site Identity', 'wpraffle-theme' ); ?></a></div></div>
		<div class="wprt-setup-step"><span>2</span><div><h3><?php esc_html_e( 'Choose your visual direction', 'wpraffle-theme' ); ?></h3><p><?php esc_html_e( 'Pick one of the six included starter styles and apply its recommended card/layout defaults.', 'wpraffle-theme' ); ?></p><a class="button" href="<?php echo esc_url( admin_url( 'themes.php?page=wpraffle-theme-settings&tab=starter-sites' ) ); ?>"><?php esc_html_e( 'Choose Starter Site', 'wpraffle-theme' ); ?></a></div></div>
		<div class="wprt-setup-step"><span>3</span><div><h3><?php esc_html_e( 'Create core pages', 'wpraffle-theme' ); ?></h3><p><?php esc_html_e( 'Creates Home, Competitions, Winners, How It Works, FAQ, Draw Results, Contact and placeholder legal pages, then sets Home as the static homepage.', 'wpraffle-theme' ); ?></p>
			<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>"><input type="hidden" name="action" value="wprt_create_pages"><?php wp_nonce_field( 'wprt_create_pages' ); ?><button class="button button-primary"><?php esc_html_e( 'Create Recommended Pages', 'wpraffle-theme' ); ?></button></form>
		</div></div>
		<div class="wprt-setup-step"><span>4</span><div><h3><?php esc_html_e( 'Create primary navigation', 'wpraffle-theme' ); ?></h3><p><?php esc_html_e( 'Builds a clean Home / Competitions / Winners / How It Works / FAQ menu from pages that already exist and assigns it to Primary Menu.', 'wpraffle-theme' ); ?></p>
			<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>"><input type="hidden" name="action" value="wprt_create_menu"><?php wp_nonce_field( 'wprt_create_menu' ); ?><button class="button"><?php esc_html_e( 'Create Primary Menu', 'wpraffle-theme' ); ?></button></form>
		</div></div>
	</div>
</section>

<section class="wpr-panel">
	<div class="wprt-card-heading"><div><span class="dashicons dashicons-media-document"></span><h2><?php esc_html_e( 'Core pages', 'wpraffle-theme' ); ?></h2></div></div>
	<div class="wprt-page-grid">
	<?php foreach ( $pages as $page ) : $found = WPRaffle_Theme_Control_Center::find_page( $page['slug'] ); ?>
		<div class="wprt-page-row <?php echo $found ? 'is-ready' : ''; ?>"><span class="dashicons dashicons-<?php echo $found ? 'yes-alt' : 'minus'; ?>"></span><div><strong><?php echo esc_html( $page['title'] ); ?></strong><small>/<?php echo esc_html( $page['slug'] ); ?>/</small></div><?php if ( $found ) : ?><a href="<?php echo esc_url( get_edit_post_link( $found->ID ) ); ?>"><?php esc_html_e( 'Edit', 'wpraffle-theme' ); ?></a><?php endif; ?></div>
	<?php endforeach; ?>
	</div>
</section>
