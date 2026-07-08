<?php
/**
 * Homepage hero banner.
 *
 * @package Diamond
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$bg_id  = get_theme_mod( 'diamond_hero_bg' );
$bg_url = $bg_id ? wp_get_attachment_image_url( $bg_id, 'diamond-hero' ) : '';
?>
<section class="diamond-hero">
	<?php if ( $bg_url ) : ?>
		<div class="diamond-hero__bg" style="background-image:url('<?php echo esc_url( $bg_url ); ?>');"></div>
	<?php endif; ?>
	<div class="container">
		<div class="diamond-hero__inner">
			<span class="eyebrow" style="color:#fff;opacity:.85;"><?php echo esc_html( get_theme_mod( 'diamond_hero_eyebrow', __( 'Luxury Prize Competitions', 'wpraffle-theme' ) ) ); ?></span>
			<h1 class="diamond-hero__title">
				<?php
				echo wp_kses_post( get_theme_mod( 'diamond_hero_title', __( 'Win incredible prizes <span class="accent">every week</span>', 'wpraffle-theme' ) ) );
				?>
			</h1>
			<p class="diamond-hero__lead"><?php echo esc_html( get_theme_mod( 'diamond_hero_lead', __( 'Low fixed odds, instant payouts and 100% of ticket sales donated to charity. Enter your favourite competition today.', 'wpraffle-theme' ) ) ); ?></p>
			<div class="diamond-hero__actions">
				<?php if ( class_exists( 'WooCommerce' ) ) : ?>
					<a class="btn btn-accent btn-lg" href="<?php echo esc_url( get_permalink( wc_get_page_id( 'shop' ) ) ); ?>"><?php esc_html_e( 'Enter Competitions', 'wpraffle-theme' ); ?> <i class="fa-solid fa-arrow-right ms-1"></i></a>
				<?php endif; ?>
				<a class="btn btn-outline-light btn-lg" href="#winners"><?php esc_html_e( 'See Recent Winners', 'wpraffle-theme' ); ?></a>
			</div>

			<div class="diamond-hero__stats">
				<div class="diamond-hero__stat">
					<div class="num"><?php echo esc_html( get_theme_mod( 'diamond_stat_winners', '12,400+' ) ); ?></div>
					<div class="label"><?php esc_html_e( 'Winners', 'wpraffle-theme' ); ?></div>
				</div>
				<div class="diamond-hero__stat">
					<div class="num"><?php echo esc_html( get_theme_mod( 'diamond_stat_raised', '£2.8m' ) ); ?></div>
					<div class="label"><?php esc_html_e( 'Raised for Charity', 'wpraffle-theme' ); ?></div>
				</div>
				<div class="diamond-hero__stat">
					<div class="num"><?php echo esc_html( get_theme_mod( 'diamond_stat_rating', '4.9★' ) ); ?></div>
					<div class="label"><?php esc_html_e( 'Trustpilot', 'wpraffle-theme' ); ?></div>
				</div>
			</div>
		</div>
	</div>
</section>
