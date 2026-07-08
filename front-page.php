<?php
/**
 * The homepage template. Assembles the Paragon-style sections in order.
 *
 * @package Diamond
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();
?>
<main id="main" class="site-main">

	<?php
	get_template_part( 'template-parts/hero' );
	get_template_part( 'template-parts/winners-carousel' );
	get_template_part( 'template-parts/active-competitions' );
	get_template_part( 'template-parts/charity-donations' );
	get_template_part( 'template-parts/instant-payouts' );
	?>

</main>
<?php
get_footer();
