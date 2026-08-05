<?php
/**
 * Native header: top bar + sticky nav header.
 *
 * @package WPRaffle_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<header id="masthead" class="wpr-header">

	<?php get_template_part( 'template-parts/top-bar' ); ?>

	<div class="container">
		<div class="wpr-header__inner">

			<div class="wpr-header__left">
				<button class="wpr-menu-toggle" aria-label="<?php esc_attr_e( 'Toggle menu', 'wpraffle-theme' ); ?>" aria-expanded="false">
					<i class="fa-solid fa-bars"></i>
				</button>
				<?php wpraffle_theme_logo(); ?>
			</div>

			<nav class="wpr-nav" aria-label="<?php esc_attr_e( 'Primary', 'wpraffle-theme' ); ?>">
				<?php
				if ( has_nav_menu( 'primary' ) ) {
					wp_nav_menu( array(
						'theme_location' => 'primary',
						'container'      => false,
						'menu_class'     => 'menu wpr-primary-menu',
						'depth'          => 2,
						'fallback_cb'    => false,
					) );
				} else {
					echo '<ul>';
					if ( class_exists( 'WooCommerce' ) ) {
						echo '<li><a href="' . esc_url( get_permalink( wc_get_page_id( 'shop' ) ) ) . '">' . esc_html__( 'Competitions', 'wpraffle-theme' ) . '</a></li>';
						echo '<li><a href="' . esc_url( get_permalink( wc_get_page_id( 'myaccount' ) ) ) . '">' . esc_html__( 'My Account', 'wpraffle-theme' ) . '</a></li>';
					}
					echo '</ul>';
				}
				?>
			</nav>

			<div class="wpr-header__actions">
				<?php if ( class_exists( 'WooCommerce' ) ) : ?>
					<?php get_template_part( 'template-parts/search-toggle' ); ?>
					<?php
					// v1.2.0 Enhancement W — header notification bell.
					// Shows a count of new competitions/draw results since the last
					// visit (stored in a cookie). The dropdown lists recent items.
					if ( wpraffle_theme_has_plugin() ) :
						get_template_part( 'template-parts/notification-bell' );
					endif;
					?>
					<?php wpraffle_theme_account_link(); ?>
					<?php wpraffle_theme_cart_link(); ?>
				<?php endif; ?>
			</div>

		</div>
	</div>
</header><!-- #masthead -->
