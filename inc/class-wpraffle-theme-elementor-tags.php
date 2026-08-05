<?php
/**
 * WPRaffle Theme — Elementor dynamic tags.
 *
 * Surfaces plugin data (current product's raffle id, ticket price, draw date,
 * and the global charity total raised) as dynamic tags so any native Elementor
 * widget can bind to live values instead of hardcoding them in JSON templates.
 *
 * Registered from WPRaffle_Theme_Elementor via `elementor/dynamic_tags/register`.
 *
 * @package WPRaffle_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Base class for the theme's raffle data tags.
 *
 * Extends the plugin's tag pattern but lives in the theme so it works on
 * templates that don't include a raffle widget (header/footer/homepage).
 */
abstract class WPRaffle_Theme_Tag_Base extends \Elementor\Base_Data_Tag {

	/**
	 * Tag group (matches the plugin's group when present, else standalone).
	 */
	public function get_group() {
		return 'wpraffle-theme';
	}

	/**
	 * Resolve the raffle row for the current product, or an explicit id.
	 *
	 * @param int $explicit_id Optional explicit raffle id.
	 * @return object|false
	 */
	protected function resolve_raffle( $explicit_id = 0 ) {
		if ( $explicit_id ) {
			if ( function_exists( 'wpraffle_get_raffle' ) ) {
				return wpraffle_get_raffle( $explicit_id ) ?: false;
			}
			global $wpdb;
			return $wpdb->get_row( $wpdb->prepare( "SELECT * FROM {$wpdb->prefix}raffles WHERE id = %d", $explicit_id ) ) ?: false; // phpcs:ignore WordPress.DB
		}
		$current_id = get_the_ID();
		if ( ! $current_id ) {
			return false;
		}
		$raffle_id = (int) get_post_meta( $current_id, '_raffle_id', true );
		if ( ! $raffle_id ) {
			return false;
		}
		if ( function_exists( 'wpraffle_get_raffle' ) ) {
			return wpraffle_get_raffle( $raffle_id ) ?: false;
		}
		global $wpdb;
		return $wpdb->get_row( $wpdb->prepare( "SELECT * FROM {$wpdb->prefix}raffles WHERE id = %d", $raffle_id ) ) ?: false; // phpcs:ignore WordPress.DB
	}
}

/**
 * Tag: the current product's linked raffle ID.
 */
class WPRaffle_Theme_Tag_Raffle_Id extends WPRaffle_Theme_Tag_Base {
	public function get_name()  { return 'wprt-raffle-id'; }
	public function get_title() { return __( 'Raffle ID (current product)', 'wpraffle-theme' ); }
	public function get_categories() {
		return array( \Elementor\Modules\DynamicTags\Module::TEXT_CATEGORY, \Elementor\Modules\DynamicTags\Module::NUMBER_CATEGORY );
	}
	protected function register_controls() {}
	protected function get_value( array $options = array() ) {
		$raffle = $this->resolve_raffle();
		return $raffle ? (int) $raffle->id : '';
	}
}

/**
 * Tag: the current product's raffle ticket price.
 */
class WPRaffle_Theme_Tag_Ticket_Price extends WPRaffle_Theme_Tag_Base {
	public function get_name()  { return 'wprt-ticket-price'; }
	public function get_title() { return __( 'Ticket Price (current raffle)', 'wpraffle-theme' ); }
	public function get_categories() {
		return array( \Elementor\Modules\DynamicTags\Module::TEXT_CATEGORY );
	}
	protected function register_controls() {}
	protected function get_value( array $options = array() ) {
		$raffle = $this->resolve_raffle();
		if ( ! $raffle ) {
			return '';
		}
		return function_exists( 'wpr_price' ) ? wpr_price( $raffle->id ) : $raffle->ticket_price;
	}
}

/**
 * Tag: the current product's raffle draw date.
 */
class WPRaffle_Theme_Tag_Draw_Date extends WPRaffle_Theme_Tag_Base {
	public function get_name()  { return 'wprt-draw-date'; }
	public function get_title() { return __( 'Draw Date (current raffle)', 'wpraffle-theme' ); }
	public function get_categories() {
		return array( \Elementor\Modules\DynamicTags\Module::TEXT_CATEGORY );
	}
	protected function register_controls() {}
	protected function get_value( array $options = array() ) {
		$raffle = $this->resolve_raffle();
		if ( ! $raffle || ! $raffle->draw_date ) {
			return '';
		}
		return mysql2date( get_option( 'date_format' ), $raffle->draw_date );
	}
}

/**
 * Tag: the global charity total raised across all raffles.
 */
class WPRaffle_Theme_Tag_Charity_Total extends WPRaffle_Theme_Tag_Base {
	public function get_name()  { return 'wprt-charity-total'; }
	public function get_title() { return __( 'Charity Total Raised', 'wpraffle-theme' ); }
	public function get_categories() {
		return array( \Elementor\Modules\DynamicTags\Module::TEXT_CATEGORY );
	}
	protected function register_controls() {}
	protected function get_value( array $options = array() ) {
		// Delegate to the theme's own total calculator (sums active charities
		// via the plugin's Raffle_Charity::calculate_total_raised_for_charity()).
		if ( class_exists( 'WPRaffle_Theme_Integration' ) && method_exists( 'WPRaffle_Theme_Integration', 'get_total_raised' ) ) {
			return WPRaffle_Theme_Integration::get_total_raised();
		}
		global $wpdb;
		$total = (float) $wpdb->get_var( "SELECT SUM(total_raised) FROM {$wpdb->prefix}raffle_charities" ); // phpcs:ignore WordPress.DB
		return function_exists( 'wpr_price' ) ? wpr_price( $total, 0 ) : number_format_i18n( (float) $total );
	}
}
