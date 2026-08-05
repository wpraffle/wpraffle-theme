<?php
/**
 * The footer template. Fires wpraffle_theme_footer() which either renders an
 * Elementor Theme Builder footer (if active) or the native footer partial.
 *
 * @package WPRaffle_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
	<footer class="wpr-footer site-footer">
		<?php wpraffle_theme_footer(); ?>
	</footer>
</div><!-- .site -->

<?php
// v1.1.0 conditional template parts.
$s = WPRaffle_Theme_Settings::instance()->get_settings();
if ( 'on' === $s['social_proof'] && wpraffle_theme_has_plugin() ) {
	get_template_part( 'template-parts/social-proof' );
}
if ( 'on' === $s['mobile_cta'] ) {
	get_template_part( 'template-parts/mobile-cta' );
}
?>

<?php wp_footer(); ?>
</body>
</html>
