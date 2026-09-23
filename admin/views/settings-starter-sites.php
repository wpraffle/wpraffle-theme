<?php
if ( ! defined( 'ABSPATH' ) ) { exit; }
$starters = WPRaffle_Theme_Control_Center::starter_sites();
$settings = WPRaffle_Theme_Settings::instance()->get_settings();
$current = isset( $settings['preset'] ) ? $settings['preset'] : 'default';
if ( isset( $_GET['starter_applied'] ) ) { echo '<div class="notice notice-success inline"><p>' . esc_html__( 'Starter style applied. Your content was not changed.', 'wpraffle-theme' ) . '</p></div>'; }
?>
<section class="wpr-panel">
	<div class="wprt-card-heading"><div><span class="dashicons dashicons-layout"></span><h2><?php esc_html_e( 'Starter Sites', 'wpraffle-theme' ); ?></h2></div></div>
	<p class="wpr-panel-intro"><?php esc_html_e( 'Each starter applies the real theme preset plus sensible layout defaults. It does not import fake competitions or overwrite page content.', 'wpraffle-theme' ); ?></p>
	<div class="wprt-starter-grid">
	<?php foreach ( $starters as $slug => $starter ) : ?>
		<article class="wprt-starter-card <?php echo esc_attr( $starter['class'] ); ?> <?php echo $current === $slug ? 'is-active' : ''; ?>">
			<div class="wprt-starter-preview"><div class="wprt-mini-browser"><i></i><i></i><i></i><span></span></div><div class="wprt-mini-hero"><b><?php echo esc_html( strtoupper( $starter['name'] ) ); ?></b><em></em></div><div class="wprt-mini-cards"><i></i><i></i><i></i></div></div>
			<div class="wprt-starter-body"><div class="wprt-starter-title"><div><h3><?php echo esc_html( $starter['name'] ); ?></h3><small><?php echo esc_html( $starter['tagline'] ); ?></small></div><?php if ( $current === $slug ) : ?><span class="wprt-current-pill"><?php esc_html_e( 'Current', 'wpraffle-theme' ); ?></span><?php endif; ?></div><p><?php echo esc_html( $starter['description'] ); ?></p>
			<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>"><input type="hidden" name="action" value="wprt_apply_starter"><input type="hidden" name="preset" value="<?php echo esc_attr( $slug ); ?>"><?php wp_nonce_field( 'wprt_apply_starter' ); ?><button class="button <?php echo $current === $slug ? '' : 'button-primary'; ?>" <?php disabled( $current, $slug ); ?>><?php echo $current === $slug ? esc_html__( 'Active', 'wpraffle-theme' ) : esc_html__( 'Use This Style', 'wpraffle-theme' ); ?></button></form>
			</div>
		</article>
	<?php endforeach; ?>
	</div>
</section>
