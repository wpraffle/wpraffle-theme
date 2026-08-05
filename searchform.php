<?php
/**
 * Custom search form.
 *
 * @package WPRaffle_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<form role="search" method="get" class="wpr-search-form" action="<?php echo esc_url( home_url( '/' ) ); ?>">
	<label class="screen-reader-text" for="wpr-s"><?php esc_html_e( 'Search for:', 'wpraffle-theme' ); ?></label>
	<div class="input-group">
		<input type="search" id="wpr-s" class="form-control" placeholder="<?php esc_attr_e( 'Search…', 'wpraffle-theme' ); ?>" value="<?php echo esc_attr( get_search_query() ); ?>" name="s" />
		<button type="submit" class="btn btn-accent"><i class="fa-solid fa-magnifying-glass"></i> <span class="d-none d-md-inline"><?php esc_html_e( 'Search', 'wpraffle-theme' ); ?></span></button>
	</div>
</form>
