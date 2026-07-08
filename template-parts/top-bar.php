<?php
/**
 * Top bar: instant-payouts message + secondary nav + social.
 *
 * @package Diamond
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<div class="diamond-topbar">
	<div class="container">
		<div class="diamond-topbar__inner">

			<div class="diamond-topbar__msg">
				<i class="fa-solid fa-bolt"></i>
				<span><?php echo esc_html( get_theme_mod( 'diamond_topbar_text', __( 'Instant payouts — pay yourself out 24/7 direct to your bank', 'wpraffle-theme' ) ) ); ?></span>
			</div>

			<?php if ( has_nav_menu( 'top_bar' ) ) : ?>
				<nav class="diamond-topbar__nav" aria-label="<?php esc_attr_e( 'Top Bar', 'wpraffle-theme' ); ?>">
					<?php
					wp_nav_menu( array(
						'theme_location' => 'top_bar',
						'container'      => false,
						'menu_class'     => 'diamond-topbar-menu',
						'depth'          => 1,
						'fallback_cb'    => false,
					) );
					?>
				</nav>
			<?php endif; ?>

			<div class="diamond-topbar__social"><?php wpraffle_theme_social_links(); ?></div>

		</div>
	</div>
</div>
