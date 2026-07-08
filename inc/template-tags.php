<?php
/**
 * Template tags for the Diamond theme.
 *
 * These wrap common markup so templates stay readable. Most are pluggable
 * (defined only if not already overridden) so child themes or plugins can
 * replace them.
 *
 * @package Diamond
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/* ---------------------------------------------------------------------
 * Plugin page resolver.
 *
 * Resolution order for each "View all" link:
 *   1. A page manually assigned in Appearance → Theme Options (theme_mod).
 *   2. The WPRaffles plugin's wpraffle_pages[ $key ] option (if the ID is
 *      valid + published) — kept as a fallback so existing installs work.
 *   3. A published page containing the relevant shortcode (search fallback).
 *   4. The supplied fallback URL (e.g. the shop).
 *   5. Home.
 * ------------------------------------------------------------------- */

if ( ! function_exists( 'wpraffle_theme_get_page_url' ) ) {
	/**
	 * Resolve a page URL by theme assignment, plugin option, or shortcode.
	 *
	 * @param string $theme_key  Theme mod key holding a manually-assigned page ID.
	 * @param string $opt_key    Key in the plugin's wpraffle_pages option.
	 * @param string $shortcode  Shortcode to search for as a fallback.
	 * @param string $fallback   URL to use if nothing found.
	 * @return string
	 */
	function wpraffle_theme_get_page_url( $theme_key, $opt_key, $shortcode = '', $fallback = '' ) {
		// 1. Theme-assigned page (Theme Options).
		$assigned = get_theme_mod( $theme_key );
		if ( $assigned && 'publish' === get_post_status( $assigned ) ) {
			return get_permalink( (int) $assigned );
		}

		// 2. Plugin option lookup.
		$pages = get_option( 'wpraffle_pages', array() );
		if ( ! empty( $pages[ $opt_key ] ) && 'publish' === get_post_status( $pages[ $opt_key ] ) ) {
			return get_permalink( (int) $pages[ $opt_key ] );
		}

		// 3. Search for a published page containing the shortcode.
		if ( $shortcode ) {
			$found = get_posts( array(
				'post_type'      => 'page',
				'post_status'    => 'publish',
				'posts_per_page' => 1,
				's'              => $shortcode,
			) );
			if ( ! empty( $found ) ) {
				return get_permalink( $found[0]->ID );
			}
		}

		// 4. Fallback.
		if ( $fallback ) {
			return $fallback;
		}

		// 5. Home.
		return home_url( '/' );
	}
}

if ( ! function_exists( 'wpraffle_theme_competitions_url' ) ) {
	/** URL for the "View all competitions" link. Defaults to the WC shop. */
	function wpraffle_theme_competitions_url() {
		$shop = class_exists( 'WooCommerce' ) ? get_permalink( wc_get_page_id( 'shop' ) ) : '';
		return wpraffle_theme_get_page_url( 'diamond_page_competitions', 'raffles', 'raffle_list', $shop );
	}
}

if ( ! function_exists( 'wpraffle_theme_winners_url' ) ) {
	/** URL for the "View all winners" link. */
	function wpraffle_theme_winners_url() {
		return wpraffle_theme_get_page_url( 'diamond_page_winners', 'ended', 'raffle_ended_list', wpraffle_theme_competitions_url() );
	}
}

if ( ! function_exists( 'wpraffle_theme_charities_url' ) ) {
	/** URL for the "View all charities" link. */
	function wpraffle_theme_charities_url() {
		return wpraffle_theme_get_page_url( 'diamond_page_charities', 'charities', 'raffle_charities', home_url( '/' ) );
	}
}

/* ---------------------------------------------------------------------
 * Header / footer action hooks. Elementor Theme Builder replaces the
 * default markup via these hooks when a header/footer template is active.
 * ------------------------------------------------------------------- */

if ( ! function_exists( 'wpraffle_theme_header' ) ) {
	/**
	 * Fire the wpraffle_theme_header action which either prints the Theme Builder
	 * header or the native header markup.
	 */
	function wpraffle_theme_header() {
		do_action( 'wpraffle_theme_header' );
	}
}

