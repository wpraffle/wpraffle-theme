<?php
/**
 * Instant Payouts / trust block: Secure / Free entry / Verified draw.
 *
 * @package Diamond
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<section class="section">
	<div class="container">
		<?php
		wpraffle_theme_section_heading(
			get_theme_mod( 'diamond_trust_title', __( 'Play With Confidence', 'wpraffle-theme' ) ),
			get_theme_mod( 'diamond_trust_subtitle', __( 'Secure, transparent and fully verified.', 'wpraffle-theme' ) )
		);
		?>
		<div class="diamond-trust">
			<div class="diamond-trust__item">
				<div class="diamond-trust__icon diamond-trust__icon--secure"><i class="fa-solid fa-lock"></i></div>
				<h3><?php esc_html_e( 'Secure Payments', 'wpraffle-theme' ); ?></h3>
				<p><?php esc_html_e( 'Bank-grade encryption on every transaction. Your details are never stored.', 'wpraffle-theme' ); ?></p>
			</div>
			<div class="diamond-trust__item">
				<div class="diamond-trust__icon diamond-trust__icon--free"><i class="fa-solid fa-gift"></i></div>
				<h3><?php esc_html_e( 'Free Entry Route', 'wpraffle-theme' ); ?></h3>
				<p><?php esc_html_e( 'Every competition has a no-purchase-necessary postal entry option.', 'wpraffle-theme' ); ?></p>
			</div>
			<div class="diamond-trust__item">
				<div class="diamond-trust__icon diamond-trust__icon--verified"><i class="fa-solid fa-certificate"></i></div>
				<h3><?php esc_html_e( 'Verified Draws', 'wpraffle-theme' ); ?></h3>
				<p><?php esc_html_e( 'All draws are independently verified and recorded for full transparency.', 'wpraffle-theme' ); ?></p>
			</div>
		</div>
	</div>
</section>
