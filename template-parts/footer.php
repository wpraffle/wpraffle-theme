<?php
/**
 * Native footer: brand column + widget columns + bottom bar.
 *
 * @package Diamond
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<div class="container">
	<div class="diamond-footer__top">

		<div class="diamond-footer__brand">
			<?php wpraffle_theme_logo(); ?>
			<p><?php echo esc_html( get_theme_mod( 'wpraffle_theme_footer_about', __( 'Luxury prizes won every week. Play fair, win big, support great causes.', 'wpraffle-theme' ) ) ); ?></p>
			<div class="diamond-footer__social"><?php wpraffle_theme_social_links(); ?></div>
		</div>

		<?php if ( is_active_sidebar( 'footer-1' ) ) : ?>
			<div class="diamond-footer__col"><?php dynamic_sidebar( 'footer-1' ); ?></div>
		<?php endif; ?>
		<?php if ( is_active_sidebar( 'footer-2' ) ) : ?>
			<div class="diamond-footer__col"><?php dynamic_sidebar( 'footer-2' ); ?></div>
		<?php endif; ?>
		<?php if ( is_active_sidebar( 'footer-3' ) ) : ?>
			<div class="diamond-footer__col"><?php dynamic_sidebar( 'footer-3' ); ?></div>
		<?php endif; ?>

	</div>

	<div class="diamond-footer__bottom">
		<div class="diamond-footer__copy"><?php wpraffle_theme_copyright(); ?></div>
		<div class="diamond-footer__payments" aria-hidden="true">
			<i class="fa-brands fa-cc-visa"></i>
			<i class="fa-brands fa-cc-mastercard"></i>
			<i class="fa-brands fa-cc-amex"></i>
			<i class="fa-brands fa-cc-paypal"></i>
			<i class="fa-brands fa-cc-apple-pay"></i>
		</div>
	</div>
</div>
