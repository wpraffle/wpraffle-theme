<?php
/**
 * WooCommerce integration.
 *
 * Removes the default WooCommerce wrappers and substitutes Bootstrap-friendly
 * ones so the shop/archive layout matches the rest of the theme. Also declutters
 * the single product page and styles the My Account area.
 *
 * @package Diamond
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class WPRaffle_Theme_WooCommerce
 */
final class WPRaffle_Theme_WooCommerce {

	/** @var WPRaffle_Theme_WooCommerce|null */
	private static $instance = null;

	/**
	 * Get the singleton.
	 *
	 * @return WPRaffle_Theme_WooCommerce
	 */
	public static function instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * Only hook in when WooCommerce is active.
	 */
	private function __construct() {
		if ( ! class_exists( 'WooCommerce' ) ) {
			return;
		}

		// Swap the WC content wrappers for theme wrappers.
		remove_action( 'woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10 );
		remove_action( 'woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10 );
		add_action( 'woocommerce_before_main_content', array( $this, 'output_content_wrapper' ), 10 );
		add_action( 'woocommerce_after_main_content', array( $this, 'output_content_wrapper_end' ), 10 );

		// Tidy the archive header.
		add_action( 'woocommerce_show_page_title', '__return_false' );
		remove_action( 'woocommerce_before_shop_loop', 'woocommerce_result_count', 20 );
		remove_action( 'woocommerce_before_shop_loop', 'woocommerce_catalog_ordering', 30 );

		// Slim down the single product page (the WPRaffles plugin owns the raffle summary).
		remove_action( 'woocommerce_before_single_product_summary', 'woocommerce_show_product_sale_flash', 10 );

		// Mini-cart fragment.
		add_filter( 'woocommerce_add_to_cart_fragments', array( $this, 'cart_link_fragment' ) );

		// Products per row & per page to match Paragon's 3-col grid.
		add_filter( 'loop_shop_columns', array( $this, 'shop_columns' ), 99 );
		add_filter( 'loop_shop_per_page', array( $this, 'shop_per_page' ), 99 );

		// Account menu item order — keep My Raffles visible high up if the plugin added it.
		add_filter( 'woocommerce_account_menu_items', array( $this, 'account_menu_items' ), 99 );
	}

	/**
	 * Opening wrapper.
	 *
	 * Uses a flat container (no Bootstrap .row/.col nesting) so the shop grid
	 * breathes the same way the homepage sections do.
	 */
	public function output_content_wrapper() {
		echo '<div id="primary" class="content-area diamond-shop"><main id="main" class="site-main"><div class="container diamond-shop__inner">';
	}

	/**
	 * Closing wrapper.
	 */
	public function output_content_wrapper_end() {
		echo '</div></main></div>';
	}

	/**
	 * Shop columns.
	 *
	 * @return int
	 */
	public function shop_columns() {
		return 3;
	}

	/**
	 * Products per page.
	 *
	 * @return int
	 */
	public function shop_per_page() {
		return 12;
	}

	/**
	 * Re-order My Account menu items: keep Dashboard, then My Raffles, then the rest.
	 *
	 * @param array $items Menu items.
	 * @return array
	 */
	public function account_menu_items( $items ) {
		if ( isset( $items['my-raffles'] ) ) {
			$my_raffles = array( 'my-raffles' => $items['my-raffles'] );
			unset( $items['my-raffles'] );
			$dashboard = array_slice( $items, 0, 1, true );
			$rest      = array_slice( $items, 1, null, true );
			$items     = array_merge( $dashboard, $my_raffles, $rest );
		}
		return $items;
	}

	/**
	 * Cart link fragment for the header mini-cart count.
	 *
	 * @param array $fragments Fragments.
	 * @return array
	 */
	public function cart_link_fragment( $fragments ) {
		$fragments['span.diamond-cart-count'] = '<span class="diamond-cart-count">' . absint( WC()->cart->get_cart_contents_count() ) . '</span>';
		return $fragments;
	}
}
