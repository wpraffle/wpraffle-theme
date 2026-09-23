<?php
/**
 * Default page template.
 * @package WPRaffle_Theme
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }
$page_content      = (string) get_post_field( 'post_content', get_queried_object_id() );
$is_raffle_listing = has_shortcode( $page_content, 'raffle_list' ) || has_block( 'raffle/list', get_queried_object_id() );
$is_account        = function_exists( 'is_account_page' ) && is_account_page();
$main_classes      = array( 'site-main', 'wprt-page-template', 'wprt-page-default' );
if ( $is_raffle_listing ) {
	$main_classes[] = 'wprt-page-competitions';
	$main_classes[] = 'wprt-page-raffles';
}
if ( $is_account ) {
	$main_classes[] = 'wprt-page-account';
}
get_header();
?>
<main id="primary" class="<?php echo esc_attr( implode( ' ', $main_classes ) ); ?>">
	<?php while ( have_posts() ) : the_post(); ?>
	<section class="wprt-page-hero wprt-page-hero--compact"><div class="container"><?php if ( $is_raffle_listing ) : ?><span class="wprt-page-kicker"><?php esc_html_e( 'Choose your next competition', 'wpraffle-theme' ); ?></span><?php elseif ( has_excerpt() ) : ?><span class="wprt-page-kicker"><?php echo esc_html( get_the_excerpt() ); ?></span><?php endif; ?><h1><?php the_title(); ?></h1></div></section>
	<?php if ( has_post_thumbnail() ) : ?><div class="container wpr-page-featured"><?php the_post_thumbnail( 'wpr-card-wide' ); ?></div><?php endif; ?>
	<?php if ( $is_raffle_listing ) : ?>
	<section id="active" class="section wprt-reveal">
		<div class="container">
			<?php wpraffle_theme_section_heading( __( 'Active Competitions', 'wpraffle-theme' ), __( 'Enter now — limited tickets, guaranteed draws.', 'wpraffle-theme' ) ); ?>
			<div class="wpr-active-grid"><div class="wprt-active-list"><?php the_content(); wp_link_pages(); ?></div></div>
		</div>
	</section>
	<?php else : ?>
	<section class="wprt-page-content"><div class="container<?php echo $is_account ? ' wprt-account-width' : ' wprt-reading-width'; ?>"><?php the_content(); wp_link_pages(); ?></div></section>
	<?php endif; ?>
	<?php if ( comments_open() || get_comments_number() ) : ?><div class="container wprt-reading-width"><?php comments_template(); ?></div><?php endif; ?>
	<?php endwhile; ?>
</main>
<?php get_footer(); ?>
