<?php
/**
 * 404 template — configurable via Theme Options.
 *
 * @package WPRaffle_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$s = WPRaffle_Theme_Settings::instance()->get_settings();

get_header();
?>
<main id="main" class="site-main section">
	<div class="container">
		<div class="text-center py-5 wprt-404" <?php echo $s['error_bg'] ? 'style="background:url(' . esc_url( $s['error_bg'] ) . ') center/cover;border-radius:1rem;padding:3rem 1rem;"' : ''; ?>>
			<div class="eyebrow"><?php esc_html_e( 'Error 404', 'wpraffle-theme' ); ?></div>
			<h1 class="mb-3"><?php echo esc_html( $s['error_heading'] ); ?></h1>
			<p class="text-muted mb-4"><?php echo esc_html( $s['error_text'] ); ?></p>
			<div class="wpr-rule mx-auto"></div>
			<?php if ( 'on' === $s['error_show_search'] ) : ?>
				<div class="mt-4 mb-3" style="max-width:400px;margin-left:auto;margin-right:auto;"><?php get_search_form(); ?></div>
			<?php endif; ?>
			<p class="mt-4">
				<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="btn btn-dark"><?php esc_html_e( 'Back to home', 'wpraffle-theme' ); ?></a>
				<?php if ( 'on' === $s['error_show_comps'] && class_exists( 'WooCommerce' ) ) : ?>
					<a href="<?php echo esc_url( wpraffle_theme_competitions_url() ); ?>" class="btn btn-accent"><?php esc_html_e( 'Browse competitions', 'wpraffle-theme' ); ?></a>
				<?php endif; ?>
			</p>
		</div>
	</div>
</main>
<?php
get_footer();