if ( ! function_exists( 'wpraffle_theme_header_markup' ) ) {
	/**
	 * Native header markup. Hooked to wpraffle_theme_header at priority 10.
	 */
	function wpraffle_theme_header_markup() {
		get_template_part( 'template-parts/header' );
	}
	add_action( 'wpraffle_theme_header', 'wpraffle_theme_header_markup', 10 );
}

if ( ! function_exists( 'wpraffle_theme_footer' ) ) {
	/**
	 * Fire the wpraffle_theme_footer action.
	 */
	function wpraffle_theme_footer() {
		do_action( 'wpraffle_theme_footer' );
	}
}

if ( ! function_exists( 'wpraffle_theme_footer_markup' ) ) {
	/**
	 * Native footer markup. Hooked to wpraffle_theme_footer at priority 10.
	 */
	function wpraffle_theme_footer_markup() {
		get_template_part( 'template-parts/footer' );
	}
	add_action( 'wpraffle_theme_footer', 'wpraffle_theme_footer_markup', 10 );
}

/* ---------------------------------------------------------------------
 * Logo.
 * ------------------------------------------------------------------- */

if ( ! function_exists( 'wpraffle_theme_logo' ) ) {
	/**
	 * Render the site logo, falling back to the site title.
	 *
	 * @param bool $linked Wrap in a home link.
	 */
	function wpraffle_theme_logo( $linked = true ) {
		$open  = $linked ? '<a class="diamond-logo" href="' . esc_url( home_url( '/' ) ) . '">' : '<span class="diamond-logo">';
		$close = $linked ? '</a>' : '</span>';

		if ( has_custom_logo() ) {
			echo wp_kses_post( $open . get_custom_logo() . $close ); // phpcs:ignore — get_custom_logo returns safe markup.
			return;
		}

		$tagline = get_bloginfo( 'description', 'display' );
		echo wp_kses_post( $open . '<span class="diamond-logo__text">' . get_bloginfo( 'name' ) . '</span>' . $close );
		if ( $tagline ) {
			echo '<span class="diamond-logo__tagline d-none d-md-block">' . esc_html( $tagline ) . '</span>';
		}
	}
}

/* ---------------------------------------------------------------------
 * Cart link.
 * ------------------------------------------------------------------- */

if ( ! function_exists( 'wpraffle_theme_cart_link' ) ) {
	/**
	 * Header cart link with live count fragment.
	 */
	function wpraffle_theme_cart_link() {
		if ( ! class_exists( 'WooCommerce' ) ) {
			return;
		}
		$count = WC()->cart ? WC()->cart->get_cart_contents_count() : 0;
		?>
		<a class="diamond-icon-btn diamond-cart" href="<?php echo esc_url( wc_get_cart_url() ); ?>" title="<?php esc_attr_e( 'View cart', 'wpraffle-theme' ); ?>">
			<i class="fa-solid fa-bag-shopping"></i>
			<span class="diamond-cart-count"><?php echo absint( $count ); ?></span>
		</a>
		<?php
	}
}

/* ---------------------------------------------------------------------
 * Account link.
 * ------------------------------------------------------------------- */

if ( ! function_exists( 'wpraffle_theme_account_link' ) ) {
	/**
	 * Header account login / dashboard link.
	 */
	function wpraffle_theme_account_link() {
		if ( ! class_exists( 'WooCommerce' ) ) {
			return;
		}
		$url   = is_user_logged_in() ? wc_get_page_permalink( 'myaccount' ) : wc_get_page_permalink( 'myaccount' );
		$label = is_user_logged_in() ? __( 'My Account', 'wpraffle-theme' ) : __( 'Login', 'wpraffle-theme' );
		?>
		<a class="diamond-icon-btn diamond-account" href="<?php echo esc_url( $url ); ?>">
			<i class="fa-regular fa-user"></i>
			<span class="d-none d-lg-inline"><?php echo esc_html( $label ); ?></span>
		</a>
		<?php
	}
}

/* ---------------------------------------------------------------------
 * Social icons.
 * ------------------------------------------------------------------- */

