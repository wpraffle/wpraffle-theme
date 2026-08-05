<?php
/**
 * Active Competitions section — renders the live raffle grid via the WPRaffles
 * [raffle_list] shortcode when the plugin is active. Falls back to the WC shop
 * loop URL prompt otherwise.
 *
 * v1.2.0 additions:
 *  - Enhancement L: category filter tabs (uses plugin v1.3.1's new `category`
 *    attribute on [raffle_list]). Renders only if raffle_category terms exist.
 *
 * Note on Enhancement Q (skeleton loading): the original v1.2.0 build shipped a
 * skeleton-placeholder grid, but [raffle_list] renders synchronously server-side
 * so there is no async gap to bridge — the skeleton only ever flashed or (worse)
 * got stuck visible when its hide-script was gated behind the tabs check. It has
 * been removed; the shortcode output appears instantly.
 *
 * @package WPRaffle_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Category tabs — only if the plugin is active AND terms exist.
$show_tabs = wpraffle_theme_has_plugin() && taxonomy_exists( 'raffle_category' );
$terms     = $show_tabs ? get_terms( array(
	'taxonomy'   => 'raffle_category',
	'hide_empty' => true,
) ) : array();
$show_tabs = $show_tabs && ! is_wp_error( $terms ) && ! empty( $terms );
?>
<section id="active" class="section wprt-reveal">
	<div class="container">
		<?php
		wpraffle_theme_section_heading(
			get_theme_mod( 'wpr_active_title', __( 'Active Competitions', 'wpraffle-theme' ) ),
			get_theme_mod( 'wpr_active_subtitle', __( 'Enter now — limited tickets, guaranteed draws.', 'wpraffle-theme' ) ),
			wpraffle_theme_competitions_url(),
			__( 'View all competitions', 'wpraffle-theme' )
		);

		// Enhancement L: category filter tabs.
		if ( $show_tabs ) :
			?>
			<div class="wprt-filter-tabs" role="tablist" aria-label="<?php esc_attr_e( 'Filter competitions by category', 'wpraffle-theme' ); ?>">
				<button type="button" class="wprt-filter-tabs__btn is-active" data-wprt-filter="" role="tab" aria-selected="true"><?php esc_html_e( 'All', 'wpraffle-theme' ); ?></button>
				<?php foreach ( $terms as $term ) : ?>
					<button type="button" class="wprt-filter-tabs__btn" data-wprt-filter="<?php echo esc_attr( $term->slug ); ?>" role="tab" aria-selected="false"><?php echo esc_html( $term->name ); ?></button>
				<?php endforeach; ?>
			</div>
			<?php
		endif;
		?>

		<div class="wpr-active-grid" id="wprt-active-grid">
			<?php if ( wpraffle_theme_has_plugin() ) : ?>
				<?php
				// The grid renders synchronously via the shortcode. data-default caches
				// the un-filtered markup so the "All" tab can restore it without a
				// server round-trip.
				$default = do_shortcode( '[raffle_list status="active" columns="3" per_page="9"]' );
				echo '<div class="wprt-active-list">' . $default . '</div>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped — shortcode output is sanitised by the plugin.
				?>
			<?php elseif ( class_exists( 'WooCommerce' ) ) : ?>
				<?php
				$loop = new WP_Query( array(
					'post_type'      => 'product',
					'posts_per_page' => 9,
					'meta_key'       => '_price', // phpcs:ignore WordPress.DB.SlowDBQuery
					'orderby'        => 'meta_value_num',
					'order'          => 'ASC',
				) );
				if ( $loop->have_posts() ) :
					echo '<div class="row g-4">';
					while ( $loop->have_posts() ) :
						$loop->the_post();
						get_template_part( 'template-parts/competition-card' );
					endwhile;
					echo '</div>';
					wp_reset_postdata();
				else :
					echo '<p>' . esc_html__( 'No competitions yet. Add a product to showcase it here.', 'wpraffle-theme' ) . '</p>';
				endif;
				?>
			<?php else : ?>
				<p class="text-center"><?php esc_html_e( 'Install the WPRaffles plugin (or WooCommerce) to show live competitions here.', 'wpraffle-theme' ); ?></p>
			<?php endif; ?>
		</div>
	</div>
</section>

<?php if ( $show_tabs ) : ?>
<script>
( function() {
	var grid    = document.getElementById( 'wprt-active-grid' );
	if ( ! grid ) { return; }
	var listEl  = grid.querySelector( '.wprt-active-list' );
	// Cache the un-filtered markup once, on load, so "All" can restore it cheaply.
	var defaultMarkup = listEl ? listEl.innerHTML : '';
	var buttons = document.querySelectorAll( '.wprt-filter-tabs__btn' );
	var ajaxUrl = ( window.wprThemeData && window.wprThemeData.ajaxUrl ) || null;
	var nonce   = ( window.wprThemeData && window.wprThemeData.nonce ) || '';

	buttons.forEach( function( btn ) {
		btn.addEventListener( 'click', function () {
			buttons.forEach( function ( b ) {
				b.classList.remove( 'is-active' );
				b.setAttribute( 'aria-selected', 'false' );
			} );
			btn.classList.add( 'is-active' );
			btn.setAttribute( 'aria-selected', 'true' );

			var cat = btn.getAttribute( 'data-wprt-filter' );
			applyFilter( cat );
		} );
	} );

	function applyFilter( cat ) {
		if ( ! listEl ) { return; }
		grid.classList.add( 'is-filtering' );

		// No category → restore the cached default markup (no server round-trip).
		if ( ! cat ) {
			listEl.innerHTML = defaultMarkup;
			grid.classList.remove( 'is-filtering' );
			return;
		}

		// Server render: fetch the filtered list via the AJAX endpoint registered
		// in class-wpraffle-theme-features.php (action=wprt_filter_raffles).
		if ( ! ajaxUrl ) {
			listEl.innerHTML = defaultMarkup;
			grid.classList.remove( 'is-filtering' );
			return;
		}

		fetch( ajaxUrl, {
			method: 'POST',
			headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
			body: 'action=wprt_filter_raffles&nonce=' + encodeURIComponent( nonce ) + '&category=' + encodeURIComponent( cat )
		} )
		.then( function ( r ) { return r.json(); } )
		.then( function ( res ) {
			// AJAX returns { success: true, data: markup } via wp_send_json.
			var html = ( res && res.success && res.data ) ? res.data : defaultMarkup;
			listEl.innerHTML = html;
		} )
		.catch( function () { listEl.innerHTML = defaultMarkup; } )
		.finally( function () { grid.classList.remove( 'is-filtering' ); } );
	}
} )();
</script>
<?php endif; ?>
