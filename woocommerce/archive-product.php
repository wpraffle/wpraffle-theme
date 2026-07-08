<?php
/**
 * The Template for displaying product archives, including the main shop page
 * which is the "Active Competitions" listing.
 *
 * @package Diamond
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header( 'shop' );
?>
<header class="diamond-shop-hero section--dark" style="padding:3.5rem 0;">
	<div class="container">
		<span class="eyebrow" style="color:#fff;opacity:.85;"><?php esc_html_e( 'Live Now', 'wpraffle-theme' ); ?></span>
		<?php if ( apply_filters( 'woocommerce_show_page_title', true ) ) : ?>
			<h1 class="mb-2" style="color:#fff;font-size:clamp(1.8rem,4vw,2.75rem);">
				<?php woocommerce_page_title(); ?>
			</h1>
		<?php endif; ?>
		<p class="mb-0" style="color:rgba(255,255,255,.85);"><?php esc_html_e( 'Limited tickets, guaranteed draws, instant payouts.', 'wpraffle-theme' ); ?></p>
	</div>
</header>

<?php
/**
 * Hook: woocommerce_before_main_content.
 *
 * WPRaffle_Theme_WooCommerce substitutes the theme wrapper here.
 */
do_action( 'woocommerce_before_main_content' );
?>

<?php if ( woocommerce_product_loop() ) : ?>

	<?php do_action( 'woocommerce_before_shop_loop' ); ?>

	<div class="diamond-active-grid">
		<?php
		woocommerce_product_loop_start();
		if ( wc_get_loop_prop( 'total' ) ) {
			while ( have_posts() ) {
				the_post();
				/**
				 * Hook: woocommerce_shop_loop.
				 */
				do_action( 'woocommerce_shop_loop' );
				wc_get_template_part( 'content', 'product' );
			}
		}
		woocommerce_product_loop_end();
		?>
	</div>

	<?php
	do_action( 'woocommerce_after_shop_loop' );

else :
	/**
	 * Hook: woocommerce_no_products_found.
	 */
	do_action( 'woocommerce_no_products_found' );
endif;
?>

<?php
/**
 * Hook: woocommerce_after_main_content.
 */
do_action( 'woocommerce_after_main_content' );
?>
<?php
get_footer( 'shop' );
