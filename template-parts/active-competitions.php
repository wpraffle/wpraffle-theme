<?php
if ( ! defined( 'ABSPATH' ) ) { exit; }
$terms = taxonomy_exists( 'raffle_category' ) ? get_terms( array( 'taxonomy' => 'raffle_category', 'hide_empty' => true ) ) : array();
$terms = is_wp_error( $terms ) ? array() : $terms;
$args = array( 'status' => 'active', 'per_page' => 9 );
foreach ( array( 'category','min_price','max_price','closing','availability','sort','status','page' ) as $key ) {
    if ( isset( $_GET[ 'wprt_' . $key ] ) && is_scalar( $_GET[ 'wprt_' . $key ] ) ) { $args[ $key ] = sanitize_text_field( wp_unslash( $_GET[ 'wprt_' . $key ] ) ); }
}
if ( class_exists( 'Raffle_Discovery' ) ) { $args = Raffle_Discovery::normalize( $args ); }
?>
<section id="active" class="section wprt-reveal"><div class="container">
<?php wpraffle_theme_section_heading( get_theme_mod( 'wpr_active_title', __( 'Competitions', 'wpraffle-theme' ) ), get_theme_mod( 'wpr_active_subtitle', __( 'Find your next competition.', 'wpraffle-theme' ) ), wpraffle_theme_competitions_url(), __( 'View all competitions', 'wpraffle-theme' ) ); ?>
<?php if ( wpraffle_theme_has_plugin() && class_exists( 'Raffle_Discovery' ) ) : ?>
<form method="get" class="wprt-discovery-form" aria-label="<?php esc_attr_e( 'Competition filters', 'wpraffle-theme' ); ?>" data-i18n="<?php echo esc_attr( wp_json_encode( array( 'loading' => __( 'Loading competitions…', 'wpraffle-theme' ), 'error' => __( 'Could not update results. Previous results are still shown.', 'wpraffle-theme' ), 'empty' => __( 'No competitions match these filters.', 'wpraffle-theme' ), 'clear' => __( 'Clear filters', 'wpraffle-theme' ), 'count' => __( '%d competitions found.', 'wpraffle-theme' ), 'page' => __( 'Page %d of %d', 'wpraffle-theme' ), 'remove' => __( 'Remove filter:', 'wpraffle-theme' ) ) ) ); ?>" data-nonce="<?php echo esc_attr( wp_create_nonce( 'wpr_nonce' ) ); ?>" data-endpoint="<?php echo esc_url( admin_url( 'admin-ajax.php' ) ); ?>">
<?php if ( is_page() && ! is_front_page() ) : ?><input type="hidden" name="page_id" value="<?php echo esc_attr( get_queried_object_id() ); ?>"><?php endif; ?>
<input type="hidden" name="wprt_page" value="<?php echo esc_attr( $args['page'] ); ?>">
<?php $selects = array(
 'status' => array( 'label' => __( 'Status', 'wpraffle-theme' ), 'values' => array( 'active' => __( 'Open', 'wpraffle-theme' ), 'upcoming' => __( 'Upcoming', 'wpraffle-theme' ), 'ended' => __( 'Ended', 'wpraffle-theme' ) ) ),
 'closing' => array( 'label' => __( 'Closing', 'wpraffle-theme' ), 'values' => array( '' => __( 'Any date', 'wpraffle-theme' ), '24h' => __( 'Within 24 hours', 'wpraffle-theme' ), '7d' => __( 'Within 7 days', 'wpraffle-theme' ) ) ),
 'availability' => array( 'label' => __( 'Availability', 'wpraffle-theme' ), 'values' => array( '' => __( 'Any availability', 'wpraffle-theme' ), 'available' => __( 'Tickets remaining', 'wpraffle-theme' ), 'sold_out' => __( 'Sold out', 'wpraffle-theme' ) ) ),
 'sort' => array( 'label' => __( 'Sort by', 'wpraffle-theme' ), 'values' => array( 'newest' => __( 'Newest', 'wpraffle-theme' ), 'closing' => __( 'Closing soon', 'wpraffle-theme' ), 'price_asc' => __( 'Price: low to high', 'wpraffle-theme' ), 'price_desc' => __( 'Price: high to low', 'wpraffle-theme' ), 'remaining' => __( 'Fewest tickets remaining', 'wpraffle-theme' ) ) ),
 );
