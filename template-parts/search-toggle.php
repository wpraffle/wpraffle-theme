<?php
/**
 * Header search toggle (collapsible search input).
 *
 * @package WPRaffle_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<button class="wpr-icon-btn wpr-search-toggle" type="button" aria-label="<?php esc_attr_e( 'Search', 'wpraffle-theme' ); ?>" aria-expanded="false">
	<i class="fa-solid fa-magnifying-glass"></i>
</button>
