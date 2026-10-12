<?php
if ( ! defined( 'ABSPATH' ) ) { exit; }
final class WPRaffle_Theme_Preview {
    public static function init() {
        add_action( 'wp_ajax_wprt_preview', array( __CLASS__, 'create' ) );
        add_filter( 'wpraffle_theme_settings', array( __CLASS__, 'settings' ) );
        add_action( 'template_redirect', static function() { if ( isset( $_GET['wprt_preview'] ) ) { nocache_headers(); header( 'X-Robots-Tag: noindex, nofollow', true ); } }, 1 );
    }
    public static function clean( $posted ) {
        $posted = is_array( $posted ) ? $posted : array(); $clean = array();
        $defaults = WPRaffle_Theme_Settings::get_defaults();
        if ( isset( $posted['sections'] ) && is_array( $posted['sections'] ) ) {
            foreach ( $defaults['sections'] as $key => $cfg ) {
                $value = isset( $posted['sections'][ $key ] ) && is_array( $posted['sections'][ $key ] ) ? $posted['sections'][ $key ] : array();
                $clean['sections'][ $key ] = array( 'enabled' => ! empty( $value['enabled'] ), 'order' => isset( $value['order'] ) && is_scalar( $value['order'] ) ? max( 0, min( 100, (int) $value['order'] ) ) : $cfg['order'] );
            }
        }
        $enums = array( 'card_ratio' => array( '4-3','16-9','square' ), 'card_title_pos' => array( 'below','overlay' ), 'card_progress' => array( 'thick','thin','hidden' ), 'card_hover' => array( 'lift','zoom','border' ), 'hero_primary_link_type' => array( 'shop','page','custom' ), 'hero_secondary_link_type' => array( 'winners-section','winners-page','page','custom' ) );
        foreach ( $enums as $key => $values ) { if ( isset( $posted[ $key ] ) && is_string( $posted[ $key ] ) && in_array( $posted[ $key ], $values, true ) ) { $clean[ $key ] = $posted[ $key ]; } }
        foreach ( array( 'hero_primary_text','hero_secondary_text' ) as $key ) { if ( isset( $posted[ $key ] ) && is_scalar( $posted[ $key ] ) ) { $clean[ $key ] = sanitize_text_field( $posted[ $key ] ); } }
        foreach ( array( 'hero_primary_url','hero_secondary_url' ) as $key ) { if ( isset( $posted[ $key ] ) && is_scalar( $posted[ $key ] ) ) { $clean[ $key ] = esc_url_raw( $posted[ $key ], array( 'http','https' ) ); } }
        foreach ( array( 'hero_primary_page_id','hero_secondary_page_id' ) as $key ) { if ( isset( $posted[ $key ] ) && is_scalar( $posted[ $key ] ) ) { $clean[ $key ] = absint( $posted[ $key ] ); } }
        foreach ( array( 'hero_primary_new_tab','hero_secondary_new_tab' ) as $key ) { if ( isset( $posted[ $key ] ) ) { $clean[ $key ] = 'on' === $posted[ $key ] ? 'on' : ''; } }
        if ( isset( $posted['card_fields'] ) ) { $clean['card_fields'] = self::card_fields( $posted['card_fields'] ); }
        return $clean;
    }
    public static function card_fields( $fields ) {
        $allowed = array( 'title','image','draw','countdown','cash','price','progress','charity','cta' );
        if ( ! is_array( $fields ) ) { return $allowed; }
        $clean = array(); foreach ( $fields as $field ) { if ( is_string( $field ) && in_array( $field, $allowed, true ) && ! in_array( $field, $clean, true ) ) { $clean[] = $field; } }
        foreach ( array( 'title','image','price' ) as $field ) { if ( ! in_array( $field, $clean, true ) ) { $clean[] = $field; } }
        $clean = array_values( array_diff( $clean, array( 'cta' ) ) ); $clean[] = 'cta'; return $clean;
    }
    public static function create() {
        if ( ! current_user_can( 'manage_options' ) ) { wp_send_json_error( array( 'message' => 'Administrator access required.' ), 403 ); }
        check_ajax_referer( 'wprt_preview', 'nonce' );
        $posted = isset( $_POST['wpr_settings'] ) ? wp_unslash( $_POST['wpr_settings'] ) : array();
        $token = wp_generate_uuid4();
        // One transient per administrator; each refresh revokes the previous preview.
        set_transient( 'wprt_preview_' . get_current_user_id(), array( 'token' => $token, 'settings' => self::clean( $posted ) ), 15 * MINUTE_IN_SECONDS );
        wp_send_json_success( array( 'url' => add_query_arg( 'wprt_preview', $token, home_url( '/' ) ) ) );
    }
    public static function settings( $settings ) {
        if ( ! isset( $_GET['wprt_preview'] ) || ! is_scalar( $_GET['wprt_preview'] ) || ! current_user_can( 'manage_options' ) ) { return $settings; }
        $preview = get_transient( 'wprt_preview_' . get_current_user_id() );
        $token = sanitize_text_field( wp_unslash( $_GET['wprt_preview'] ) );
        return is_array( $preview ) && isset( $preview['token'], $preview['settings'] ) && hash_equals( $preview['token'], $token ) ? array_merge( $settings, $preview['settings'] ) : $settings;
    }
    public static function panel() {
        echo '<section class="wprt-preview-panel"><h3>' . esc_html__( 'Preview unsaved changes', 'wpraffle-theme' ) . '</h3><p>' . esc_html__( 'Refresh the preview to view changes without publishing. The private preview expires after 15 minutes.', 'wpraffle-theme' ) . '</p><button type="button" class="button" data-wprt-preview data-nonce="' . esc_attr( wp_create_nonce( 'wprt_preview' ) ) . '">' . esc_html__( 'Refresh live preview', 'wpraffle-theme' ) . '</button><p role="status" class="wprt-preview-status"></p><iframe title="' . esc_attr__( 'Homepage preview', 'wpraffle-theme' ) . '" class="wprt-preview-frame" hidden style="width:100%;height:650px;border:1px solid #aaa"></iframe></section>';
    }
}
WPRaffle_Theme_Preview::init();