$advanced = array( 'category','min_price','max_price','closing','availability' );
$count = 0; foreach ( $advanced as $key ) { if ( null !== $args[$key] && '' !== (string) $args[$key] ) { $count++; } }
$render_select = static function( $key, $select ) use ( $args ) { ?><label><?php echo esc_html( $select['label'] ); ?><select name="<?php echo esc_attr( 'wprt_' . $key ); ?>"><?php foreach ( $select['values'] as $value => $label ) : ?><option value="<?php echo esc_attr( $value ); ?>" <?php selected( $args[$key], $value ); ?>><?php echo esc_html( $label ); ?></option><?php endforeach; ?></select></label><?php };
?>
<div class="wprt-discovery-common"><?php $render_select( 'status', $selects['status'] ); $render_select( 'sort', $selects['sort'] ); ?><button type="submit" class="btn btn-accent"><?php esc_html_e( 'Apply filters', 'wpraffle-theme' ); ?></button><button type="reset" class="btn"><?php esc_html_e( 'Clear filters', 'wpraffle-theme' ); ?></button></div>
<details class="wprt-advanced-filters" <?php echo $count ? 'open' : ''; ?>><summary><?php esc_html_e( 'Filters', 'wpraffle-theme' ); ?> <span class="wprt-filter-count"><?php echo esc_html( $count ); ?></span></summary><div class="wprt-discovery-advanced">
<label><?php esc_html_e( 'Category', 'wpraffle-theme' ); ?><select name="wprt_category"><option value=""><?php esc_html_e( 'All categories', 'wpraffle-theme' ); ?></option><?php foreach ( $terms as $term ) : ?><option value="<?php echo esc_attr( $term->slug ); ?>" <?php selected( $args['category'], $term->slug ); ?>><?php echo esc_html( $term->name ); ?></option><?php endforeach; ?></select></label>
<?php foreach ( array( 'min_price' => __( 'Minimum price', 'wpraffle-theme' ), 'max_price' => __( 'Maximum price', 'wpraffle-theme' ) ) as $key => $label ) : ?><label><?php echo esc_html( $label ); ?><input type="number" step="0.01" min="0" name="<?php echo esc_attr( 'wprt_' . $key ); ?>" value="<?php echo esc_attr( $args[ $key ] ); ?>"></label><?php endforeach; ?>
<?php $render_select( 'closing', $selects['closing'] ); $render_select( 'availability', $selects['availability'] ); ?></div></details>
<div class="wprt-active-filters" aria-label="<?php esc_attr_e( 'Selected filters', 'wpraffle-theme' ); ?>"><?php foreach ( $advanced as $key ) : if ( null === $args[$key] || '' === (string) $args[$key] ) { continue; } ?><a href="<?php echo esc_url( remove_query_arg( array( 'wprt_' . $key, 'wprt_page' ) ) ); ?>"><?php echo esc_html( str_replace( '_', ' ', $key ) . ': ' . $args[$key] ); ?> ×</a><?php endforeach; ?></div>
</form>
<button type="button" class="wprt-discovery-retry button" hidden><?php esc_html_e( 'Retry', 'wpraffle-theme' ); ?></button>
<p class="wprt-discovery-status" role="status" aria-live="polite"></p>
<div class="wpr-active-grid" id="wprt-active-grid" aria-busy="false"><div class="wprt-active-list">
<?php $sc = '[raffle_list per_page="9"'; foreach ( $args as $key => $value ) { if ( is_scalar( $value ) && 'per_page' !== $key ) { $sc .= ' ' . sanitize_key( $key ) . '="' . esc_attr( (string) $value ) . '"'; } } echo do_shortcode( $sc . ']' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped shortcode renderer. ?>
</div></div>
<?php $total = Raffle_Discovery::query( $args, true ); $pages = (int) ceil( $total / 9 ); if ( ! $total ) : ?><p class="wprt-discovery-empty"><?php esc_html_e( 'No competitions match these filters. Clear filters or try a wider price range.', 'wpraffle-theme' ); ?></p><?php endif; ?>
<nav class="wprt-discovery-pagination" aria-label="<?php esc_attr_e( 'Competition pages', 'wpraffle-theme' ); ?>"><button type="button" class="button" data-page-step="-1" <?php disabled( $args['page'] <= 1 ); ?>><?php esc_html_e( 'Previous', 'wpraffle-theme' ); ?></button><span><?php echo esc_html( sprintf( __( 'Page %d of %d', 'wpraffle-theme' ), $args['page'], max( 1, $pages ) ) ); ?></span><button type="button" class="button" data-page-step="1" <?php disabled( $args['page'] >= $pages ); ?>><?php esc_html_e( 'Next', 'wpraffle-theme' ); ?></button></nav><noscript><nav aria-label="<?php esc_attr_e( 'Competition pages without JavaScript', 'wpraffle-theme' ); ?>"><?php if ( $args['page'] > 1 ) : ?><a href="<?php echo esc_url( add_query_arg( 'wprt_page', $args['page'] - 1 ) ); ?>"><?php esc_html_e( 'Previous', 'wpraffle-theme' ); ?></a><?php endif; ?> <?php if ( $args['page'] < $pages ) : ?><a href="<?php echo esc_url( add_query_arg( 'wprt_page', $args['page'] + 1 ) ); ?>"><?php esc_html_e( 'Next', 'wpraffle-theme' ); ?></a><?php endif; ?></nav></noscript>
<?php elseif ( wpraffle_theme_has_plugin() ) : ?><div class="wpr-active-grid"><?php echo do_shortcode( '[raffle_list status="active" per_page="9"]' ); ?></div>
<?php elseif ( class_exists( 'WooCommerce' ) ) : ?><p><a class="btn btn-accent" href="<?php echo esc_url( wc_get_page_permalink( 'shop' ) ); ?>"><?php esc_html_e( 'Browse competitions', 'wpraffle-theme' ); ?></a></p>
<?php else : ?><p><?php esc_html_e( 'Activate WPRaffle and WooCommerce to show competitions.', 'wpraffle-theme' ); ?></p><?php endif; ?>
</div></section>