if ( ! function_exists( 'wpraffle_theme_social_links' ) ) {
	/**
	 * Render social links from the Customizer.
	 */
	function wpraffle_theme_social_links() {
		$networks = array(
			'facebook'  => get_theme_mod( 'diamond_social_facebook' ),
			'instagram' => get_theme_mod( 'diamond_social_instagram' ),
			'x'         => get_theme_mod( 'diamond_social_x' ),
			'tiktok'    => get_theme_mod( 'diamond_social_tiktok' ),
			'youtube'   => get_theme_mod( 'diamond_social_youtube' ),
		);

		echo '<div class="diamond-social">';
		foreach ( $networks as $net => $url ) {
			if ( ! $url ) {
				continue;
			}
			$icon = array(
				'facebook'  => 'fa-facebook-f',
				'instagram' => 'fa-instagram',
				'x'         => 'fa-x-twitter',
				'tiktok'    => 'fa-tiktok',
				'youtube'   => 'fa-youtube',
			);
			printf(
				'<a href="%s" target="_blank" rel="noopener" aria-label="%s"><i class="fa-brands %s"></i></a>',
				esc_url( $url ),
				esc_attr( ucfirst( $net ) ),
				esc_attr( $icon[ $net ] )
			);
		}
		echo '</div>';
	}
}

/* ---------------------------------------------------------------------
 * Section heading used throughout templates.
 * ------------------------------------------------------------------- */

if ( ! function_exists( 'wpraffle_theme_section_heading' ) ) {
	/**
	 * Reusable section heading.
	 *
	 * @param string $title    Title.
	 * @param string $subtitle Optional subtitle.
	 * @param string $link     Optional "view all" link URL.
	 * @param string $link_text Optional link label.
	 */
	function wpraffle_theme_section_heading( $title, $subtitle = '', $link = '', $link_text = '' ) {
		?>
		<div class="diamond-section-heading d-flex flex-wrap align-items-end justify-content-between gap-2 mb-4">
			<div>
				<?php if ( $title ) : ?>
					<h2 class="diamond-section-heading__title"><?php echo esc_html( $title ); ?></h2>
				<?php endif; ?>
				<?php if ( $subtitle ) : ?>
					<p class="diamond-section-heading__subtitle mb-0"><?php echo esc_html( $subtitle ); ?></p>
				<?php endif; ?>
			</div>
			<?php if ( $link && $link_text ) : ?>
				<a class="diamond-section-heading__link" href="<?php echo esc_url( $link ); ?>"><?php echo esc_html( $link_text ); ?> <i class="fa-solid fa-arrow-right ms-1"></i></a>
			<?php endif; ?>
		</div>
		<?php
	}
}

/* ---------------------------------------------------------------------
 * Winner badge.
 * ------------------------------------------------------------------- */

if ( ! function_exists( 'wpraffle_theme_winner_badge' ) ) {
	/**
	 * Render a winner-type badge.
	 *
	 * @param string $type 'main' or 'instant'.
	 */
	function wpraffle_theme_winner_badge( $type = 'main' ) {
		$map = array(
			'main'    => array( 'label' => __( 'Main Prize Winner', 'wpraffle-theme' ), 'class' => 'badge-main' ),
			'instant' => array( 'label' => __( 'Instant Winner', 'wpraffle-theme' ), 'class' => 'badge-instant' ),
		);
		$badge = isset( $map[ $type ] ) ? $map[ $type ] : $map['main'];
		printf( '<span class="diamond-winner-badge %s">%s</span>', esc_attr( $badge['class'] ), esc_html( $badge['label'] ) );
	}
}

/* ---------------------------------------------------------------------
 * Copyright years.
 * ------------------------------------------------------------------- */

if ( ! function_exists( 'wpraffle_theme_copyright' ) ) {
	/**
	 * Site copyright string.
	 */
	function wpraffle_theme_copyright() {
		$year = gmdate( 'Y' );
		$name = get_bloginfo( 'name' );
		/* translators: %1$s: year, %2$s: site name. */
		printf( esc_html__( '© %1$s %2$s. All rights reserved.', 'wpraffle-theme' ), esc_html( $year ), esc_html( $name ) );
	}
}
