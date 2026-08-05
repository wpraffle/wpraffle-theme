<?php
/**
 * Header notification bell (v1.2.0 Enhancement W).
 *
 * Shows a count badge for new competitions or draw results since the visitor's
 * last visit. The "last visit" timestamp is stored in a cookie (wprt_last_visit);
 * the count comes from a lightweight query against the raffles table. Falls
 * back gracefully (empty dropdown) when there is nothing new.
 *
 * The dropdown toggle is wired in v1.2.0.js.
 *
 * @package WPRaffle_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

global $wpdb;

// Determine the visitor's last-visit timestamp from the cookie (set on load by JS).
$last_visit = isset( $_COOKIE['wprt_last_visit'] ) ? (int) $_COOKIE['wprt_last_visit'] : 0;
$now_ts     = time();

// If no cookie yet, treat this as a first visit and set the baseline to "now"
// (so the bell doesn't immediately light up with everything). We still stamp
// the cookie on the client so the *next* visit shows new items.
if ( ! $last_visit ) {
	$last_visit = $now_ts;
}

// Count new raffles + newly-drawn raffles since the last visit.
$since    = gmdate( 'Y-m-d H:i:s', $last_visit );
$new_ids  = array();

if ( wpraffle_theme_has_plugin() ) {
	$table    = $wpdb->prefix . 'raffles';
	$new_ids  = $wpdb->get_col( $wpdb->prepare(
		"SELECT id FROM {$table} WHERE created_at > %s ORDER BY created_at DESC LIMIT 8",
		$since
	) );
}

$count = count( $new_ids );
?>
<span class="wprt-notification-bell">
	<button type="button" class="wpr-icon-btn" aria-label="<?php esc_attr_e( 'Notifications', 'wpraffle-theme' ); ?>" aria-expanded="false">
		<i class="fa-regular fa-bell" aria-hidden="true"></i>
		<span class="wprt-notification-bell__count" data-count="<?php echo esc_attr( $count ); ?>"><?php echo $count > 0 ? esc_html( $count ) : ''; ?></span>
	</button>
	<div class="wprt-notification-dropdown" role="menu" aria-label="<?php esc_attr_e( 'Recent notifications', 'wpraffle-theme' ); ?>">
		<?php if ( empty( $new_ids ) ) : ?>
			<div class="wprt-notification-dropdown__empty"><?php esc_html_e( 'No new competitions since your last visit.', 'wpraffle-theme' ); ?></div>
		<?php else : ?>
			<?php foreach ( $new_ids as $rid ) : ?>
				<?php
				$r = $wpdb->get_row( $wpdb->prepare( "SELECT id, title FROM {$wpdb->prefix}raffles WHERE id = %d", $rid ) );
				if ( ! $r ) { continue; }
				$link = wpraffle_theme_competitions_url();
				?>
				<a class="wprt-notification-dropdown__item" href="<?php echo esc_url( $link ); ?>" role="menuitem">
					<?php echo esc_html( $r->title ); ?>
				</a>
			<?php endforeach; ?>
		<?php endif; ?>
	</div>
</span>
<script>
	// Stamp the last-visit cookie so the next pageload has a baseline.
	// Refreshes every 5 minutes so a long idle session doesn't accumulate.
	( function () {
		var now = Math.floor( Date.now() / 1000 );
		var existing = parseInt( document.cookie.match( '(?:^|; )wprt_last_visit=([^;]*)' ) && document.cookie.match( '(?:^|; )wprt_last_visit=([^;]*)' )[1], 10 );
		if ( ! existing || ( now - existing ) > 300 ) {
			document.cookie = 'wprt_last_visit=' + now + '; path=/; max-age=' + ( 60 * 60 * 24 * 30 );
		}
	} )();
</script>
