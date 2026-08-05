<?php
/**
 * Empty state content.
 *
 * @package WPRaffle_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<div class="wpr-card">
	<div class="wpr-card__body text-center py-5">
		<i class="fa-regular fa-folder-open fa-3x text-muted mb-3"></i>
		<h2 class="wpr-card__title"><?php esc_html_e( 'Nothing found', 'wpraffle-theme' ); ?></h2>
		<p class="text-muted"><?php esc_html_e( 'No content matches your search. Try again with different keywords.', 'wpraffle-theme' ); ?></p>
		<?php get_search_form(); ?>
	</div>
</div>
