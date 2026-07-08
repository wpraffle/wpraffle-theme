<?php
/**
 * Active Competitions section — renders the live raffle grid via the WPRaffles
 * [raffle_list] shortcode when the plugin is active. Falls back to the WC shop
 * loop URL prompt otherwise.
 *
 * @package Diamond
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<section id="active" class="section">
	<div class="container">
		<?php
		wpraffle_theme_section_heading(
			get_theme_mod( 'diamond_active_title', __( 'Active Competitions', 'wpraffle-theme' ) ),
			get_theme_mod( 'diamond_active_subtitle', __( 'Enter now — limited tickets, guaranteed draws.', 'wpraffle-theme' ) ),
			wpraffle_theme_competitions_url(),
			__( 'View all competitions', 'wpraffle-theme' )
		);
		?>
		<div class="diamond-active-grid">
			<?php if ( wpraffle_theme_has_plugin() ) : ?>
				<?php echo do_shortcode( '[raffle_list status="active" columns="3" per_page="9"]' ); ?>
			<?php elseif ( class_exists( 'WooCommerce' ) ) : ?>
				<?php
				$loop = new WP_Query( array(
					'post_type'      => 'product',
					'posts_per_page' => 9,
					'meta_key'       => '_price', // phpcs:ignore WordPress.DB.SlowDBQuery
					'orderby'        => 'meta_value_num',
					'order'          => 'ASC',
				) );
				if ( $loop->have_posts() ) :
					echo '<div class="row g-4">';
					while ( $loop->have_posts() ) :
						$loop->the_post();
						get_template_part( 'template-parts/competition-card' );
					endwhile;
					echo '</div>';
					wp_reset_postdata();
				else :
					echo '<p>' . esc_html__( 'No competitions yet. Add a product to showcase it here.', 'wpraffle-theme' ) . '</p>';
				endif;
				?>
			<?php else : ?>
				<p class="text-center"><?php esc_html_e( 'Install the WPRaffles plugin (or WooCommerce) to show live competitions here.', 'wpraffle-theme' ); ?></p>
			<?php endif; ?>
		</div>
	</div>
</section>
