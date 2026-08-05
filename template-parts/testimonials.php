<?php
/**
 * Testimonials carousel — pulls from the Theme Options Testimonials tab.
 *
 * v1.2.0 Addition J: when `testimonials_trustpilot` is on AND a Trustpilot
 * Business Unit ID is configured, the section instead renders the official
 * Trustpilot reviews carousel (genuine integration — the theme never fakes
 * Trustpilot styling on manual testimonials). Otherwise the manual carousel
 * is shown with its own distinct gold-star styling.
 *
 * @package WPRaffle_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$s            = WPRaffle_Theme_Settings::instance()->get_settings();
$testimonials = isset( $s['testimonial_items'] ) && is_array( $s['testimonial_items'] ) ? $s['testimonial_items'] : array();

$tp_id   = ! empty( $s['trustpilot_business_id'] ) ? $s['trustpilot_business_id'] : '';
$use_tp  = 'on' === $s['testimonials_trustpilot'] && $tp_id;

if ( ! $use_tp && empty( $testimonials ) ) {
	return;
}
?>
<section id="testimonials" class="section section--tint<?php echo $use_tp ? ' is-trustpilot' : ''; ?>">
	<div class="container">
		<?php
		wpraffle_theme_section_heading(
			wpraffle_theme_mod( 'wpr_testimonials_title', __( 'What Our Players Say', 'wpraffle-theme' ) ),
			wpraffle_theme_mod( 'wpr_testimonials_subtitle', __( 'Real reviews from real winners.', 'wpraffle-theme' ) )
		);
		?>

		<?php if ( $use_tp ) : ?>
			<?php
			// Real Trustpilot reviews carousel. The TrustBox bootstrap script is
			// loaded in WPRaffle_Theme_Features::maybe_enqueue_trustpilot().
			?>
			<div class="wprt-trustpilot-slot">
				<div class="trustpilot-widget" data-locale="en-GB" data-template-id="54ad27defe574d092d226eb1" data-businessunit-id="<?php echo esc_attr( $tp_id ); ?>" data-style-height="420px" data-style-width="100%" data-theme="light" data-stars="4,5">
					<a href="https://uk.trustpilot.com/review/<?php echo esc_attr( wp_parse_url( home_url(), PHP_URL_HOST ) ); ?>" target="_blank" rel="noopener"><?php esc_html_e( 'Trustpilot', 'wpraffle-theme' ); ?></a>
				</div>
			</div>
		<?php else : ?>
			<div class="swiper wprt-testimonials-swiper" data-wpr-carousel data-wpr-carousel-options='{"slidesPerView":1,"breakpoints":{"768":{"slidesPerView":2},"1200":{"slidesPerView":3}}}'>
				<div class="swiper-wrapper">
					<?php foreach ( $testimonials as $t ) : ?>
						<?php
						$name    = isset( $t['name'] ) ? $t['name'] : '';
						$content = isset( $t['content'] ) ? $t['content'] : '';
						$photo   = isset( $t['photo'] ) ? $t['photo'] : '';
						if ( ! $name && ! $content ) {
							continue;
						}
						?>
						<div class="swiper-slide">
							<div class="wprt-testimonial-card">
								<?php if ( $photo ) : ?>
									<div class="wprt-testimonial-photo"><img src="<?php echo esc_url( $photo ); ?>" alt="<?php echo esc_attr( $name ); ?>" width="64" height="64"></div>
								<?php endif; ?>
								<div class="wprt-testimonial-stars">
									<i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i>
								</div>
								<?php if ( $content ) : ?>
									<div class="wprt-testimonial-content"><?php echo wp_kses_post( wpautop( $content ) ); ?></div>
								<?php endif; ?>
								<?php if ( $name ) : ?>
									<div class="wprt-testimonial-name"><?php echo esc_html( $name ); ?></div>
								<?php endif; ?>
							</div>
						</div>
					<?php endforeach; ?>
				</div>
				<div class="swiper-pagination"></div>
				<div class="swiper-button-prev"></div>
				<div class="swiper-button-next"></div>
			</div>
		<?php endif; ?>
	</div>
</section>

