<?php
/**
 * One-click importer for the Elementor JSON library bundled with the theme.
 *
 * @package WPRaffle_Theme
 * @since 1.4.0
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }

final class WPRaffle_Theme_Elementor_Importer {
    private static $instance = null;
    const SOURCE_META = '_wprt_source_template';

    public static function instance() {
        if ( null === self::$instance ) { self::$instance = new self(); }
        return self::$instance;
    }

    private function __construct() {
        add_action( 'admin_post_wprt_import_elementor_template', array( $this, 'handle_import_one' ) );
        add_action( 'admin_post_wprt_import_elementor_library', array( $this, 'handle_import_all' ) );
    }

    public static function available_templates() {
        return array(
            'theme-builder/home.json'          => __( 'Homepage', 'wpraffle-theme' ),
            'theme-builder/header.json'        => __( 'Header', 'wpraffle-theme' ),
            'theme-builder/footer.json'        => __( 'Footer', 'wpraffle-theme' ),
            'theme-builder/archive.json'       => __( 'Competition Archive', 'wpraffle-theme' ),
            'theme-builder/single-raffle.json' => __( 'Single Competition', 'wpraffle-theme' ),
            'theme-builder/single-charity.json'=> __( 'Single Charity', 'wpraffle-theme' ),
            'theme-builder/cart.json'          => __( 'Basket', 'wpraffle-theme' ),
            'theme-builder/checkout.json'      => __( 'Checkout', 'wpraffle-theme' ),
            'theme-builder/my-account.json'    => __( 'My Account', 'wpraffle-theme' ),
            'theme-builder/search.json'        => __( 'Search Results', 'wpraffle-theme' ),
            'theme-builder/404.json'           => __( '404 Page', 'wpraffle-theme' ),
            'sections/hero.json'               => __( 'Hero', 'wpraffle-theme' ),
            'sections/active-competitions.json'=> __( 'Active Competitions', 'wpraffle-theme' ),
            'sections/featured-spotlight.json' => __( 'Featured Competition', 'wpraffle-theme' ),
            'sections/winners-carousel.json'   => __( 'Winners Carousel', 'wpraffle-theme' ),
            'sections/countdown.json'          => __( 'Countdown', 'wpraffle-theme' ),
            'sections/live-draw.json'          => __( 'Live Draw', 'wpraffle-theme' ),
            'sections/how-it-works.json'       => __( 'How It Works', 'wpraffle-theme' ),
            'sections/stats-counter.json'      => __( 'Stats', 'wpraffle-theme' ),
            'sections/testimonials.json'       => __( 'Testimonials', 'wpraffle-theme' ),
            'sections/faq.json'                => __( 'FAQ', 'wpraffle-theme' ),
            'sections/charity-donations.json'  => __( 'Charity Donations', 'wpraffle-theme' ),
            'sections/instant-payouts.json'    => __( 'Instant Payouts / Trust', 'wpraffle-theme' ),
        );
    }

    public static function is_elementor_ready() {
        return did_action( 'elementor/loaded' ) || defined( 'ELEMENTOR_VERSION' );
    }

    public static function imported_post_id( $source ) {
        $posts = get_posts( array(
            'post_type'      => 'elementor_library',
            'post_status'    => array( 'publish', 'draft' ),
            'posts_per_page' => 1,
            'meta_key'       => self::SOURCE_META,
            'meta_value'     => $source,
            'fields'         => 'ids',
        ) );
        return ! empty( $posts ) ? (int) $posts[0] : 0;
    }

    public static function import_template( $source ) {
        $available = self::available_templates();
        if ( ! isset( $available[ $source ] ) ) {
            return new WP_Error( 'invalid_template', __( 'Unknown WPRaffle Elementor template.', 'wpraffle-theme' ) );
        }
        if ( ! self::is_elementor_ready() ) {
            return new WP_Error( 'elementor_inactive', __( 'Elementor must be active before templates can be imported.', 'wpraffle-theme' ) );
        }
        $path = WPRAFFLE_THEME_DIR . '/elementor/' . $source;
        if ( ! is_readable( $path ) ) {
            return new WP_Error( 'missing_template', __( 'The bundled template file could not be read.', 'wpraffle-theme' ) );
        }
        $data = json_decode( file_get_contents( $path ), true ); // phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents
        if ( ! is_array( $data ) || empty( $data['content'] ) || ! is_array( $data['content'] ) ) {
            return new WP_Error( 'invalid_json', __( 'The bundled Elementor template is invalid.', 'wpraffle-theme' ) );
        }

        $post_id = self::imported_post_id( $source );
        $postarr = array(
            'post_type'   => 'elementor_library',
            'post_status' => 'publish',
            'post_title'  => ! empty( $data['title'] ) ? sanitize_text_field( $data['title'] ) : $available[ $source ],
        );
        if ( $post_id ) { $postarr['ID'] = $post_id; }
        $post_id = wp_insert_post( wp_slash( $postarr ), true );
        if ( is_wp_error( $post_id ) ) { return $post_id; }

        $type = ! empty( $data['type'] ) ? sanitize_key( $data['type'] ) : 'section';
        if ( 'wp-page' === $type ) { $type = 'page'; }
        update_post_meta( $post_id, '_elementor_edit_mode', 'builder' );
        update_post_meta( $post_id, '_elementor_template_type', $type );
        update_post_meta( $post_id, '_elementor_data', wp_slash( wp_json_encode( $data['content'] ) ) );
        update_post_meta( $post_id, '_elementor_page_settings', isset( $data['page_settings'] ) && is_array( $data['page_settings'] ) ? $data['page_settings'] : array() );
        update_post_meta( $post_id, '_elementor_version', defined( 'ELEMENTOR_VERSION' ) ? ELEMENTOR_VERSION : ( isset( $data['version'] ) ? sanitize_text_field( $data['version'] ) : '3.0.0' ) );
        update_post_meta( $post_id, self::SOURCE_META, $source );
        update_post_meta( $post_id, '_wprt_import_version', WPRAFFLE_THEME_VERSION );

        // Safe global display conditions for header/footer when Theme Builder
        // support is available. More specific single/archive conditions remain
        // under operator control because sites may mix raffle/non-raffle products.
        if ( wpraffle_theme_has_elementor_pro() && in_array( $type, array( 'header', 'footer' ), true ) ) {
            update_post_meta( $post_id, '_elementor_conditions', array( 'include/general' ) );
        }
        return (int) $post_id;
    }

    public function handle_import_one() {
        if ( ! current_user_can( 'manage_options' ) ) { wp_die( esc_html__( 'Permission denied.', 'wpraffle-theme' ) ); }
        check_admin_referer( 'wprt_import_elementor_template' );
        $source = isset( $_POST['template'] ) ? sanitize_text_field( wp_unslash( $_POST['template'] ) ) : '';
        $result = self::import_template( $source );
        $args = is_wp_error( $result ) ? array( 'elementor_error' => $result->get_error_code() ) : array( 'elementor_imported' => $result );
        wp_safe_redirect( add_query_arg( array_merge( array( 'page'=>'wpraffle-theme-settings','tab'=>'templates' ), $args ), admin_url( 'themes.php' ) ) );
        exit;
    }

    public function handle_import_all() {
        if ( ! current_user_can( 'manage_options' ) ) { wp_die( esc_html__( 'Permission denied.', 'wpraffle-theme' ) ); }
        check_admin_referer( 'wprt_import_elementor_library' );
        $ok = 0; $errors = 0;
        foreach ( array_keys( self::available_templates() ) as $source ) {
            $result = self::import_template( $source );
            if ( is_wp_error( $result ) ) { $errors++; } else { $ok++; }
        }
        wp_safe_redirect( add_query_arg( array( 'page'=>'wpraffle-theme-settings','tab'=>'templates','elementor_imported_all'=>$ok,'elementor_errors'=>$errors ), admin_url( 'themes.php' ) ) );
        exit;
    }
}
