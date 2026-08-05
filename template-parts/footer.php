<?php
/**
 * Native footer: configurable columns + optional CTA strip + bottom bar.
 *
 * @package WPRaffle_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$s        = WPRaffle_Theme_Settings::instance()->get_settings();
$cols     = absint( $s['footer_columns'] );
?>
<div class="container">
	<?php if ( 'on' === $s['footer_cta'] && $s['footer_cta_title'] ) : ?>
		<div class="wpr-footer__cta">
			<div>
				<h3><?php echo esc_html( $s['footer_cta_title'] ); ?></h3>
				<?php if ( $s['footer_cta_text'] ) : ?>
					<p><?php echo esc_html( $s['footer_cta_text'] ); ?></p>
				<?php endif; ?>
			</div>
			<?php if ( $s['footer_cta_btn'] && $s['footer_cta_url'] ) : ?>
				<a href="<?php echo esc_url( $s['footer_cta_url'] ); ?>" class="btn btn-accent btn-lg"><?php echo esc_html( $s['footer_cta_btn'] ); ?></a>
			<?php endif; ?>
		</div>
	<?php endif; ?>

	<div class="wpr-footer__top wpr-footer__top--<?php echo esc_attr( $cols ); ?>col">

		<div class="wpr-footer__brand">
			<?php wpraffle_theme_logo(); ?>
			<p><?php echo esc_html( get_theme_mod( 'wpraffle_theme_footer_about', __( 'Luxury prizes won every week. Play fair, win big, support great causes.', 'wpraffle-theme' ) ) ); ?></p>
			<div class="wpr-footer__social"><?php wpraffle_theme_social_links(); ?></div>
		</div>

		<?php for ( $i = 1; $i <= max( 0, $cols - 1 ); $i++ ) : ?>
			<?php if ( is_active_sidebar( 'footer-' . $i ) ) : ?>
				<div class="wpr-footer__col"><?php dynamic_sidebar( 'footer-' . $i ); ?></div>
			<?php endif; ?>
		<?php endfor; ?>

	</div>

		<?php if ( 'on' === $s['footer_newsletter'] ) : ?>
			<div class="wpr-footer__newsletter">
				<h3><?php esc_html_e( 'Get the latest competitions in your inbox', 'wpraffle-theme' ); ?></h3>
				<form class="wprt-newsletter-form" onsubmit="return false;">
					<input type="email" placeholder="<?php esc_attr_e( 'Your email address', 'wpraffle-theme' ); ?>" required>
					<button type="submit" class="btn btn-accent"><?php esc_html_e( 'Subscribe', 'wpraffle-theme' ); ?></button>
				</form>
			</div>
		<?php endif; ?>

		<?php
		// v1.2.0 Enhancement R — Instagram feed (completes the footer_instagram stub).
		if ( 'on' === $s['footer_instagram'] && ! empty( $s['instagram_feed_url'] ) ) :
			$handle = '';
			$p = wp_parse_url( $s['instagram_feed_url'] );
			if ( ! empty( $p['path'] ) ) {
				$parts = array_filter( explode( '/', trim( $p['path'], '/' ) ) );
				if ( ! empty( $parts ) ) {
					$handle = '@' . rawurldecode( $parts[0] );
				}
			}
			?>
			<div class="wpr-footer__instagram">
				<h3><?php echo esc_html( sprintf( __( 'Follow us on Instagram %s', 'wpraffle-theme' ), $handle ) ); ?></h3>
				<div class="wprt-instagram-feed">
					<?php for ( $i = 0; $i < 6; $i++ ) : ?>
						<a class="wprt-instagram-feed__item" href="<?php echo esc_url( $s['instagram_feed_url'] ); ?>" target="_blank" rel="noopener">
							<img src="data:image/svg+xml;charset=utf-8,%3Csvg xmlns='http://www.w3.org/2000/svg' width='300' height='300'%3E%3Crect width='100%25' height='100%25' fill='%23eee'/%3E%3C/svg%3E" alt="<?php esc_attr_e( 'Instagram post', 'wpraffle-theme' ); ?>" loading="lazy">
						</a>
					<?php endfor; ?>
				</div>
				<p class="description" style="margin-top:0.5rem;">
					<?php esc_html_e( 'Connect a real Instagram feed via the Instagram Basic Display API, or replace these placeholders with an oEmbed widget.', 'wpraffle-theme' ); ?>
				</p>
			</div>
		<?php endif; ?>

	<div class="wpr-footer__bottom">
		<div class="wpr-footer__copy"><?php wpraffle_theme_copyright(); ?></div>
		<div class="wpr-footer__payments" aria-hidden="true">
			<i class="fa-brands fa-cc-visa"></i>
			<i class="fa-brands fa-cc-mastercard"></i>
			<i class="fa-brands fa-cc-amex"></i>
			<i class="fa-brands fa-cc-paypal"></i>
			<i class="fa-brands fa-cc-apple-pay"></i>
		</div>
	</div>

	<?php
	// v1.2.0 Responsible-play bar (DCMS Voluntary Code compliance).
	if ( 'on' === $s['responsible_play'] ) {
		get_template_part( 'template-parts/responsible-play' );
	}
	?>
</div>
