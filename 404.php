<?php
/**
 * 404 template.
 *
 * @package Diamond
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();
?>
<main id="main" class="site-main section">
	<div class="container">
		<div class="text-center py-5">
			<div class="eyebrow"><?php esc_html_e( 'Error 404', 'wpraffle-theme' ); ?></div>
			<h1 class="mb-3"><?php esc_html_e( 'Page not found', 'wpraffle-theme' ); ?></h1>
			<p class="text-muted mb-4"><?php esc_html_e( "The page you're looking for doesn't exist or has been moved.", 'wpraffle-theme' ); ?></p>
			<div class="diamond-rule mx-auto"></div>
			<?php get_search_form(); ?>
			<p class="mt-4">
				<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="btn btn-dark"><?php esc_html_e( 'Back to home', 'wpraffle-theme' ); ?></a>
				<?php if ( class_exists( 'WooCommerce' ) ) : ?>
					<a href="<?php echo esc_url( get_permalink( wc_get_page_id( 'shop' ) ) ); ?>" class="btn btn-accent"><?php esc_html_e( 'Browse competitions', 'wpraffle-theme' ); ?></a>
				<?php endif; ?>
			</p>
		</div>
	</div>
</main>
<?php
get_footer();
