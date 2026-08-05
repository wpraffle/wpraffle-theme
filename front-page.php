<?php
/**
 * The homepage template.
 *
 * Renders sections dynamically based on the Homepage tab config (order +
 * enabled state) in Theme Options, with conditional display checks so empty
 * sections (e.g. no testimonials) don't render.
 *
 * @package WPRaffle_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

// Map section keys to their template part files.
$section_map = array(
	'hero'         => 'hero',
	'winners'      => 'winners-carousel',
	'active'       => 'active-competitions',
	'countdown'    => 'countdown',
	'live_draw'    => 'live-draw',
	'charity'      => 'charity-donations',
	'testimonials' => 'testimonials',
	'faq'          => 'faq',
	'trust'        => 'instant-payouts',
	// v1.2.0 new sections.
	'how_it_works' => 'how-it-works',
	'stats_counter' => 'stats-counter',
	'featured'     => 'featured-competition',
);

$sections = wpraffle_theme_get_homepage_sections();
?>
<main id="main" class="site-main">

	<?php
	// Hook before the first homepage section (for widget areas / promos).
	do_action( 'wpraffle_theme_before_homepage_sections' );

	foreach ( $sections as $section_key ) {
		if ( ! isset( $section_map[ $section_key ] ) ) {
			continue;
		}
		// Conditional display check (e.g. hide testimonials if none exist).
		if ( ! wpraffle_theme_section_should_show( $section_key ) ) {
			continue;
		}

		// Hook before each section.
		do_action( 'wpraffle_theme_before_section_' . $section_key );

		get_template_part( 'template-parts/' . $section_map[ $section_key ] );

		// Hook after each section.
		do_action( 'wpraffle_theme_after_section_' . $section_key );
	}

	// Hook after all homepage sections.
	do_action( 'wpraffle_theme_after_homepage_sections' );
	?>

</main>
<?php
get_footer();
