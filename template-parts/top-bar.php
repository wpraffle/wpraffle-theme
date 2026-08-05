<?php
/**
 * Top bar: instant-payouts message + secondary nav + social.
 *
 * @package WPRaffle_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<div class="wpr-topbar">
	<div class="container">
		<div class="wpr-topbar__inner">

			<div class="wpr-topbar__msg">
				<i class="fa-solid fa-bolt"></i>
				<span><?php echo esc_html( get_theme_mod( 'wpr_topbar_text', __( 'Instant payouts — pay yourself out 24/7 direct to your bank', 'wpraffle-theme' ) ) ); ?></span>
			</div>

			<?php if ( has_nav_menu( 'top_bar' ) ) : ?>
				<nav class="wpr-topbar__nav" aria-label="<?php esc_attr_e( 'Top Bar', 'wpraffle-theme' ); ?>">
					<?php
					wp_nav_menu( array(
						'theme_location' => 'top_bar',
						'container'      => false,
						'menu_class'     => 'wpr-topbar-menu',
						'depth'          => 1,
						'fallback_cb'    => false,
					) );
					?>
				</nav>
			<?php endif; ?>

			<div class="wpr-topbar__social"><?php wpraffle_theme_social_links(); ?></div>

		</div>
	</div>
</div>
