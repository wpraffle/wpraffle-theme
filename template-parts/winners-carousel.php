<?php
/**
 * Winners carousel — featured winners from the WPRaffles plugin.
 *
 * Pulls rows flagged is_featured=1 via Raffle_Featured_Winners::get_featured().
 * Each row carries the winner photo, name, prize title, prize image and an
 * optional testimonial. Falls back to a neutral placeholder grid when the
 * plugin is absent or no winners are featured yet.
 *
 * @package Diamond
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$winners = wpraffle_theme_has_plugin() ? WPRaffle_Theme_Integration::get_featured_winners( 8 ) : array();
?>
<section id="winners" class="section section--tint">
	<div class="container">
		<?php
		wpraffle_theme_section_heading(
			get_theme_mod( 'diamond_winners_title', __( 'Featured Winners', 'wpraffle-theme' ) ),
			get_theme_mod( 'diamond_winners_subtitle', __( 'Real prizes, real people, paid out instantly.', 'wpraffle-theme' ) ),
			wpraffle_theme_winners_url(),
			__( 'View all winners', 'wpraffle-theme' )
		);
		?>
		<div class="swiper diamond-swiper" data-diamond-carousel>
			<div class="swiper-wrapper">
				<?php if ( ! empty( $winners ) ) : ?>
					<?php foreach ( $winners as $fw ) : ?>
						<?php get_template_part( 'template-parts/winner-card', null, array( 'winner' => $fw ) ); ?>
					<?php endforeach; ?>
				<?php else : ?>
					<?php for ( $i = 0; $i < 6; $i++ ) : ?>
						<?php get_template_part( 'template-parts/winner-card', 'placeholder' ); ?>
					<?php endfor; ?>
				<?php endif; ?>
			</div>
			<div class="swiper-pagination"></div>
			<div class="swiper-button-prev"></div>
			<div class="swiper-button-next"></div>
		</div>
	</div>
</section>
